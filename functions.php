<?php

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function urlIs($value)
{
    if (!isset($_SERVER['REQUEST_URI'])) {
        return false;
    }
    return $_SERVER['REQUEST_URI'] === $value;
}
