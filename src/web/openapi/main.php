<?php

namespace {

    use Amp\Http\Server\Response;
    use Amp\Http\Status;
    use Amp\LazyPromise;
    use CatPaw\Web\Attributes\IgnoreOpenAPI;
    use CatPaw\Web\Attributes\Produces;
    use CatPaw\Web\Attributes\RequestQuery;
    use CatPaw\Web\Attributes\StartWebServer;
    use CatPaw\Web\Services\OpenAPIService;
    use CatPaw\Web\Utilities\Route;


    #[StartWebServer]
    function main(OpenAPIService $oa): LazyPromise {
        return new LazyPromise(function() use ($oa) {
            $oa->setTitle("My Title");
            $oa->setVersion("0.0.1");
            
            Route::get(
                path    : "/plain",
                callback: 
                #[Produces("text/plain")] 
                function(#[RequestQuery("name")] ?string $name) {
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
                    fn() => $oa->getData()
            );
        });
    }
}
