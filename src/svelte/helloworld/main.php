<?php

namespace {

	use Amp\ByteStream\ClosedException;
	use Amp\ByteStream\StreamException;
	use Amp\CancelledException;
	use Amp\Process\Process;
	use Amp\Socket\ConnectException;
	use CatPaw\Svelte\Exceptions\SvelteException;
	use CatPaw\Svelte\Services\RequestEncoderService;
	use CatPaw\Svelte\Services\SvelteService;
	use CatPaw\Svelte\SvelteExchanger;
	use CatPaw\Utilities\Strings;

	/**
	 * @param SvelteService         $svelte
	 * @param RequestEncoderService $encoder
	 * @return Generator
	 * @throws StreamException
	 * @throws ClosedException
	 * @throws CancelledException
	 * @throws ConnectException
	 */
	function main(
		SvelteService         $svelte,
		RequestEncoderService $encoder,
	): Generator {
		$hostname = "127.0.0.1";
		$port = 5757;
		$secret = Strings::uuid();
		$dump = "./providers/tmp";

		/** @var Process $process */

		[
			"process" => $process,
			"secret"  => $secret,
		] = yield $svelte->start($hostname, $port, $secret, $dump, function() {
			echo "Process is over.\n";
		});

		/** @var SvelteExchanger $svelte */
		try {
			$svelte = yield $svelte->connect($hostname, $port, $secret);

			$source = $encoder
				->setGenerate('ssr')
				->setFormat('esm')
				->setProperties([
									"name" => "test",
								])
				->setBody(<<<SVELTE
				<script>
					export let name = 'world'
				</script>
				<!-- this is a comment -->
				<b>hello {name}</b>
				SVELTE
				)
				->build();

			echo yield $svelte->ssr($source);
			echo PHP_EOL;
		} catch(Throwable $e) {
			echo $e->getMessage().PHP_EOL;
		}

		try {
			$process->kill();
		} catch(Throwable $e) {
			echo $e->getMessage();
		}
	}
}