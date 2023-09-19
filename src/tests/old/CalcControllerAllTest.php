<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalcControllerAllTest extends TestCase
{
    /**
     * rootのテスト
     */
    public function test_root_returns_200_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * データプロバイダ 四則演算計算パターン
     * test_calc_variety_formulas() の計算パターンに使用
     *
     * [0] = $operator : add, multiply, subtract, divide
     * [1] = $val1 :
     * [2] = $val2 :
     * [3] = $result : 計算結果
     *
     * @return array
     */
    public static function dataProvider_CalcPattern(): array
    {
        return [
            ['add', 3, 2, 5],
            ['add', -5, 2, -3],
            ['add', -8, -4, -12],
            ['multiply', 4, 7, 28],
            ['multiply', 9, -5, -45],
            ['multiply', -6, -3, 18],
            ['subtract', 9, 3, 6],
            ['subtract', 4, -1, 5],
            ['subtract', -7, -2, -5],
            ['divide', 8, 2, 4],
            ['divide', 4, -8, -0.5],
            ['divide', -6, -3, 2],
        ];
    }

    /**
     * 四則演算をいろんなパターンで行う
     *
     * @dataProvider dataProvider_CalcPattern
     */
    public function test_calc_variety_formulas($operator, $val1, $val2, $result): void
    {
        $param =[
            'val1' => $val1,
            'val2' => $val2,
            'operator' => $operator
        ];
        $response = $this->get('/process?' . http_build_query($param));

        // バリデーションエラーが発生しなかったことを確認
        $response->assertSessionDoesntHaveErrors();

        // 正常にリダイレクトされることを確認
        $response->assertStatus(302);

        // リダイレクト先の確認
        $response->assertRedirect(route('calculator.home'));

        // 計算の結果が$resultであることを確認
        $this->assertEquals($result, session('sum'));
    }


    // ************** 以下はエラーになることを確認するテスト *****************

    /**
     * データプロバイダ operatorリスト
     *
     * @return array
     */
    public static function dataProvider_OperatorList(): array
    {
        return [['add'], ['multiply'], ['subtract'], ['divide']];
    }

    /**
     * 入力エラーになるテスト val1 required
     * 四則演算全てチェック
     *
     * @dataProvider dataProvider_OperatorList
     */
    public function test_input_val1_required_error($operator): void
    {
        $param =[
            'val1' => null,
            'val2' => 2,
            'operator' => $operator
        ];
        $response = $this->get('/process?' . http_build_query($param));

        // エラーが出ても処理が落ちずにリダイレクトされることを確認
        $response->assertStatus(302);

        // リダイレクト先の確認
        $response->assertRedirect(route('calculator.home'));

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // val1の指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['val1']);
        $response->assertInvalid(['val1']);

        // エラーメッセージの検証
        $this->assertEquals(
            'Value 1 cannot be blank',
            $response->getSession()->get('errors')->get('val1')[0]
        );
    }

    /**
     * 入力エラーになるテスト val2 required
     * 四則演算全てチェック
     *
     * @dataProvider dataProvider_OperatorList
     */
    public function test_input_val2_required_error($operator): void
    {
        $param =[
            'val1' => 2,
            'val2' => null,
            'operator' => $operator
        ];
        $response = $this->get('/process?' . http_build_query($param));

        // エラーが出ても処理が落ちずにリダイレクトされることを確認
        $response->assertStatus(302);

        // リダイレクト先の確認
        $response->assertRedirect(route('calculator.home'));

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // val1の指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['val2']);
        $response->assertInvalid(['val2']);

        // エラーメッセージの検証
        $this->assertEquals(
            'Value 2 cannot be blank',
            $response->getSession()->get('errors')->get('val2')[0]
        );
    }

    /**
     * 入力エラーになるテスト val1 numeric
     * 四則演算全てチェック
     *
     * @dataProvider dataProvider_OperatorList
     */
    public function test_input_val1_numeric_error($operator): void
    {
        $param =[
            'val1' => 'a',
            'val2' => 2,
            'operator' => $operator
        ];
        $response = $this->get('/process?' . http_build_query($param));

        // エラーが出ても処理が落ちずにリダイレクトされることを確認
        $response->assertStatus(302);

        // リダイレクト先の確認
        $response->assertRedirect(route('calculator.home'));

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // val1の指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['val1']);
        $response->assertInvalid(['val1']);

        // エラーメッセージの検証
        $this->assertEquals(
            'Value 1 must be a number',
            $response->getSession()->get('errors')->get('val1')[0]
        );
    }

    /**
     * 入力エラーになるテスト val2 numeric
     * 四則演算全てチェック
     *
     * @dataProvider dataProvider_OperatorList
     */
    public function test_input_val2_numeric_error($operator): void
    {
        $param =[
            'val1' => 3,
            'val2' => 'a',
            'operator' => $operator
        ];
        $response = $this->get('/process?' . http_build_query($param));

        // エラーが出ても処理が落ちずにリダイレクトされることを確認
        $response->assertStatus(302);

        // リダイレクト先の確認
        $response->assertRedirect(route('calculator.home'));

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // val1の指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['val2']);
        $response->assertInvalid(['val2']);

        // エラーメッセージの検証
        $this->assertEquals(
            'Value 2 must be a number',
            $response->getSession()->get('errors')->get('val2')[0]
        );
    }

    /**
     * 0割がエラーになるテスト
     */
    public function test_divid_0_error(): void
    {
        $param =[
            'val1' => 10,
            'val2' => 0,
            'operator' => 'divide'
        ];
        $response = $this->get('/process?' . http_build_query($param));

        // エラーが出ても処理が落ちずにリダイレクトされることを確認
        $response->assertStatus(302);

        // リダイレクト先の確認
        $response->assertRedirect(route('calculator.home'));

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // val2の指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['val2']);
        $response->assertInvalid(['val2']);

        // エラーメッセージの検証
        $this->assertEquals(
            $param['val1'] . ' / ' . $param['val2'] . ' = Division by zero.',
            $response->getSession()->get('errors')->get('val2')[0]
        );
    }

    /**
     * operator 未指定エラー
     *
     * @return void
     */
    public function test_operator_required_error(): void
    {
        $param =[
            'val1' => 5,
            'val2' => 4,
            'operator' => null
        ];
        $response = $this->get('/process?' . http_build_query($param));

        // エラーが出ても処理が落ちずにリダイレクトされることを確認
        $response->assertStatus(302);

        // リダイレクト先の確認
        $response->assertRedirect(route('calculator.home'));

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // operatorの指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['operator']);
        $response->assertInvalid(['operator']);

        // エラーメッセージの検証
        $this->assertEquals(
            'Please select an operator',
            $response->getSession()->get('errors')->get('operator')[0]
        );
    }

    /**
     * operator max over エラー
     *
     * @return void
     */
    public function test_operator_max_over_error(): void
    {
        $param =[
            'val1' => 5,
            'val2' => 4,
            'operator' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
        ];
        $response = $this->get('/process?' . http_build_query($param));

        // エラーが出ても処理が落ちずにリダイレクトされることを確認
        $response->assertStatus(302);

        // リダイレクト先の確認
        $response->assertRedirect(route('calculator.home'));

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // operatorの指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['operator']);
        $response->assertInvalid(['operator']);

        // エラーメッセージの検証
        $this->assertEquals(
            'That operator is invalid.',
            $response->getSession()->get('errors')->get('operator')[0]
        );
    }

    /**
     * operator に想定外の指定があった場合のエラー
     *
     * @return void
     */
    public function test_operator_invalid_error(): void
    {
        $param =[
            'val1' => 5,
            'val2' => 4,
            'operator' => 'abc'
        ];
        $response = $this->get('/process?' . http_build_query($param));

        // エラーが出ても処理が落ちずにリダイレクトされることを確認
        $response->assertStatus(302);

        // リダイレクト先の確認
        $response->assertRedirect(route('calculator.home'));

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // operatorの指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['operator']);
        $response->assertInvalid(['operator']);

        // エラーメッセージの検証
        $this->assertEquals(
            'That operator is invalid.',
            $response->getSession()->get('errors')->get('operator')[0]
        );
    }
}
