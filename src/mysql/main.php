<?php

namespace {

	use CatPaw\MYSQL\Attributes\Repository;
	use CatPaw\MYSQL\Services\DatabaseService;
	use CatPaw\Web\Attributes\StartWebServer;
	use CatPaw\Web\Utilities\Route;

	#[StartWebServer]
	function main(
		DatabaseService $db
	) {
	    $db->setPool(
			poolName: "main",
			host    : "127.0.0.1",
			user    : "myuser",
			password: "mypassword",
			database: "genericstore"
		);

	    Route::get(
			path    : "/",
			callback: fn(#[Repository("account")] $updateByLikeEmail) => $updateByLikeEmail(
				["email" => "new@gmail.com"], //payload
				["email" => "my@gmail.com"],//lookup
			)
		);

	    echo Route::describe();
	}
}