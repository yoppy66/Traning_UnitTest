<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Task;


class TaskControllerTest extends TestCase
{
    use DatabaseTransactions; // トレイトを追加 テストデータがDBに残らないようにする


    /**
     * 「追加する」ボタン テスト
     */
    public function test_todo_store_success(): void
    {
        // まずは tasks のページにアクセス
        $this->get('/tasks')->assertStatus(200);

        // CSRFトークンをヘッダに追加し、「追加する」ボタン押下のPOST
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('/tasks', [
            'task_name' => 'Test task',
        ]);

        // POST処理後のリダイレクト
        $response->assertStatus(302);
        $response->assertRedirect('/tasks');

        // 追加したtodoの存在を確認
        $task = Task::where('name', 'Test task')->first();
        $this->assertNotNull($task);
    }

    /**
     * 「編集」ボタン テスト
     */
    public function test_todo_edit_success(): void
    {
        // 編集対象のタスクを作成する
        $task = Task::factory()->create([
            'name' => 'Test task',
            'status' => false,
        ]);

        // まずは tasks のページにアクセス
        $this->get('/tasks')->assertStatus(200);

        // 編集フォームにアクセス
        $response = $this->get('/tasks/' . $task->id . '/edit');
        $response->assertStatus(200);

        // 編集フォームに正しいデータが表示されることを確認
        $response->assertSee('Test task');

        // 編集フォームに変更内容を入力して送信
        $newName = 'Updated task';

        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->put('/tasks/' . $task->id, [
            '_method' => 'PUT',
            'task_name' => $newName,
        ]);

        // データベースに変更が反映され、リダイレクト先が正しいことを確認
        $response->assertStatus(302);
        $response->assertRedirect('/tasks');

        // タスクが正しく更新されたことを確認
        $updatedTask = Task::find($task->id);
        $this->assertEquals($newName, $updatedTask->name);
//        $this->assertSame($newName, $updatedTask->name);
    }

    /**
     * 「削除」ボタン テスト
     */
    public function test_todo_destroy_success(): void
    {
        // テストデータを作成
        $task = Task::factory()->create();

        // まずは tasks のページにアクセス
        $this->get('/tasks')->assertStatus(200);

        // CSRFトークンをヘッダに追加し、削除リクエストをPOST
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->delete('/tasks/' . $task->id, [
            '_token' => csrf_token(),
            '_method' => 'DELETE',
        ]);

        // リダイレクトが正しく行われたことを確認
        $response->assertStatus(302);
        $response->assertRedirect('/tasks');

        // 削除が正しく行われたことを確認
        if(version_compare(app()->version(), '9.0.0') < 0){
            $this->assertDeleted($task);        // ver8系まではこちらで確認
        }else{
            $this->assertModelMissing($task);   // ナウいのはコッチ
        }
    }

    /**
     * 「完了」ボタン テスト
     */
    public function test_todo_complete_success(): void
    {
        // テストデータを作成
        $task = Task::factory()->create();

        // まずは tasks のページにアクセス
        $this->get('/tasks')->assertStatus(200);

        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->put('/tasks/' . $task->id, [
            '_method' => 'PUT',
            'status' => $task->status,
        ]);

        // データベースに変更が反映され、リダイレクト先が正しいことを確認
        $response->assertStatus(302);
        $response->assertRedirect('/tasks');

        // タスクが正しく更新されたことを確認
        $updatedTask = Task::find($task->id);
        $this->assertTrue($updatedTask->status);
    }


    // ************** 以下はエラーになることを確認するテスト *****************

    /**
     * 「追加する」ボタン
     *
     *  requireエラー : task_name
     */
    public function test_todo_store_error_require_task_name(): void
    {
        // まずは tasks のページにアクセス
        $this->get('/tasks')->assertStatus(200);

        // CSRFトークンをヘッダに追加し、「追加する」ボタン押下のPOST
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('/tasks', [
        ]);

        // POST処理後のリダイレクト
        $response->assertStatus(302);
        $response->assertRedirect('/tasks');

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // task_nameの指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['task_name']);
        $response->assertInvalid(['task_name']);

        // エラーメッセージの検証
        $this->assertEquals(
            '必須項目です',
            $response->getSession()->get('errors')->get('task_name')[0]
        );
    }

    /**
     * 「追加する」ボタン
     *
     *  maxエラー : task_name
     */
    public function test_todo_store_error_max_task_name(): void
    {
        // まずは tasks のページにアクセス
        $this->get('/tasks')->assertStatus(200);

        // CSRFトークンをヘッダに追加し、「追加する」ボタン押下のPOST
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('/tasks', [
            'task_name' => '100文字以上入力したらエラーになる検証をせんといかんから101文字を設定する。100文字以上入力したらエラーになる検証をせんといかんから101文字を設定する。012345678901234567890',
        ]);

        // POST処理後のリダイレクト
        $response->assertStatus(302);
        $response->assertRedirect('/tasks');

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // task_nameの指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['task_name']);
        $response->assertInvalid(['task_name']);

        // エラーメッセージの検証
        $this->assertEquals(
            '100文字以下にしてください',
            $response->getSession()->get('errors')->get('task_name')[0]
        );
    }

    /**
     * 「編集」ボタン テスト
     *
     *  requireエラー : task_name
     */
    public function test_todo_edit_error_require_task_name(): void
    {
        // 編集対象のタスクを作成する
        $task = Task::factory()->create([
            'name' => 'Test task',
            'status' => false,
        ]);

        // まずは tasks のページにアクセス
        $this->get('/tasks')->assertStatus(200);

        // 編集フォームにアクセス
        $response = $this->get('/tasks/' . $task->id . '/edit');
        $response->assertStatus(200);

        // 編集フォームに正しいデータが表示されることを確認
        $response->assertSee('Test task');

        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('/tasks/' . $task->id, [
            '_method' => 'PUT',
        ]);

        // POST処理後のリダイレクト
        $response->assertStatus(302);
        $response->assertRedirect('/tasks/' . $task->id . '/edit');

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // task_nameの指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['task_name']);
        $response->assertInvalid(['task_name']);

        // エラーメッセージの検証
        $this->assertEquals(
            '必須項目です',
            $response->getSession()->get('errors')->get('task_name')[0]
        );
    }

