<?php
use function CatPaw\Core\anyError;
use function CatPaw\Core\asFileName;

use CatPaw\Web\Server;
use function CatPaw\Web\success;

function hello() {
    return success('hello world');
}

function main() {
    return anyError(function() {
        $server = Server::get()->withStaticsLocation(asFileName(__DIR__, '../../../public'));
        $server->router->get('/', hello(...))->try();
        $server->start()->try();
    });
}
