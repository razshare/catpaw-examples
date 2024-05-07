<?php
use function CatPaw\Core\anyError;
use CatPaw\Core\Unsafe;
use const CatPaw\Web\APPLICATION_JSON;
use CatPaw\Web\Attributes\IgnoreOpenApi;
use CatPaw\Web\Server;
use CatPaw\Web\Services\OpenApiService;
use function CatPaw\Web\success;

/**
 *
 * @param  Server       $server
 * @return Unsafe<void>
 */
function showSwaggerUI(Server $server):Unsafe {
    return anyError(function() use ($server) {
        $server->router->get(
            path: "/openapi",
            function:
            #[IgnoreOpenApi]
            fn (OpenApiService $oa)
                => success($oa->getData())->as(APPLICATION_JSON)
        )->try();

        echo <<<TEXT

                   ==========================================
                  |                                          |
                  |    Your SwaggerUI is ready!              |
                  |                                          |
                  |    Visit it at http://127.0.0.1:8080/    |
                  |                                          |
                  |    There you can test your api.          |
                  |                                          |
                   ==========================================


            TEXT;
    });
}