    /**
     * 「編集」ボタン テスト
     *
     *  maxエラー : task_name
     */
    public function test_todo_edit_error_max_task_name(): void
    {
        // 編集対象のタスクを作成する
        $task = Task::factory()->create([
            'name' => 'Test task',
            'status' => false,
        ]);

        // まずは tasks のページにアクセス
        $this->get('/tasks')->assertStatus(200);

        // 編集フォームにアクセス
        $response = $this->get('/tasks/' . $task->id . '/edit');
        $response->assertStatus(200);

        // 編集フォームに正しいデータが表示されることを確認
        $response->assertSee('Test task');

        // 編集フォームに変更内容を入力して送信
        $newName = 'Updated task';

        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->put('/tasks/' . $task->id, [
            '_method' => 'PUT',
            'task_name' => '100文字以上入力したらエラーになる検証をせんといかんから101文字を設定する。100文字以上入力したらエラーになる検証をせんといかんから101文字を設定する。012345678901234567890',
        ]);

        // POST処理後のリダイレクト
        $response->assertStatus(302);
        $response->assertRedirect('/tasks/' . $task->id . '/edit');

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // task_nameの指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['task_name']);
        $response->assertInvalid(['task_name']);

        // エラーメッセージの検証
        $this->assertEquals(
            '100文字以下にしてください',
            $response->getSession()->get('errors')->get('task_name')[0]
        );
    }

    /**
     * 「編集」ボタン テスト
     *
     * 存在しないtask_idを指定してエラーが出るか確認する
     */
    public function test_todo_edit_error_notexist_task_id(): void
    {
        // 編集対象のタスクを作成する
        $task = Task::factory()->create([
            'name' => 'Test task',
            'status' => false,
        ]);

        // まずは tasks のページにアクセス
        $this->get('/tasks')->assertStatus(200);

        // 編集フォームにアクセス
        $response = $this->get('/tasks/' . $task->id . '/edit');
        $response->assertStatus(200);

        // 編集フォームに正しいデータが表示されることを確認
        $response->assertSee('Test task');

        // 存在しないtask_idを生成
        for($ii=0; $ii<9; $ii++){
            $notExistTaskId = rand(100000, 999999);
            $taskExists = Task::where('id', $notExistTaskId)->exists();
            if (!$taskExists){
                break;
            }
        }

        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('/tasks/' . $notExistTaskId, [
            '_method' => 'PUT',
            'task_name' => 'あああ',
        ]);

        // POST処理後のリダイレクト
        $response->assertStatus(302);
        $response->assertRedirect('/tasks/' . $task->id . '/edit');

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // idの指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['id']);
        $response->assertInvalid(['id']);

        // エラーメッセージの検証
        $this->assertEquals(
            '存在しない項目が指定されました',
            $response->getSession()->get('errors')->get('id')[0]
        );
    }

    /**
     * 「削除」ボタン
     *
     * 存在しないtask_idを指定してエラーが出るか確認する
     */
    public function test_todo_destroy_error_notexist_task_id(): void
    {
        // テストデータを作成
        $task = Task::factory()->create();

        // まずは tasks のページにアクセス
        $this->get('/tasks')->assertStatus(200);

        // 存在しないtask_idを生成
        for($ii=0; $ii<9; $ii++){
            $notExistTaskId = rand(100000, 999999);
            $taskExists = Task::where('id', $notExistTaskId)->exists();
            if (!$taskExists){
                break;
            }
        }

        // CSRFトークンをヘッダに追加し、削除リクエストをPOST
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->delete('/tasks/' . $notExistTaskId, [
            '_token' => csrf_token(),
            '_method' => 'DELETE',
        ]);

        // POST処理後のリダイレクト
        $response->assertStatus(302);
        $response->assertRedirect('/tasks');

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // idの指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['id']);
        $response->assertInvalid(['id']);

        // エラーメッセージの検証
        $this->assertEquals(
            '存在しない項目が指定されました',
            $response->getSession()->get('errors')->get('id')[0]
        );
    }

    /**
     * 「完了」ボタン
     *
     * 存在しないtask_idを指定してエラーが出るか確認する
     */
    public function test_todo_complete_error_notexist_task_id(): void
    {
        // テストデータを作成
        $task = Task::factory()->create();

        // まずは tasks のページにアクセス
        $this->get('/tasks')->assertStatus(200);

        // 存在しないtask_idを生成
        for($ii=0; $ii<9; $ii++){
            $notExistTaskId = rand(100000, 999999);
            $taskExists = Task::where('id', $notExistTaskId)->exists();
            if (!$taskExists){
                break;
            }
        }

        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->put('/tasks/' . $notExistTaskId, [
            '_method' => 'PUT',
            'status' => true,
        ]);

        // POST処理後のリダイレクト
        $response->assertStatus(302);
        $response->assertRedirect('/tasks');

        // validateでエラーが発生したことの確認
        $response->assertSessionHasErrors();

        // idの指定でエラーが発生したことを掴めているか確認
        $response->assertSessionHasErrors(['id']);
        $response->assertInvalid(['id']);

        // エラーメッセージの検証
        $this->assertEquals(
            '存在しない項目が指定されました',
            $response->getSession()->get('errors')->get('id')[0]
        );
    }
}
