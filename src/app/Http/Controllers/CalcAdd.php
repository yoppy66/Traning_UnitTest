<?php

namespace App\Http\Controllers;

/**
 * Addition class
 */
class CalcAdd implements CalcI
{
    /**
     * Var to hold the sign
     *
     * @var string
     */
    private $sign = '+';

    /**
     * Function to calculate
     *
     * @param float $input1
     * @param float $input2
     * @return float|integer
     */
    public function calculate($input1, $input2)
    {
        return $input1 + $input2;
    }

    /**
     * Function to get the sign
     *
     * @return string
     */
    public function getSign()
    {
        return $this->sign;
    }
}
