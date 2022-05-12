<?php

namespace {

	use Amp\ByteStream\ClosedException;
	use Amp\ByteStream\StreamException;
	use Amp\CancelledException;
	use Amp\Process\Process;
	use Amp\Socket\ConnectException;
	use CatPaw\Svelte\Exceptions\SvelteException;
	use CatPaw\Svelte\Services\SvelteService;
	use CatPaw\Svelte\SvelteExchanger;
	use CatPaw\Utilities\Strings;
	use CatPaw\Web\Attributes\StartWebServer;
	use CatPaw\Web\Utilities\Route;
	use Psr\Log\LoggerInterface;

	/**
	 * 
	 * @param SvelteService $svelte 
	 * @param LoggerInterface $logger 
	 * @return Generator 
	 * @throws StreamException
	 * @throws ClosedException
	 * @throws CancelledException 
	 * @throws ConnectException 
	 * @throws Error 
	 */
	#[StartWebServer]
	function main(
		SvelteService $svelte,
		LoggerInterface $logger,
	): Generator {
		/** @var Process $process */
		[
			"process" => $process,
			"secret" => $secret,
		] = yield $svelte->start("127.0.0.1", 5757, Strings::uuid(), './providers/tmp', function () {
		});

		$pid = $process->getPid();


		$logger->info("Svelte provider process started with pid $pid");

		/** @var SvelteExchanger $exchange */
		$exchange = yield $svelte->connect("127.0.0.1", 5757, $secret);


		Route::get("/", function () use ($exchange) {
			return yield $exchange->ssr(<<<SVELTE
			hello
			SVELTE);
		});
	}
}
