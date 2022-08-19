<?php

namespace {


    use CatPaw\Web\Attributes\Consumes;
    use CatPaw\Web\Attributes\Produces;
    use CatPaw\Web\Attributes\RequestBody;
    use CatPaw\Web\Attributes\StartWebServer;
    use CatPaw\Web\Utilities\Route;

    #[StartWebServer]
    function main(): void {
        $cats = [];

        Route::get(
            path    : "/cats",
            callback: #[Produces("application/json")]
            function() use (&$cats) {
                return $cats;
            }
        );

        Route::post(
            path    : "/cats",
            callback: #[Consumes("application/json")]
            function(
                #[RequestBody]
                array $cat
            ) use (&$cats) {
                $cats[] = $cat;
            }
        );
    }
}
