<?php

use function CatPaw\Core\stop;

use CatPaw\Web\Server;

function main() {
    $server = Server::create()->try($error)                        or stop($error);
    $server->router->get('/', fn () => 'hello world')->try($error) or stop($error);
    $server->start()->await()->try($error)                         or stop($error);
}
