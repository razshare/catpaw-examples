<?php
use function CatPaw\Core\anyError;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Interfaces\SessionInterface;

use const CatPaw\Web\OK;
use CatPaw\Web\Server;
use function CatPaw\Web\success;
use const CatPaw\Web\TEXT_HTML;

#[Produces(OK, TEXT_HTML, 'On success', 'string')]
function serve(SessionInterface $session) {
    $created = $session->ref('created', time());

    return success(
        <<<HTML
            This session was created at <span style="color:red">$created</span>
            HTML
    )->as(TEXT_HTML);
}

function main() {
    return anyError(function() {
        $server = Server::create()->try($error)
        or yield $error;

        $server->router->get("/", serve(...))->try($error)
        or yield $error;

        $server->start()->try($error)
        or yield $error;
    });
}
