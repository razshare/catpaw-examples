<?php

namespace {

    use function Amp\call;
    use Amp\Promise;
    use Amp\Websocket\Client;
    use Amp\Websocket\Message;
    use Amp\Websocket\Server\Gateway;
    use CatPaw\Web\Attributes\StartWebServer;
    use CatPaw\Web\Interfaces\WebSocketInterface;
    use CatPaw\Web\Utilities\Route;

    use Psr\Log\LoggerInterface;

    #[StartWebServer]
    function main() {
        Route::get(
            path    : "/",
            callback: function(
                LoggerInterface $logger
            ) {
                return new class($logger) implements WebSocketInterface {
                    public function __construct(private LoggerInterface $logger) {
                    }

                    public function onStart(Gateway $gateway):Promise {
                        // TODO: Implement onStart() method.
                        return call(fn() => true);
                    }

                    public function onMessage(Message $message, Gateway $gateway, Client $client): Promise {
                        // TODO: Implement onMessage() method.
                        return call(fn() => $this->logger->info("Message:".(yield $message->read())));
                    }

                    public function onClose(Client $client, int $code, string $reason):Promise {
                        // TODO: Implement onClose() method.
                        return call(fn() => true);
                    }

                    public function onError(Throwable $e):Promise {
                        // TODO: Implement onError() method.
                        return call(fn() => $this->logger->error($e->getMessage()));
                    }
                };
            }
        );
    }
}