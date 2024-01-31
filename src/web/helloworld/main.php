<?php
use function CatPaw\Core\anyError;
use CatPaw\Web\Server;
use function CatPaw\Web\success;

function hello() {
    return success('hello world');
}

function main() {
    return anyError(function() {
        $server = Server::create()->try($error)
        or yield $error;

        $server->router->get('/', hello(...))->try($error)
        or yield $error;

        $server->start()->await()->try($error)
        or yield $error;
    });
}
