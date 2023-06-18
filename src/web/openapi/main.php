<?php

use Amp\Http\HttpStatus;
use Amp\Http\Server\Response;
use CatPaw\Web\Attributes\IgnoreOpenAPI;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Attributes\Query;
use CatPaw\Web\Server;
use CatPaw\Web\Services\OpenAPIService;
use CatPaw\Web\Utilities\Route;

function main(OpenAPIService $oapi) {
    $oapi->setTitle("My Title");
    $oapi->setVersion("0.0.1");
    
    Route::get(
        path    : "/plain",
        callback: 
        #[Produces("text/plain")] 
        function(#[Query("name")] ?string $name) {
            return !$name
                ? new Response(HttpStatus::BAD_REQUEST, [], "Sorry, query string 'name' is required.")
                : new Response(HttpStatus::OK, [], "hello $name.");
        }
    );

    Route::get(
        path: "/api",
        callback: 
            #[Produces("application/json")]
            #[IgnoreOpenAPI]    // excluding this endpoint from the resulting open api json
            fn () => $oapi->getData()
    );

    Server::create()->create();
}