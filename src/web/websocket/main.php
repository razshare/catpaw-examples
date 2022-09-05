<?php

namespace {
    use Amp\Websocket\Server\Gateway;
    use Amp\Websocket\{Client, Message};
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

                    public function onStart(Gateway $gateway) {
                        // TODO: Implement onStart() method.
                        $this->logger->info("Connection opened.");
                    }

                    public function onMessage(Message $message, Gateway $gateway, Client $client) {
                        // TODO: Implement onMessage() method.
                        $this->logger->info("Message:".(yield $message->read()));
                    }

                    public function onClose(Client $client, int $code, string $reason) {
                        // TODO: Implement onClose() method.
                        $this->logger->info("Connection closed.");
                    }

                    public function onError(Throwable $e) {
                        // TODO: Implement onError() method.
                        $this->logger->error($e->getMessage());
                    }
                };
            }
        );
    }
}