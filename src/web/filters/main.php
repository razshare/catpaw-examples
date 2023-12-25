<?php

use Amp\Http\HttpStatus;
use CatPaw\Web\Attributes\Example;
use CatPaw\Web\Attributes\Produces;

use function CatPaw\Web\error;
use const CatPaw\Web\PASS;

use CatPaw\Web\Server;

function isGreaterThanZero(int $value) {
    return $value > 0 
    ? PASS 
    : error("Bad request :/", HttpStatus::BAD_REQUEST);
}

#[Produces("int", "text/plain", 1)]
function serve(
    #[Example(1)]
    int $value
) {
    return $value;
}

function main() {
    $server = Server::create(www:'./public');

    $server->router->get(
        path    : "/{value}",
        callback: [ isGreaterThanZero(...), serve(...) ]
    );
    
    showSwaggerUI($server);
    $server->start();
}