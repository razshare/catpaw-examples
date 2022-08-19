<?php

namespace {

    use Amp\LazyPromise;
    use Amp\Promise;
    use CatPaw\Attributes\Interfaces\AttributeInterface;
    use CatPaw\Attributes\Traits\CoreAttributeDefinition;
    use CatPaw\Web\Attributes\Produces;
    use CatPaw\Web\Attributes\StartWebServer;
    use CatPaw\Web\RouteHandlerContext;
    use CatPaw\Web\Utilities\Route;

    #[Attribute]
    class CustomHttpParameterAttribute implements AttributeInterface {
        use CoreAttributeDefinition;

        public function __construct(private string $value) {
            echo "hello world\n";
        }

        public function onParameter(
            ReflectionParameter $reflection,
            mixed &$value,
            mixed $context
        ): Promise {
            return new LazyPromise(function() use (&$value) {
                $value = "$this->value $value";
            });
        }
    }

    #[Attribute]
    class CustomRouteAttribute implements AttributeInterface {
        use CoreAttributeDefinition;

        public function onRouteHandler(
            ReflectionFunction $reflection,
            Closure &$value,
            mixed $context
        ): Promise {
            /** @var RouteHandlerContext $context */
            return new LazyPromise(function() use ($reflection, $context) {
                echo "Detecting a custom attribute on $context->method $context->path!\n";
            });
        }
    }

    #[StartWebServer]
    function main() {
        Route::get(
            path    : "/",
            callback: 
            #[Produces("text/html")]
            #[CustomRouteAttribute]
            function(#[CustomHttpParameterAttribute("hello")] string $name = 'world') {
                return $name;
            }
        );
    }
}