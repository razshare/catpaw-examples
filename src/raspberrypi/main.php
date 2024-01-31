<?php
use function Amp\delay;
use function CatPaw\Core\anyError;
use CatPaw\RaspberryPi\Services\GpioService;

function main(GpioService $gpio) {
    return anyError(function() use ($gpio) {
        $writer12 = $gpio->createWriter("12");
    
        $led = false;
        while (true) {
            delay(1);
            $led = !$led;
            $writer12->write($led?'1':'0')->try($error)
            or yield $error;
        }
    });
}
