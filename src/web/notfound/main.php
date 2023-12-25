<?php

use CatPaw\Web\Server;

function main() {
    $server = Server::create();
    $server->router->get("@404", fn () => "Sorry, couldn't find the resource!");
    $server->start();
}