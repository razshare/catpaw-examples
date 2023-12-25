<?php

use CatPaw\Web\Server;

function main() {
    $server = Server::create();
    $server->setFileServer(fn () => "Sorry, couldn't find the resource!");
    $server->start();
}