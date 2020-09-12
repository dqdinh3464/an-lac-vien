<?php

use App\Home;

if (!function_exists('getOwner')) {
    function getOwner($id)
    {
        $owner = \App\Owner::where('id', $id)->first();
        return $owner;
    }
}

if (!function_exists('getHomeType')) {
    function getHomeType($id)
    {
        $type = \App\HomeType::where('id', $id)->first();

        return $type;
    }
}


if (!function_exists('getRow')) {
    function getRow($y)
    {
        $rowHomes = Home::where('y', $y)->get()->sortBy('x')->toArray();

        return $rowHomes;
    }
}


