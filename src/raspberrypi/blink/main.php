<?php

namespace {
    use function Amp\delay;
    use CatPaw\RaspberryPI\Attributes\GPIO;

    function main(
        #[GPIO("12", "write")] $set12
    ) {
        $led = false;
        while (true) {
            yield delay(1000);
            $led = !$led;
            yield $set12($led);
        }
    }
}