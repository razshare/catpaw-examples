<?php

use function Amp\delay;
use CatPaw\RaspberryPI\Services\GPIOService;

function main(
    GPIOService $gpio
) {
    $writer12 = $gpio->createWriter("12");

    $led = false;
    while (true) {
        delay(1);
        $led = !$led;
        $writer12->write($led?'1':'0');
    }
}
