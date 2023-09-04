<?php

namespace App\Http\Controllers;

/**
 * Division class
 */
class CalcDivide implements CalcI
{
    /**
     * Var to hold the sign
     *
     * @var string
     */
    private $sign = '/';

    /**
     * Function to calculate
     *
     * @param float $input1
     * @param float $input2
     * @return float|integer|null
     */
    public function calculate($input1, $input2)
    {
        if($input2 == 0){
            return null;    // error
        }

        return $input1 / $input2;
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
