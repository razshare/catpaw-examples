<?php

use Amp\Http\HttpStatus;
use Amp\Http\Server\Response;
use CatPaw\Web\Attributes\Param;
use const CatPaw\Web\PASS;
use CatPaw\Web\Server;

function main() {
    $filter1 = fn (#[Param] int $value) 
                    => $value > 0 
                        ? PASS 
                        : new Response(HttpStatus::BAD_REQUEST, [], "Bad request :/");

    $server = Server::create();
    $server->router->get(
        path    : "/{value}",
        callback: [ $filter1, fn (#[Param] int $value) => $value ]
    );

    $server->start();
}