<?php

namespace {
    use CatPaw\Web\Attributes\StartWebServer;
    use CatPaw\Web\Utilities\Route;

    #[StartWebServer]
    function main() {
        Route::get("@404", function() {
            return "Sorry, couldn't find the resource!";
        });
    }
}