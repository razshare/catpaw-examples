<?php
use function CatPaw\Core\anyError;
use function CatPaw\Core\asFileName;
use CatPaw\Core\Unsafe;

use const CatPaw\Web\APPLICATION_JSON;
use CatPaw\Web\Attributes\Consumes;
use CatPaw\Web\Attributes\ProducesPage;
use CatPaw\Web\Body;
use function CatPaw\Web\failure;
use CatPaw\Web\Interfaces\RouterInterface;
use CatPaw\Web\Interfaces\ServerInterface;
use const CatPaw\Web\OK;
use CatPaw\Web\Page;
use function CatPaw\Web\success;

class Cat {
    public function __construct(
        public string $name,
    ) {
    }
}

function main(ServerInterface $server, RouterInterface $router): Unsafe {
    return anyError(function() use ($server, $router) {
        $cats = [];

        $get = #[ProducesPage(OK, APPLICATION_JSON, 'On success', Cat::class, new Cat(name:'Kitty'))]
        function(Page $page) use (&$cats) {
            return success($cats)->page($page)->as(APPLICATION_JSON);
        };

        $post = #[Consumes(APPLICATION_JSON, Cat::class, new Cat(name:'Kitty'))]
        function(Body $body) use (&$cats) {
            $cat = $body->asObject()->unwrap($error);
            if ($error) {
                return failure($error);
            }
            $cats[] = $cat;
            return success();
        };

        $router->get('/cats', $get)->try();
        $router->post('/cats', $post)->try();
        registerSwaggerUi($router)->try();

        $server
            ->withStaticsLocation(asFileName(__DIR__, '../../../public'))
            ->start()
            ->try();
    });
}
