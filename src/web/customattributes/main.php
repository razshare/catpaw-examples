<?php

namespace {

    use Amp\LazyPromise;
    use Amp\Promise;
    use CatPaw\Attributes\Interfaces\AttributeInterface;
    use CatPaw\Attributes\Traits\CoreAttributeDefinition;
    use CatPaw\Web\Attributes\Produces;
    use CatPaw\Web\Attributes\StartWebServer;
    use CatPaw\Web\Utilities\Route;

    #[Attribute]
    class CustomHttpParameterAttribute implements AttributeInterface {
        use CoreAttributeDefinition;

        public function __construct(private string $value) {
            echo "hello world\n";
        }

        public function onParameter(ReflectionParameter $reflection, mixed &$value, mixed $http): Promise {
            return new LazyPromise(function() use (
                $reflection,
                &$value,
                $http
            ) {
                $value = "$this->value $value";
            });
        }
    }

    #[Attribute]
    class CustomRouteAttribute implements AttributeInterface {
        use CoreAttributeDefinition;

        public function onRouteHandler(ReflectionFunction $reflection, Closure &$value, mixed $route): Promise {
            return new LazyPromise(function() use ($reflection, $route) {
                echo "Detecting a custom attribute on $route->method $route->path!\n";
            });
        }
    }

    #[StartWebServer]
    function main() {
        Route::get(
            path    : "/",
            callback: #[Produces("text/html")]
            #[CustomRouteAttribute]
            function(
                #[CustomHttpParameterAttribute("hello")] string $name = 'world',
            ) {
                return $name;
            }
        );
    }
}