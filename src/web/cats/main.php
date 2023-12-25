<?php

use CatPaw\Web\Attributes\Body;
use CatPaw\Web\Attributes\Consumes;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Server;

function main() {
    /** @var array<string> */
    $cats   = [ "cat1" ];
    $server = Server::create( www:'./public' );
    $server->router->get(
        path    : '/cats',
        callback:
        #[Produces('array')]
        function() use (&$cats) {
            return $cats;
        }
    );

    $server->router->post(
        path    : '/cats',
        callback:
        #[Consumes('string')]
        function(#[Body] array $cat) use (&$cats) {
            $cats[] = $cat;
        }
    );
    showSwaggerUI($server);
    $server->start();
}