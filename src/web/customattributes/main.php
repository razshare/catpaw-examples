<?php
use Amp\Http\Server\Request;
use function CatPaw\Core\anyError;
use function CatPaw\Core\asFileName;
use function CatPaw\Core\ok;
use CatPaw\Core\Traits\CoreAttributeDefinition;
use CatPaw\Core\Unsafe;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Interfaces\OnResponse;
use CatPaw\Web\Interfaces\ResponseModifier;
use const CatPaw\Web\OK;
use CatPaw\Web\Server;
use function CatPaw\Web\success;
use CatPaw\Web\SuccessResponseModifier;
use const CatPaw\Web\TEXT_PLAIN;

#[Attribute]
class SecretBlazingCat implements OnResponse {
    use CoreAttributeDefinition;

    public function onResponse(Request $request, ResponseModifier $modifier): Unsafe {
        if (!($modifier instanceof SuccessResponseModifier)) {
            return ok();
        }

        if ("Here's a red cat" === $modifier->getData()) {
            $data = <<<TEXT
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
            $modifier->setData($data);
        }
        return ok();
    }
}

#[SecretBlazingCat]
#[Produces(OK, TEXT_PLAIN, 'On success', 'string')]
function showcaseRandomCat() {
    static $cats = [
        'a red and white cat',
        'a white cat',
        'a red cat',
        'a black cat',
        'a white and black cat',
    ];

    return success("Here's ".$cats[array_rand($cats)]);
}

function main() {
    return anyError(function() {
        $server = Server::get()->withStaticsLocation(asFileName(__DIR__, '../../../public'));
        $server->router->get('/showcase-random-cat', showcaseRandomCat(...))->try();
        showSwaggerUI($server)->try();
        $server->start()->try();
    });
}
