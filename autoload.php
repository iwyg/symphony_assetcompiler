<?php

$classmap = require __DIR__.'/classmap.php';
spl_autoload_register(function ($class) use ($classmap) {
    if (isset($classmap[$class])) {
        include $classmap[$class];
    }
});
