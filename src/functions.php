<?php
use function CatPaw\Core\anyError;
use CatPaw\Core\Unsafe;
use const CatPaw\Web\APPLICATION_JSON;
use CatPaw\Web\Attributes\IgnoreOpenApi;
use CatPaw\Web\Interfaces\OpenApiInterface;
use CatPaw\Web\Interfaces\RouterInterface;
use function CatPaw\Web\success;

/**
 *
 * @param  RouterInterface $router
 * @return Unsafe<void>
 */
function registerSwaggerUi(RouterInterface $router):Unsafe {
    return anyError(function() use ($router) {
        $router->get(
            path: "/openapi",
            function:
            #[IgnoreOpenApi]
            fn (OpenApiInterface $openApi)
                => success($openApi->data())->as(APPLICATION_JSON)
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
