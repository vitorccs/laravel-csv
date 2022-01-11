<?php

namespace Vitorccs\LaravelCsv\Exceptions;

class InvalidCellValueException extends \Exception
{
    /**
     * @param string $cell
     */
    public function __construct(string $cell)
    {
        parent::__construct("The cell {$cell} cannot be converted to string");
    }
}
