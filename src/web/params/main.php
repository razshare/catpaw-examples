<?php

use CatPaw\Web\Attributes\Param;
use CatPaw\Web\Server;

function main(): void {
    $server = Server::create();

    $server->router->get("/account/{username}", function(
        #[Param] string $username
    ) {
        return "hello $username.";
    });

    $server->router->get("/account/{username}/active/{active}", function(
        #[Param] string $username,
        #[Param] bool $active
    ) {
        if ($active) {
            return "Account $username has been activated.";
        }
        return "Account $username has been deactivated.";
    });

    $server->router->get("/account/{username}/{page}", function(
        #[Param] string $username,
        #[Param] string $page
    ) {
        return "hello $username, you are looking at page $page.";
    });

    $server->start();
}