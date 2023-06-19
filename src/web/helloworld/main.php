<?php

use CatPaw\Web\Server;

function main() {
    $server = Server::create();
    $server->router->get("/", fn () => "hello world");
    $server->start();
}
