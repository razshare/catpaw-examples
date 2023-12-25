<?php

use Amp\Http\HttpStatus;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Attributes\Query;
use function CatPaw\Web\error;
use function CatPaw\Web\ok;

use CatPaw\Web\Server;
use CatPaw\Web\Services\OpenApiService;

#[Produces("text/plain")] 
function plain(#[Query("name")] ?string $name) {
    if (!$name) {
        return error("Sorry, query string 'name' is required.", HttpStatus::BAD_REQUEST);
    }

    return ok("hello $name.");
}


function main(OpenApiService $oa) {
    $oa->setTitle("My Title");
    $oa->setVersion("0.0.1");
    
    $server = Server::create( www: './public' );

    $server->router->get(
        path    : "/plain",
        callback: plain(...)
    );

    showSwaggerUI($server);

    $server->start();
}