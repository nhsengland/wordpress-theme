<?php

set_exception_handler(
    function (Exception $e) {
        echo $e->getMessage();
    }
);

\Eloquent\Asplode\Asplode::install();

return function (\Evenement\EventEmitterInterface $emitter) {
    $dot = new \Peridot\Reporter\Dot\DotReporterPlugin($emitter);
};
