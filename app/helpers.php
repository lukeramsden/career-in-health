<?php

function isActive($path)
{
    if (request()->is($path)) {
        return true;
    }

    return false;
}