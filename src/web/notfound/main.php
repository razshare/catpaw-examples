<?php

use CatPaw\Web\Server;

function main() {
    Route::get("@404", function() {
        return "Sorry, couldn't find the resource!";
    });
    Server::create()->create();
}