<?php
use function CatPaw\Core\stop;
use const CatPaw\Web\__APPLICATION_JSON;
use const CatPaw\Web\__OK;
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
        #[ProducesPage(__OK, __APPLICATION_JSON, 'on success', Cat::class, new Cat(name:'Kitty'))]
        function() use (&$cats) {
            return $cats;
        }
    )->try($error) or stop($error);

    $server->router->post(
        path    : '/cats',
        function:
        #[Consumes(__APPLICATION_JSON, Cat::class, new Cat(name:'Kitty'))]
        function(#[Body] $cat) use (&$cats) {
            $cats[] = $cat;
        }
    )->try($error)                         or stop($error);
    showSwaggerUI($server)->try($error)    or stop($error);
    $server->start()->await()->try($error) or stop($error);
}
