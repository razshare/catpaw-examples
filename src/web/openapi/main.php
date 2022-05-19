<?php

namespace {

	use Amp\Http\Server\Response;
	use Amp\Http\Status;
	use Amp\LazyPromise;
	use CatPaw\OpenAPI\Attributes\OpenAPIRoute;
	use CatPaw\OpenAPI\Services\OpenAPIService;
	use CatPaw\Web\Attributes\Produces;
	use CatPaw\Web\Attributes\RequestQuery;
	use CatPaw\Web\Attributes\StartWebServer;
	use CatPaw\Web\Utilities\Route;


	#[StartWebServer]
	function main(OpenAPIService $oa): LazyPromise {
	    return new LazyPromise(function() use ($oa) {
	        Route::get(
				path    : "/plain",
				callback: #[OpenAPIRoute([
				    "Success" => [
				        "status" => Status::OK,
				        "response" => "string",
				    ],
				    "The query string 'name' has not been provided" => [
				        "status" => Status::BAD_REQUEST,
				        "response" => "string",
				    ],
				])]
				#[Produces("text/plain")]
				function(
					#[RequestQuery("name")] ?string $name
				) {
				    return !$name
						? new Response(Status::BAD_REQUEST, [], "Sorry, query string 'name' is required.")
						: new Response(Status::OK, [], "hello $name.");
				}
			);

	        $oa->setInfo(
				title       : "title",
				version     : "0.0.1",
				emailContact: "tangent.jotey@gmail.com",
			);
	        yield $oa->dump("public/openapi.json");
	        echo Route::describe();
	    });
	}
}
