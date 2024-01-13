<?php

use function Amp\delay;

use function CatPaw\Core\stop;
use CatPaw\RaspberryPi\Services\GpioService;


function main(GpioService $gpio) {
    $writer12 = $gpio->createWriter("12");

    $led = false;
    while (true) {
        delay(1);
        $led = !$led;
        $writer12->write($led?'1':'0')->try($error) or stop($error);
    }
}
