<?php

use const CatPaw\Web\APPLICATION_JSON;
use CatPaw\Web\Attributes\Body;
use CatPaw\Web\Attributes\Consumes;
use CatPaw\Web\Attributes\ProducesPage;
use CatPaw\Web\Server;

class Cat {
    public function __construct(
        public string $name,
    ) {
    }
}

function main(): void {
    $cats = [];

    $server = Server::create( www:'./public' );

    $server->router->get(
        path    : '/cats',
        callback:
        #[ProducesPage(Cat::class, APPLICATION_JSON, new Cat( name: 'Kitty' ))]
        function() use (&$cats) {
            return $cats;
        }
    );
    
    $server->router->post(
        path    : '/cats',
        callback:
        #[Consumes(Cat::class, APPLICATION_JSON, new Cat( name: 'Kitty' ))]
        function(#[Body] $cat) use (&$cats) {
            $cats[] = $cat;
        }
    );

    showSwaggerUI($server);
    $server->start();
}
