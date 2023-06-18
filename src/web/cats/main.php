<?php

use CatPaw\Web\Attributes\Body;
use CatPaw\Web\Attributes\Consumes;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Server;
use CatPaw\Web\Utilities\Route;


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
        function(#[Body] array $cat) use (&$cats) {
            $cats[] = $cat;
        }
    );

    Server::create()->create();
}
