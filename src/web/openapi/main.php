<?php
use function CatPaw\Core\anyError;
use function CatPaw\Core\asFileName;

use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Attributes\ProducesError;
use CatPaw\Web\Attributes\Query;
use const CatPaw\Web\BAD_REQUEST;
use function CatPaw\Web\failure;

use CatPaw\Web\Interfaces\OpenApiInterface;
use CatPaw\Web\Interfaces\RouterInterface;
use CatPaw\Web\Interfaces\ServerInterface;
use const CatPaw\Web\OK;
use function CatPaw\Web\success;
use const CatPaw\Web\TEXT_PLAIN;

#[Produces(OK, TEXT_PLAIN, 'On success', 'string')]
#[ProducesError(BAD_REQUEST, TEXT_PLAIN, 'when query string `name` is not specified.')]
function plain(#[Query] string $name = '') {
    if (!$name) {
        return failure("Sorry, query string 'name' is required.", BAD_REQUEST);
    }

    return success("hello $name.");
}


function main(ServerInterface $server, RouterInterface $router, OpenApiInterface $openApi) {
    return anyError(function() use ($server, $router, $openApi) {
        $openApi->setTitle('My Title');
        $openApi->setVersion('0.0.1');
        
        $router->get('/plain', plain(...))->try();
        registerSwaggerUi($router)->try();

        $server
            ->withStaticsLocation(asFileName(__DIR__, '../../../public'))
            ->start()
            ->try();
    });
}
