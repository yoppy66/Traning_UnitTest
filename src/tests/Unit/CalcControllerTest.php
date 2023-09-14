<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalcControllerTest extends TestCase
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
     * 割り算が正常に行われるかのテスト
     * @throws \JsonException
     */
    public function test_divid_successful(): void
    {
        $param =[
            'val1' => 10,
            'val2' => 2,
            'operator' => 'divide'
        ];
        $response = $this->get('/process?' . http_build_query($param));

        // バリデーションエラーが発生しなかったことを確認
        $response->assertSessionHasNoErrors();

        // 正常にリダイレクトされることを確認
        $response->assertStatus(302);

        // リダイレクト先の確認
        $response->assertRedirect(route('calculator.home'));

        // 割り算の結果が5であることを確認
        $this->assertEquals(5, session('sum'));
    }

    /**
     * 掛け算が正常に行われるかのテスト
     * @throws \JsonException
     */
    public function test_multiply_successful(): void
    {
        $param =[
            'val1' => 2,
            'val2' => 3,
            'operator' => 'multiply'
        ];
        $response = $this->get('/process?' . http_build_query($param));

        // バリデーションエラーが発生しなかったことを確認
//        $response->assertSessionHasNoErrors();

        // 正常にリダイレクトされることを確認
        $response->assertStatus(302);

        // リダイレクト先の確認
        $response->assertRedirect(route('calculator.home'));

        // 掛け算の結果が6であることを確認
        $this->assertEquals(6, session('sum'));
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
     * 掛け算で入力エラーになるテスト val1 required
     */
    public function test_multiply_input_val1_required_error(): void
    {
        $param =[
            'val1' => null,
            'val2' => 2,
            'operator' => 'multiply'
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
     * 掛け算で入力エラーになるテスト val1 number
     */
    public function test_multiply_input_val1_number_error(): void
    {
        $param =[
            'val1' => 'a',
            'val2' => 2,
            'operator' => 'multiply'
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
     * 掛け算で入力エラーになるテスト val2 required
     */
    public function test_multiply_input_val2_required_error(): void
    {
        $param =[
            'val2' => 3,
            'val2' => null,
            'operator' => 'multiply'
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
            'Value 2 cannot be blank',
            $response->getSession()->get('errors')->get('val2')[0]
        );
    }

    /**
     * 掛け算で入力エラーになるテスト val2 number
     */
    public function test_multiply_input_val2_number_error(): void
    {
        $param =[
            'val2' => 3,
            'val2' => 'a',
            'operator' => 'multiply'
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
            'Value 2 must be a number',
            $response->getSession()->get('errors')->get('val2')[0]
        );
    }
}
