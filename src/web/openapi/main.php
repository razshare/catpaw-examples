<?php
use function CatPaw\Core\stop;
use const CatPaw\Web\__BAD_REQUEST;
use const CatPaw\Web\__OK;
use const CatPaw\Web\__TEXT_PLAIN;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Attributes\ProducesError;
use CatPaw\Web\Attributes\Query;
use function CatPaw\Web\failure;
use CatPaw\Web\Server;
use CatPaw\Web\Services\OpenApiService;
use function CatPaw\Web\success;

#[Produces(__OK, __TEXT_PLAIN, 'on success', 'string')]
#[ProducesError(__BAD_REQUEST, __TEXT_PLAIN, 'when query string `name` is not specified.')]
function plain(#[Query] ?string $name) {
    if (!$name) {
        return failure("Sorry, query string 'name' is required.", __BAD_REQUEST);
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
