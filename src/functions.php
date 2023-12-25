<?php
use CatPaw\Web\Attributes\IgnoreOpenAPI;
use CatPaw\Web\Services\OpenApiService;

function showSwaggerUI($server) {
    $server->router->get(
        path: "/openapi",
        callback:
        #[IgnoreOpenAPI]
        fn (OpenApiService $oa) => $oa->getData()
    );

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
}