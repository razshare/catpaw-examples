<?php

use function CatPaw\Core\stop;

use const CatPaw\Web\__OK;
use const CatPaw\Web\__TEXT_HTML;

use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Attributes\Session;

use CatPaw\Web\Server;

use function CatPaw\Web\success;

#[Produces(__OK, __TEXT_HTML, 'on success', 'string')]
function serve(#[Session] array &$session) {
    if (!isset($session['created'])) {
        $session['created'] = time();
    }

    $contents = print_r($session, true);

    return success(
        <<<HTML
            this is my session <br /><pre>$contents</pre>
            HTML
    );
}

function main() {
    $server = Server::create()->try($error)            or stop($error);
    $server->router->get("/", serve(...))->try($error) or stop($error);
    $server->start()->await()->try($error)             or stop($error);
}
