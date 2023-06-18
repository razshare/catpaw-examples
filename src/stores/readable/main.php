<?php

use function Amp\delay;
use function CatPaw\Store\readable;

function main():void {
    $time = readable(time(), function(callable $set) {
        ticktock(5, function() use ($set) {
            $set(time());
        });
    });
    $time->subscribe(fn ($time) => print("the time is $time\n"));
}

function ticktock(int $iterations, callable $callback) {
    delay(1);
    $callback();
    $iterations--;
    if ($iterations > 0) {
        ticktock($iterations, $callback);
    }
}