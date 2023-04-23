<?php

namespace Tests\Feature\Api;

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\Factory;

class TodoControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function Todoの新規作成()
    {
        $params = [
            'title' => 'テスト：タイトル',
            'content' => 'テスト：内容'
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertOk();
        $todos = Todo::all();

        $this->assertCount(1, $todos);

        $todo = $todos->first();

        $this->assertSame($params['title'], $todo->title);
        $this->assertSame($params['content'], $todo->content);
    }

    /**
     * @test
     */
    public function 新規作成の失敗時()
    {
        $params = [
            'title' => '',
            'content' => ''
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertStatus(422);
        $todos = Todo::all();

        $this->assertCount(0, $todos);
    }

     /**
     * @test
     */
    public function 更新処理()
    {
        // テスト用データ
        $todo = Todo::factory()->create([
            'title' => 'new title',
            'content' => 'new content',
        ]);

        // 更新APIを呼び出す
        $params = [
            'title' => 'updated title',
            'content' => 'updated content',
        ];
        $this->putJson(route('api.todo.update', ['id' => $todo->id]), $params);

        // データが更新されていることを確認する
        $updatedTodo = Todo::findOrFail($todo->id);
        $this->assertSame($params['title'], $updatedTodo->title);
        $this->assertSame($params['content'], $updatedTodo->content);
    }

     /**
     * @test
     */
    public function 更新処理失敗()
    {
        // テスト用データ
        $todo = Todo::factory()->create([
            'title' => '',
            'content' => 'updated content'
        ]);

        // 更新APIを呼び出す
        $response = $this->putJson(route('api.todo.update', ['id' => $todo->id]));

        // ステータスコードが422であることを確認
        $response->assertStatus(422);

        // データが更新されていないことを確認
        $updatedTodo = Todo::findOrFail($todo->id);
        $this->assertSame($todo->title, $updatedTodo->title);
        $this->assertSame($todo->content, $updatedTodo->content);
    }

     /**
     * @test
     */
    public function 詳細取得()
    {
        // テスト用データ
        $todo = Todo::factory()->create([
            'title' => 'test title',
            'content' => 'test content'
        ]);
        // 詳細取得APIを呼び出す
        $response = $this->getJson(route('api.todo.show', $todo->id));
        $response->assertOk();

        // 取得したデータが正しいことを確認する
        $response->assertJson([
            'title' => $todo->title,
            'content' => $todo->content,
        ]);
    }

     /**
     * @test
     */
    public function 詳細取得失敗()
    {
        // 存在しないIDを使用して詳細を取得する
        $response = $this->getJson(route('api.todo.show', ['id' => -1]));

        // ステータスコードとエラーメッセージを確認する
        $response->assertStatus(404);
    }

    /**
    * @test
    */
    public function 削除処理()
    {
        // テスト用データ
        $todo = Todo::factory()->create([
            'title' => 'test title',
            'content' => 'test content'
        ]);

        // データを確認
        $this->assertDatabaseHas('todos', $todo->toArray());

        // 削除
        $response = $this->delete(route('api.todo.delete', ['id' => $todo->id]));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('todos', $todo->toArray());
    }

    /**
    * @test
    */
    public function 削除処理失敗()
    {
        // 削除処理を実行
        $response = $this->delete(route('api.todo.delete', ['id' => -1]));

        // ステータスコード 404 (Not Found) が返されることを検証
        $response->assertStatus(404);
    }
}
