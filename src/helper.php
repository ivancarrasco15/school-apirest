<?php

function dd():void {
    foreach(func_get_args() as $arg) {
        echo "<pre>";
        var_dump($arg);
        echo "</pre>";
    }
    die;
}
