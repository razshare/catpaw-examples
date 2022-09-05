<?php

namespace {
    use CatPaw\Web\Attributes\Param;
    use CatPaw\Web\Attributes\StartWebServer;
    use CatPaw\Web\Utilities\Route;

    #[StartWebServer]
    function main(): void {
        Route::get("/account/{username}", function(
            #[Param] string $username
        ) {
            return "hello $username.";
        });

        Route::get("/account/{username}/active/{active}", function(
            #[Param] string $username,
            #[Param] bool $active
        ) {
            if ($active) {
                return "Account $username has been activated.";
            }
            return "Account $username has been deactivated.";
        });

        Route::get("/account/{username}/{page}", function(
            #[Param] string $username,
            #[Param] string $page
        ) {
            return "hello $username, you are looking at page $page.";
        });
    }
}
