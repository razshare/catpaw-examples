<?php

use function Amp\delay;
use CatPaw\RaspberryPI\Services\GPIOService;

function main(
    GPIOService $gpio
) {
    $writer12 = $gpio->createWriter("12");

    $led = false;
    while (true) {
        yield delay(1000);
        $led = !$led;
        yield $writer12->write($led?'1':'0');
    }
}
