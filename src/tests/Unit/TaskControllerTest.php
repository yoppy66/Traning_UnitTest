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


        // CSRFトークンをヘッダに追加し、「追加する」ボタン押下のPOST

        // POST処理後のリダイレクト

        // 追加したtodoの存在を確認

    }

    /**
     * 「編集」ボタン テスト
     */
    public function test_todo_edit_success(): void
    {
        // 編集対象のタスクを作成する

        // まずは tasks のページにアクセス

        // 編集フォームにアクセス

        // 編集フォームに正しいデータが表示されることを確認

        // 編集フォームに変更内容を入力して送信

        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST

        // データベースに変更が反映され、リダイレクト先が正しいことを確認

        // タスクが正しく更新されたことを確認
    }

    /**
     * 「削除」ボタン テスト
     */
    public function test_todo_destroy_success(): void
    {
        // テストデータを作成

        // まずは tasks のページにアクセス

        // CSRFトークンをヘッダに追加し、削除リクエストをPOST

        // リダイレクトが正しく行われたことを確認

        // 削除が正しく行われたことを確認

    }

    /**
     * 「完了」ボタン テスト
     */
    public function test_todo_complete_success(): void
    {
        // テストデータを作成


        // まずは tasks のページにアクセス


        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST


        // データベースに変更が反映され、リダイレクト先が正しいことを確認

        // タスクが正しく更新されたことを確認

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


        // CSRFトークンをヘッダに追加し、「追加する」ボタン押下のPOST

        // POST処理後のリダイレクト

        // validateでエラーが発生したことの確認

        // task_nameの指定でエラーが発生したことを掴めているか確認

        // エラーメッセージの検証

    }

    /**
     * 「追加する」ボタン
     *
     *  maxエラー : task_name
     */
    public function test_todo_store_error_max_task_name(): void
    {
        // まずは tasks のページにアクセス

        // CSRFトークンをヘッダに追加し、「追加する」ボタン押下のPOST

        // POST処理後のリダイレクト

        // validateでエラーが発生したことの確認

        // task_nameの指定でエラーが発生したことを掴めているか確認

        // エラーメッセージの検証

    }

    /**
     * 「編集」ボタン テスト
     *
     *  requireエラー : task_name
     */
    public function test_todo_edit_error_require_task_name(): void
    {
        // 編集対象のタスクを作成する

        // まずは tasks のページにアクセス

        // 編集フォームにアクセス

        // 編集フォームに正しいデータが表示されることを確認

        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST

        // POST処理後のリダイレクト

        // validateでエラーが発生したことの確認

        // task_nameの指定でエラーが発生したことを掴めているか確認

        // エラーメッセージの検証

    }

    /**
     * 「編集」ボタン テスト
     *
     *  maxエラー : task_name
     */
    public function test_todo_edit_error_max_task_name(): void
    {
        // 編集対象のタスクを作成する

        // まずは tasks のページにアクセス

        // 編集フォームにアクセス

        // 編集フォームに正しいデータが表示されることを確認

        // 編集フォームに変更内容を入力して送信

        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST

        // POST処理後のリダイレクト

        // validateでエラーが発生したことの確認

        // task_nameの指定でエラーが発生したことを掴めているか確認

        // エラーメッセージの検証

    }

    /**
     * 「編集」ボタン テスト
     *
     * 存在しないtask_idを指定してエラーが出るか確認する
     */
    public function test_todo_edit_error_notexist_task_id(): void
    {
        // 編集対象のタスクを作成する

        // まずは tasks のページにアクセス

        // 編集フォームにアクセス

        // 編集フォームに正しいデータが表示されることを確認

        // 存在しないtask_idを生成

        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST

        // POST処理後のリダイレクト

        // validateでエラーが発生したことの確認

        // idの指定でエラーが発生したことを掴めているか確認

        // エラーメッセージの検証

    }

    /**
     * 「削除」ボタン
     *
     * 存在しないtask_idを指定してエラーが出るか確認する
     */
    public function test_todo_destroy_error_notexist_task_id(): void
    {
        // テストデータを作成

        // まずは tasks のページにアクセス

        // 存在しないtask_idを生成

        // CSRFトークンをヘッダに追加し、削除リクエストをPOST

        // POST処理後のリダイレクト

        // validateでエラーが発生したことの確認

        // idの指定でエラーが発生したことを掴めているか確認

        // エラーメッセージの検証

    }

    /**
     * 「完了」ボタン
     *
     * 存在しないtask_idを指定してエラーが出るか確認する
     */
    public function test_todo_complete_error_notexist_task_id(): void
    {
        // テストデータを作成

        // まずは tasks のページにアクセス

        // 存在しないtask_idを生成

        // CSRFトークンをヘッダに追加し、「編集する」ボタン押下のPOST

        // POST処理後のリダイレクト

        // validateでエラーが発生したことの確認

        // idの指定でエラーが発生したことを掴めているか確認

        // エラーメッセージの検証

    }
}
