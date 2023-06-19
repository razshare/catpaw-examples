<?php

use CatPaw\Web\Attributes\Body;
use CatPaw\Web\Attributes\Consumes;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Server;

function main(): void {
    $cats = [];

    $server = Server::create();

    $server->router->get(
        path    : "/cats",
        callback: #[Produces("application/json")]
        function() use (&$cats) {
            return $cats;
        }
    );

    $server->router->post(
        path    : "/cats",
        callback: #[Consumes("application/json")]
        function(#[Body] array $cat) use (&$cats) {
            $cats[] = $cat;
        }
    );

    $server->start();
}
