<?php

use function CatPaw\Core\stop;
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

    $server = Server::create(www:'public')->try($error) or stop($error);

    $server->router->get(
        path    : '/cats',
        function:
        #[ProducesPage(Cat::class, APPLICATION_JSON, new Cat(name:'Kitty'))]
        function() use (&$cats) {
            return $cats;
        }
    )->try($error) or stop($error);

    $server->router->post(
        path    : '/cats',
        function:
        #[Consumes(Cat::class, APPLICATION_JSON, new Cat(name:'Kitty'))]
        function(#[Body] $cat) use (&$cats) {
            $cats[] = $cat;
        }
    )->try($error)                         or stop($error);
    showSwaggerUI($server)->try($error)    or stop($error);
    $server->start()->await()->try($error) or stop($error);
}
