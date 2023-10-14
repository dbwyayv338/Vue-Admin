<?php

namespace App\Http\Exceptions;

use InvalidArgumentException;

class MachineNameInvalidArgument extends InvalidArgumentException
{
    public static function create()
    {
        return new static('The machine name must only contain lowercase letters, numbers, dashes and underscores.');
    }
}
