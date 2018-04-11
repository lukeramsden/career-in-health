<?php

if(! function_exists('ajax'))
{
    function ajax()
    {
        return app('request')->ajax();
    }
}