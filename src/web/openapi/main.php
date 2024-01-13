<?php

use Amp\Http\HttpStatus;
use function CatPaw\Core\stop;
use CatPaw\Web\Attributes\Produces;

use CatPaw\Web\Attributes\Query;
use function CatPaw\Web\failure;
use CatPaw\Web\Server;

use CatPaw\Web\Services\OpenApiService;
use function CatPaw\Web\success;

use const CatPaw\Web\TEXT_PLAIN;

#[Produces('string', TEXT_PLAIN)]
function plain(#[Query("name")] ?string $name) {
    if (!$name) {
        return failure("Sorry, query string 'name' is required.", HttpStatus::BAD_REQUEST);
    }

    return success("hello $name.");
}


function main(OpenApiService $oa) {
    $oa->setTitle('My Title');
    $oa->setVersion('0.0.1');

    $server = Server::create(www: './public')->try($error)  or stop($error);
    $server->router->get('/plain', plain(...))->try($error) or stop($error);
    showSwaggerUI($server)->try($error)                     or stop($error);
    $server->start()->await()->try($error)                  or stop($error);
}
