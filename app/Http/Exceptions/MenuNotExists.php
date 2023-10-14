<?php

namespace App\Http\Exceptions;

use InvalidArgumentException;

class MenuNotExists extends InvalidArgumentException
{
    public static function create(string $machineName)
    {
        return new static("A `{$machineName}` menu not exists.");
    }
}
