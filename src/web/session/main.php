<?php
use function CatPaw\Core\anyError;
use function CatPaw\Core\asFileName;

use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Interfaces\RouterInterface;
use CatPaw\Web\Interfaces\ServerInterface;
use CatPaw\Web\Interfaces\SessionInterface;

use const CatPaw\Web\OK;
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

function main(ServerInterface $server, RouterInterface $router) {
    return anyError(function() use ($server, $router) {
        $router->get("/", serve(...))->try();
        $server
            ->withStaticsLocation(asFileName(__DIR__, '../../../public'))
            ->start()
            ->try();
    });
}
