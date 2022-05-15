<?php

namespace {
	use CatPaw\Web\Attributes\StartWebServer;
	use CatPaw\Web\Utilities\Route;

	#[StartWebServer]
	function main() {
		Route::get("/", fn() => "hello world");
	}
}
