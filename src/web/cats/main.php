<?php
use function CatPaw\Core\anyError;
use function CatPaw\Core\asFileName;

use CatPaw\Core\Unsafe;

use const CatPaw\Web\APPLICATION_JSON;
use CatPaw\Web\Attributes\Body;
use CatPaw\Web\Attributes\Consumes;
use CatPaw\Web\Attributes\ProducesPage;
use const CatPaw\Web\OK;

use CatPaw\Web\Page;
use CatPaw\Web\Server;
use function CatPaw\Web\success;

class Cat {
    public function __construct(
        public string $name,
    ) {
    }
}

function main(): Unsafe {
    return anyError(function() {
        $cats = [];

        $get = #[ProducesPage(OK, APPLICATION_JSON, 'On success', Cat::class, new Cat(name:'Kitty'))]
        function(Page $page) use (&$cats) {
            return success($cats)->page($page)->as(APPLICATION_JSON);
        };

        $post = #[Consumes(APPLICATION_JSON, Cat::class, new Cat(name:'Kitty'))]
        function(#[Body] $cat) use (&$cats) {
            $cats[] = $cat;
            return success();
        };

        $server = Server::get()->withStaticsLocation(asFileName(__DIR__, '../../../public'));
        $server->router->get('/cats', $get)->try();
        $server->router->post('/cats', $post)->try();
        showSwaggerUI($server)->try();
        $server->start()->try();
    });
}
