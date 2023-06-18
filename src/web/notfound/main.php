<?php

use CatPaw\Web\Server;
use CatPaw\Web\Utilities\Route;

function main() {
    Route::get("@404", function() {
        return "Sorry, couldn't find the resource!";
    });
    Server::create()->create();
}