<?php

use Amp\Http\Server\Request;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Interfaces\RouteAttributeInterface;
use CatPaw\Web\Server;
use CatPaw\Web\Traits\CoreRouteAttributeDefinition;

#[Attribute]
class SecretBlazingCat implements RouteAttributeInterface {
    use CoreRouteAttributeDefinition;

    public function onResult(Request $request, mixed &$result): void {
        if ("Here's a red cat" === $result) {
            $result = <<<TEXT
                I was gonna say `Here's a red cat`, but nevermind that, here's a BLAZING red cat!!

                                .,  ,.                       ,.
                                ,((')/))).                    (()
                            '(.(()( )")),                ((()) 
                            "___/,  "/)))/).'               .))
                            '.-.   "(()(()()/^             ( (
                >> ROAR << ' _)   /)()())(()'______.---._.' )
                            '.   _  (()(()))..            ,'
                                (() \  ()) ())(             )
                                    ((                .     /_
                                    /       \,     .-(     (_ )
                                .'   \/    )___.'   \      ) 
                                /    \-    /        _/'.-'  / 
                                (,(,.'     ))       (_ /    /
                                    (,(,(,_)mrf      (,(,(,_)
                TEXT;
        }
    }
}

#[SecretBlazingCat]
#[Produces("string", "text/plain")]
function showcaseRandomCat() {
    static $cats = [
        "a red and white cat",
        "a white cat",
        "a red cat",
        "a black cat",
        "a white and black cat",
    ];

    return "Here's ".$cats[array_rand($cats)];
}

function main() {
    $server = Server::create(www:'./public');
    $server->router->get(
        path    : "/showcase-random-cat",
        callback: showcaseRandomCat(...)
    );
    showSwaggerUI($server);
    $server->start();
}