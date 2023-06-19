<?php

use CatPaw\MYSQL\Attributes\Repository;
use CatPaw\MYSQL\Services\DatabaseService;
use CatPaw\Web\Server;

function main(DatabaseService $db) {
    $db->setPool(
        poolName: "main",
        host    : "127.0.0.1",
        user    : "myuser",
        password: "mypassword",
        database: "genericstore"
    );

    $server = Server::create();
    $server->router->get(
        path    : "/",
        callback: fn (#[Repository("account")] callable $updateByLikeEmail):mixed => $updateByLikeEmail(
            ["email" => "new@gmail.com"], //payload
            ["email" => "my@gmail.com"], //lookup
        )
    );

    echo $server->describe();

    Server::create()->start();
}