<?php

use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Attributes\Session;
use CatPaw\Web\Server;

use const CatPaw\Web\TEXT_HTML;

#[Produces('string', TEXT_HTML)]
function serve(#[Session] array &$session) {
    if (!isset($session['created'])) {
        $session['created'] = time();
    }

    $contents = print_r($session, true);

    return <<<HTML
            this is my session <br /><pre>$contents</pre>
        HTML;
}

function main() {
    $server = Server::create();
    $server->router->get("/", serve(...));
    $server->start();
}