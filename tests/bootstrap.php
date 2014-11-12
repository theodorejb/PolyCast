<?php

require_once "vendor/autoload.php";

// conditionally define PHP_INT_MIN since PHP 5.x doesn't
// include it and it's necessary for validating int/float casts.
if (!defined("PHP_INT_MIN")) {
    define("PHP_INT_MIN", ~PHP_INT_MAX);
}
