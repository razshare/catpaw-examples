<?php

use CatPaw\Web\Attributes\{Body, Consumes, Produces, StartWebServer};
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
            #[Body]
            array $cat
        ) use (&$cats) {
            $cats[] = $cat;
        }
    );
}
