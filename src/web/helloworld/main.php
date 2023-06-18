<?php

use CatPaw\Web\Server;
use CatPaw\Web\Utilities\Route;

function main() {
    Route::get("/", fn () => "hello world");
    Server::create()->create();
}
