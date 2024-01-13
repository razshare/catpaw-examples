<?php

use function CatPaw\Core\stop;
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
    $server = Server::create()->try($error)            or stop($error);
    $server->router->get("/", serve(...))->try($error) or stop($error);
    $server->start()->await()->try($error)             or stop($error);
}
