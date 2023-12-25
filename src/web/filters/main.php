<?php

use Amp\Http\HttpStatus;
use CatPaw\Web\Attributes\Example;
use CatPaw\Web\Attributes\Produces;

use function CatPaw\Web\error;
use const CatPaw\Web\PASS;
use CatPaw\Web\Server;

use const CatPaw\Web\TEXT_PLAIN;

function isGreaterThanZero(int $value) {
    return $value > 0 
    ? PASS 
    : error('Bad request :/', HttpStatus::BAD_REQUEST);
}

#[Produces(className: "int", contentType: TEXT_PLAIN, example: 1)]
function serve(#[Example(0)] int $value) {
    return $value;
}

function main() {
    $server = Server::create(www:'./public');

    $server->router->get(
        path    : '/filters/{value}',
        callback: [ isGreaterThanZero(...), serve(...) ]
    );
    
    showSwaggerUI($server);
    $server->start();
}