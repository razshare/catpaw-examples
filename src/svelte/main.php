<?php

namespace {

	use CatPaw\Web\Attribute\StartWebServer;
	use CatPaw\Web\Utility\Route;

	#[StartWebServer]
	function main() {
		echo "Server started";

		Route::get("/", function(

		) {
			return "hello world";
		});
	}
}