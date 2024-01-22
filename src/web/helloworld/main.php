<?php

use function CatPaw\Core\stop;
use CatPaw\Web\Server;

use function CatPaw\Web\success;

function hello() {
    return success('hello world');
}

function main() {
    $server = Server::create()->try($error)            or stop($error);
    $server->router->get('/', hello(...))->try($error) or stop($error);
    $server->start()->await()->try($error)             or stop($error);
}
