<?php

namespace {
    use function Amp\async;
    use function Amp\delay;
    use function CatPaw\Store\readable;

    function main() {
        $time = readable(time(), function($set) {
            ticktock(5, fn () => $set(time()));
        });
        $time->subscribe(fn ($time) => print("the time is $time\n"));
    }

    function ticktock(int $iterations, callable $callback) {
        async(function() use (&$iterations, $callback) {
            delay(1);
            $callback();
            $iterations--;
            if ($iterations > 0) {
                ticktock($iterations, $callback);
            }
        });
    }
}