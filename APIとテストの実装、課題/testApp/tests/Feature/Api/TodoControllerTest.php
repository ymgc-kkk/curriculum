<?php

namespace Tests\Feature\Api;

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function Todoの新規作成()
    {
        $params = [
            "title" => "テスト：タイトル",
            "content" => "テスト：内容"
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertOk();
        $todos = Todo::all();

        $this->assertCount(1, $todos);

        $todo = $todos->first();

        $this->assertEquals($params['title'], $todo->title);
        $this->assertEquals($params['content'], $todo->content);
    }
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function 新規作成の失敗時()
    {
        $params = [
            "title" => "",
            "content" => ""
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
    $todo = new Todo;
    $todo->title = 'test title';
    $todo->content = 'test content';
    $todo->save();

    $newData = [
        'title' => 'updated title',
        'content' => 'updated content',
    ];

    // 更新APIを呼び出す
    $response = $this->putJson(route('api.todo.update', ['id' => $todo->id]), $newData);
    $response->assertRedirect();

    // データが更新されていることを確認する
    $updatedTodo = Todo::findOrFail($todo->id);
    $this->assertEquals($newData['title'], $updatedTodo->title);
    $this->assertEquals($newData['content'], $updatedTodo->content);
    }

     /**
     * @test
     */
    public function 更新処理失敗()
    {
    // テスト用データ
    $todo = new Todo;
    $todo->title = 'test title';
    $todo->content = 'test content';
    $todo->save();

    $newData = [
        'title' => '',
        'content' => 'updated content',
    ];

    // 更新APIを呼び出す
    $response = $this->putJson(route('api.todo.update', ['id' => $todo->id]), $newData);

    // ステータスコードが422であることを確認する
    $response->assertStatus(422);

    // データが更新されていないことを確認する
    $updatedTodo = Todo::findOrFail($todo->id);
    $this->assertEquals($todo->title, $updatedTodo->title);
    $this->assertEquals($todo->content, $updatedTodo->content);
    }

     /**
     * @test
     */
    public function 詳細取得()
    {
    // テスト用データ
    $todo = new Todo;
    $todo->title = 'test title';
    $todo->content = 'test content';
    $todo->save();

    // 詳細取得APIを呼び出す
    $response = $this->getJson(route('api.todo.show', ['id' => $todo->id]));
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
        $response = $this->getJson(route('api.todo.show', ['id' => 999]));
    
        // ステータスコードとエラーメッセージを確認する
        $response->assertStatus(404)
                 ->assertJson(['message' => 'Todo not found']);
    }

}
