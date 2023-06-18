<?php

use Amp\Websocket\Server\Gateway;
use Amp\Websocket\{Client, Message};
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Interfaces\WebSocketInterface;
use CatPaw\Web\Server;
use CatPaw\Web\Utilities\Route;
use Psr\Log\LoggerInterface;

function main() {
    /*
        
     */

    Route::get(
        path: "/",
        callback: #[Produces("text/html")] fn () => <<<HTML
            Try connecting to the server by running the following script in your browser:

            <pre style="border:1px solid #000"><code>
            const socket = new WebSocket("ws://localhost:8080/ws");

            socket.addEventListener("open", (event) => {
                socket.send("Hello server!");
            });

            socket.addEventListener("message", (event) => {
                console.log(event.data)
            });
            </code></pre>
            HTML
    );

    // TODO: fix for fibers
    // Route::get(
    //     path    : "/ws",
    //     callback: function(
    //         LoggerInterface $logger
    //     ) {
    //         return new class($logger) implements WebSocketInterface {
    //             public function __construct(private LoggerInterface $logger) {
    //             }

    //             public function onStart(Gateway $gateway) {
    //                 // TODO: Implement onStart() method.
    //                 $this->logger->info("Connection opened.");
    //             }

    //             public function onMessage(Message $message, Gateway $gateway, Client $client) {
    //                 yield $client->send("Hello client!");
    //                 // TODO: Implement onMessage() method.
    //                 $this->logger->info("Message:".(yield $message->read()));
    //             }

    //             public function onClose(Client $client, int $code, string $reason) {
    //                 // TODO: Implement onClose() method.
    //                 $this->logger->info("Connection closed.");
    //             }

    //             public function onError(Throwable $e) {
    //                 // TODO: Implement onError() method.
    //                 $this->logger->error($e->getMessage());
    //             }
    //         };
    //     }
    // );

    Server::create()->create();
}