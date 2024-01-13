<?php

use Amp\Http\Server\Request;
use function CatPaw\Core\ok;

use function CatPaw\Core\stop;
use CatPaw\Core\Traits\CoreAttributeDefinition;

use CatPaw\Core\Unsafe;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Interfaces\OnResult;
use CatPaw\Web\Server;
use const CatPaw\Web\TEXT_PLAIN;

#[Attribute]
class SecretBlazingCat implements OnResult {
    use CoreAttributeDefinition;

    public function onResult(Request $request, mixed &$result): Unsafe {
        if ("Here's a red cat" === $result) {
            $result = <<<TEXT
                I was gonna say `Here's a red cat`, but never mind that, here's a BLAZING red cat!!

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
        return ok();
    }
}

#[SecretBlazingCat]
#[Produces('string', TEXT_PLAIN)]
function showcaseRandomCat() {
    static $cats = [
        'a red and white cat',
        'a white cat',
        'a red cat',
        'a black cat',
        'a white and black cat',
    ];

    return "Here's ".$cats[array_rand($cats)];
}

function main() {
    $server = Server::create(www:'./public')->try($error)                             or stop($error);
    $server->router->get('/showcase-random-cat', showcaseRandomCat(...))->try($error) or stop($error);
    showSwaggerUI($server)->try($error)                                               or stop($error);
    $server->start()->await()->try($error)                                            or stop($error);
}
