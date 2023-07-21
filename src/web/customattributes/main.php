<?php

use CatPaw\Interfaces\AttributeInterface;
use CatPaw\Traits\CoreAttributeDefinition;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\RouteHandlerContext;
use CatPaw\Web\Server;

#[Attribute]
class CustomHttpParameterAttribute implements AttributeInterface {
    use CoreAttributeDefinition;

    public function __construct(private string $value) {
        echo "hello world\n";
    }

    public function onParameterMount(ReflectionParameter $reflection, mixed &$value, mixed $context) {
        $value = "$this->value $value";
    }
}

#[Attribute]
class CustomRouteAttribute implements AttributeInterface {
    use CoreAttributeDefinition;

    public function onRouteMount(ReflectionFunction $reflection, Closure &$value, mixed $context) {
        /** @var RouteHandlerContext $context */
        echo "Detecting a custom attribute on $context->method $context->path!\n";
    }
}

function main() {
    $server = Server::create();
    $server->router->get(
        path    : "/",
        callback: 
        #[Produces("text/html")]
        #[CustomRouteAttribute]
        function(#[CustomHttpParameterAttribute("hello")] string $name = 'world') {
            return $name;
        }
    );
    $server->start();
}