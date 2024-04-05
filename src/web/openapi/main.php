<?php
use function CatPaw\Core\anyError;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Attributes\ProducesError;
use CatPaw\Web\Attributes\Query;
use const CatPaw\Web\BAD_REQUEST;
use function CatPaw\Web\failure;
use const CatPaw\Web\OK;
use CatPaw\Web\Server;
use CatPaw\Web\Services\OpenApiService;
use function CatPaw\Web\success;
use const CatPaw\Web\TEXT_PLAIN;

#[Produces(OK, TEXT_PLAIN, 'On success', 'string')]
#[ProducesError(BAD_REQUEST, TEXT_PLAIN, 'when query string `name` is not specified.')]
function plain(#[Query] ?string $name) {
    if (!$name) {
        return failure("Sorry, query string 'name' is required.", BAD_REQUEST);
    }

    return success("hello $name.");
}


function main(OpenApiService $oa) {
    return anyError(function() use ($oa) {
        $oa->setTitle('My Title');
        $oa->setVersion('0.0.1');

        $server = Server::create(www: './public')->try($error)
        or yield $error;

        $server->router->get('/plain', plain(...))->try($error)
        or yield $error;

        showSwaggerUI($server)->try($error)
        or yield $error;

        $server->start()->try($error)
        or yield $error;
    });
}
