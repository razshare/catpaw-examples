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
	 * @throws CancelledException
	 * @throws ClosedException
	 * @throws ConnectException
	 * @throws StreamException
	 * @throws SvelteException
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

		[ "process" => $process, "secret"  => $secret ] = yield $svelte->start($hostname, $port, $secret, $dump, function () {
			echo "Process is over.\n";
		});

		try {
			$svelte = yield $svelte->connect($hostname, $port, $secret);
			/** @var SvelteExchanger $svelte */


			echo yield $svelte->ssr(
				<<<SVELTE
				<script>
					export let name = 'world'
				</script>
				<!-- this is a comment -->
				<b>hello {name}</b>
				SVELTE,
				[
					"name" => "world!"
				]
			);
			echo PHP_EOL;
		} catch (Throwable $e) {
			echo $e->getMessage() . PHP_EOL;
		}

		try {
			$process->kill();
		} catch (Throwable $e) {
			echo $e->getMessage();
		}
	}
}
