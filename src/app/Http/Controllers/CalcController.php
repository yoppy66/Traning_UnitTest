<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * The main Calculator controller
 */
class CalcController extends Controller
{
    /**
     * Function for page index /
     *
     * @return string
     */
    public function index()
    {
//        phpinfo();
//        exit();
        return view('calculator');
    }

    /**
     * Function for calulator processing page /process
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function calculate(Request $request)
    {
        $rules = [
            'val1' => 'required|numeric',
            'val2' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('operator') === 'divide' && $value == 0) {
                        $fail($request->input('val1') . ' / ' . $value . ' = Division by zero.');
                    }
                },
            ],
            'operator' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) {
                    $class_name = 'App\\Http\\Controllers\\Calc'.ucfirst($value);
                    if (!class_exists($class_name)) {
                        $fail('That operator is invalid.');
                    }
                },
            ],
        ];
        $messages = [
            'val1.required' => 'Value 1 cannot be blank',
            'val1.numeric' => 'Value 1 must be a number',
            'val2.required' => 'Value 2 cannot be blank',
            'val2.numeric' => 'Value 2 must be a number',
            'operator.required' => 'Please select an operator',
            'operator.max' => 'That operator is invalid.',
        ];
        $this->validate($request, $rules, $messages);

        $val1 = $request->input('val1');
        $val2 = $request->input('val2');
        $operator = basename($request->input('operator'));
        $calc = CalcFactory::build($operator);
        $sum = $calc->calculate($val1,$val2);
        $sign = $calc->getSign();

        return redirect()->route('calculator.home')->with(
            [
                'val1' => $val1,
                'val2' => $val2,
                'sum' => $sum,
                'sign' => $sign,
                'operator' => $operator
            ]
        );
    }
}
