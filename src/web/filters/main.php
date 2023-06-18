<?php

use Amp\Http\HttpStatus;
use Amp\Http\Server\Response;
use CatPaw\Web\Attributes\Filter;
use CatPaw\Web\Attributes\Param;
use CatPaw\Web\Server;
use CatPaw\Web\Utilities\Route;

function main() {
    $filter1 = fn (#[Param] int $value) 
                    => $value > 0 
                        ? Filter::PASS 
                        : new Response(HttpStatus::BAD_REQUEST, [], "Bad request :/");

    Route::get(
        path    : "/{value}",
        callback: [ $filter1, fn (#[Param] int $value) => $value ]
    );

    Server::create()->create();
}