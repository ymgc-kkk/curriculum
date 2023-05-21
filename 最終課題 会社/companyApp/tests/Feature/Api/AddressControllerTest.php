<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Address;
use App\Models\Company;

class AddressControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function 新規作成()
    {
        $params = [
            'company_id' =>0,
            'billing'=>'あいうえお株式会社',
            'billing_ruby'=>'あいうえおかぶしきがいしゃ',
            'address'=>'東京都千代田区111-111',
            'phone_number'=>'0123456789',
            'department'=>'商品部',
            'to'=>'田中太郎',
            'to_ruby'=>'たなかたろう'
        ];

        $res = $this->postJson(route('api.address.store'), $params);
        $res->assertOk();
        $addresses = Address::all();

        $this->assertCount(1, $addresses);

        $address = $addresses->first();

        $this->assertSame($params['billing'], $address->billing);
        $this->assertSame($params['billing_ruby'], $address->billing_ruby);
        $this->assertSame($params['address'], $address->address);
        $this->assertSame($params['phone_number'], $address->phone_number);
        $this->assertSame($params['department'], $address->department);
        $this->assertSame($params['to'], $address->to);
        $this->assertSame($params['to_ruby'], $address->to_ruby);

    }

    /**
     * @test
     */
    public function 新規作成の失敗時()
    {
        $params = [
            'billing' => '',
            'billing_ruby' => '',
            'address' => '',
            'phone_number' => null,
            'department'=>'',
            'to' => '',
            'to_ruby' => ''
        ];

        $res = $this->postJson(route('api.address.store'), $params);
        $res->assertStatus(422);
        $addresses = Address::all();

        $this->assertCount(0, $addresses);
    }

     /**
     * @test
     */
    public function 更新処理()
    {
        // テスト用データ
        $address = Address::factory()->create([
            'company_id' => Company::factory()->create()->id,
            'billing' => 'あいうえお株式会社',
            'billing_ruby' => 'あいうえおかぶしきがいしゃ',
            'address' => '東京都千代田区111-111',
            'phone_number' => '123456789',
            'department'=>'商品部',
            'to' => '田中太郎',
            'to_ruby' => 'たなかたろう'
        ]);

        // 更新APIを呼び出す
        $params = [
            'company_id' => Company::factory()->create()->id,
            'billing' => 'かきくけこ株式会社',
            'billing_ruby' => 'かきくけこかぶしきがいしゃ',
            'address' => '東京都品川区2222-222',
            'phone_number' => '987654321',
            'department'=>'開発部',
            'to' => '山田二郎',
            'to_ruby' => 'やまだじろう'
        ];
        $this->putJson(route('api.address.update', ['id' => $address->id]), $params);

        // データが更新されていることを確認する
        $updatedAddress = Address::findOrFail($address->id);
        $this->assertSame($params['billing'], $updatedAddress->billing);
        $this->assertSame($params['billing_ruby'], $updatedAddress->billing_ruby);
        $this->assertSame($params['address'], $updatedAddress->address);
        $this->assertSame($params['phone_number'], $updatedAddress->phone_number);
        $this->assertSame($params['department'], $updatedAddress->department);
        $this->assertSame($params['to'], $updatedAddress->to);
        $this->assertSame($params['to_ruby'], $updatedAddress->to_ruby);

    }

     /**
     * @test
     */
    public function 更新処理失敗()
    {
        // テスト用データ
        $company = Company::factory()->create();
        $address = Address::factory()->create([
            'company_id' => $company->id,
            'billing' => 'あいうえお株式会社',
            'billing_ruby' => 'あいうえおかぶしきがいしゃ',
            'address' => '東京都千代田区111-111',
            'phone_number' => '123456789',
            'department'=>'商品部',
            'to' => '田中太郎',
            'to_ruby' => 'たなかたろう'
        ]);

        // 更新APIを呼び出す
        $response = $this->putJson(route('api.address.update', ['id' => $address->company_id]),['billing' => null]);

        // ステータスコードが422であることを確認
        $response->assertStatus(422);

        // データが更新されていないことを確認
        $updatedAddress = Address::findOrFail($address->id);
        $this->assertSame($address->billing, $updatedAddress->address);
        $this->assertSame($address->billing_ruby, $updatedAddress->billing_ruby);
        $this->assertSame($address->address, $updatedAddress->address);
        $this->assertSame($address->phone_number, $updatedAddress->phone_number);
        $this->assertSame($address->to, $updatedAddress->to);
        $this->assertSame($address->to_ruby, $updatedAddress->to_ruby);
    }

     /**
     * @test
     */
    public function 詳細取得()
    {
        // テスト用データ
        $address = Address::factory()->create([
            'company_id' => Company::factory()->create()->id,
            'billing' => 'あいうえお株式会社',
            'billing_ruby' => 'あいうえおかぶしきがいしゃ',
            'address' => '東京都千代田区111-111',
            'phone_number' => '123456789',
            'department'=>'商品部',
            'to' => '田中太郎',
            'to_ruby' => 'たなかたろう'
        ]);
        // 詳細取得APIを呼び出す
        $response = $this->getJson(route('api.address.show', $address->id));
        $response->assertOk();

        // 取得したデータが正しいことを確認する
        $response->assertJson([
            'address'=>[
                'billing' => $address->billing,
                'billing_ruby' => $address->billing_ruby,
                'address' => $address->address,
                'phone_number' => $address->phone_number,
                'department'=>$address->department,
                'to' => $address->to,
                'to_ruby' => $address->to_ruby
            ]
        ]);
    }

     /**
     * @test
     */
    public function 詳細取得失敗()
    {
        // 存在しないIDを使用して詳細を取得する
        $response = $this->getJson(route('api.address.show', ['id' => -1]));

        // ステータスコードとエラーメッセージを確認する
        $response->assertStatus(404);
    }

    /**
    * @test
    */
    public function 削除処理()
    {
        // テスト用データ
        $address = Address::factory()->create([
            'company_id' => Company::factory()->create()->id,
            'billing' => 'あいうえお株式会社',
            'billing_ruby' => 'あいうえおかぶしきがいしゃ',
            'address' => '東京都千代田区111-111',
            'phone_number' => '123456789',
            'department'=>'商品部',
            'to' => '田中太郎',
            'to_ruby' => 'たなかたろう'
        ]);

        // データを確認
        $this->assertDatabaseHas('addresses', $address->toArray());

        // 削除
        $response = $this->delete(route('api.address.destroy', ['id' => $address->id]));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('addresses', $address->toArray());
    }

    /**
    * @test
    */
    public function 削除処理失敗()
    {
        // 削除処理を実行
        $response = $this->delete(route('api.address.destroy', ['id' => -1]));

        // ステータスコード 404 (Not Found) が返されることを検証
        $response->assertStatus(404);
    }
}