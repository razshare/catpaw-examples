<?php

use Amp\Http\Server\Response;
use Amp\Http\Status;
use Amp\LazyPromise;
use CatPaw\Web\Attributes\{IgnoreOpenAPI, Produces, Query, RequestQuery, StartWebServer};
use CatPaw\Web\Services\OpenAPIService;
use CatPaw\Web\Utilities\Route;


#[StartWebServer]
function main(OpenAPIService $oapi): LazyPromise {
    return new LazyPromise(function() use ($oapi) {
        $oapi->setTitle("My Title");
        $oapi->setVersion("0.0.1");
        
        Route::get(
            path    : "/plain",
            callback: 
            #[Produces("text/plain")] 
            function(#[Query("name")] ?string $name) {
                return !$name
                    ? new Response(Status::BAD_REQUEST, [], "Sorry, query string 'name' is required.")
                    : new Response(Status::OK, [], "hello $name.");
            }
        );

        Route::get(
            path: "/api",
            callback: 
                #[Produces("application/json")]
                #[IgnoreOpenAPI]    // excluding this endpoint from the resulting open api json
                fn() => $oapi->getData()
        );
    });
}