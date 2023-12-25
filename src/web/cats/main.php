<?php

use CatPaw\Traits\create;
use CatPaw\Web\Attributes\Body;
use CatPaw\Web\Attributes\Consumes;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Server;

class Cat {
    use create;
    public string $name = 'Kitty';
}

function main(): void {
    $cats = [];

    $server = Server::create(www:"./public");

    $server->router->get(
        path    : "/cats",
        callback: #[Produces(Cat::class, "application/json", new Cat)]
        function() use (&$cats) {
            return $cats;
        }
    );
    
    $server->router->post(
        path    : "/cats",
        callback: #[Consumes(Cat::class, "application/json", new Cat)]
        function(#[Body] array $cat) use (&$cats) {
            $cats[] = $cat;
        }
    );

    showSwaggerUI($server);
    $server->start();
}
