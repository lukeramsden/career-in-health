<?php

namespace App\Enum;

abstract class IAm
{
    const Employee = 1;
    const Company  = 2;
}

// wait a second why am i doing this when i can just do a published boolean
// TODO: do that^^
abstract class AdvertStatus
{
    const Draft  = 0;
    const Public = 1;
}
