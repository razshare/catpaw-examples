<?php
use function CatPaw\Core\anyError;
use CatPaw\Core\Unsafe;
use const CatPaw\Web\APPLICATION_JSON;
use CatPaw\Web\Attributes\Body;
use CatPaw\Web\Attributes\Consumes;
use CatPaw\Web\Attributes\ProducesPage;
use const CatPaw\Web\OK;
use CatPaw\Web\Server;

class Cat {
    public function __construct(
        public string $name,
    ) {
    }
}

function main(): Unsafe {
    return anyError(function() {
        $cats = [];

        $get = #[ProducesPage(OK, APPLICATION_JSON, 'on success', Cat::class, new Cat(name:'Kitty'))]
        function() use (&$cats) {
            return $cats;
        };

        $post = #[Consumes(APPLICATION_JSON, Cat::class, new Cat(name:'Kitty'))]
        function(#[Body] $cat) use (&$cats) {
            $cats[] = $cat;
        };

        $server = Server::create(www:'public')->try($error)
        or yield $error;

        $server->router->get('/cats', $get)->try($error)
        or yield $error;

        $server->router->post('/cats', $post)->try($error)
        or yield $error;

        showSwaggerUI($server)->try($error)
        or yield $error;

        $server->start()->await()->try($error)
        or yield $error;
    });
}
