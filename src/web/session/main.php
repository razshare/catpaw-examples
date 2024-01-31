<?php
use function CatPaw\Core\anyError;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Attributes\Session;
use const CatPaw\Web\OK;
use CatPaw\Web\Server;
use function CatPaw\Web\success;
use const CatPaw\Web\TEXT_HTML;

#[Produces(OK, TEXT_HTML, 'on success', 'string')]
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
    return anyError(function() {
        $server = Server::create()->try($error)
        or yield $error;

        $server->router->get("/", serve(...))->try($error)
        or yield $error;

        $server->start()->await()->try($error)
        or yield $error;
    });
}
