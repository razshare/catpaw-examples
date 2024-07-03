<?php
use function CatPaw\Core\anyError;
use function CatPaw\Core\asFileName;

use CatPaw\Web\Interfaces\RouterInterface;
use CatPaw\Web\Interfaces\ServerInterface;
use function CatPaw\Web\success;

function hello() {
    return success('hello world');
}

function main(ServerInterface $server, RouterInterface $router) {
    return anyError(function() use ($server, $router) {
        $router->get('/', hello(...))->try();
        $server->withStaticsLocation(asFileName(__DIR__, '../../../public'))->start()->try();
    });
}
