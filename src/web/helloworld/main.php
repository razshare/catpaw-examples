<?php

use CatPaw\Web\Server;

function main() {
    Route::get("/", fn () => "hello world");
    Server::create()->create();
}
