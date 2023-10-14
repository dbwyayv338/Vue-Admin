<?php

namespace App\Http\Exceptions;

use InvalidArgumentException;

class InvalidParent extends InvalidArgumentException
{
    public static function create()
    {
        return new static("Invalid Parent");
    }
}
