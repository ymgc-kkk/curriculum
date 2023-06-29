<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Address;
use App\Models\Company;
use Faker\Factory;

class AddressControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function 新規作成()
    {
        $faker = Factory::create();
        $company = Company::factory()->create();

        $params = [
            'name' => $faker->company,
            'name_kana' => 'あいうえおかぶしきがいしゃ',
            'address' => $faker->address,
            'phone_number' => '987654321',
            'department'=>'開発部',
            'to' =>  $faker->name,
            'to_kana' => 'やまだじろう'
        ];

        $res = $this->postJson(route('api.address.store',  $company->id), $params);
        $res->assertOk();
        $addresses = Address::all();

        $this->assertCount(1, $addresses);

        $address = $addresses->first();

        $this->assertSame($params['name'], $address->name);
        $this->assertSame($params['name_kana'], $address->name_kana);
        $this->assertSame($params['address'], $address->address);
        $this->assertSame($params['phone_number'], $address->phone_number);
        $this->assertSame($params['department'], $address->department);
        $this->assertSame($params['to'], $address->to);
        $this->assertSame($params['to_kana'], $address->to_kana);

    }

    /**
     * @test
     */
    public function 新規作成の失敗時()
    {
        $company = Company::factory()->create();
        $params = [
            'name' => '',
            'name_kana' => '',
            'address' => '',
            'phone_number' => null,
            'department'=>'',
            'to' => '',
            'to_kana' => ''
        ];

        $res = $this->postJson(route('api.address.store', $company->id), $params);
        $res->assertStatus(422);
        $addresses = Address::all();

        $this->assertCount(0, $addresses);
    }

     /**
     * @test
     */
    public function 更新処理()
    {
        $address = Address::factory()->create();
        $faker = Factory::create();
        $params = [
            'name' => $faker->company,
            'name_kana' => 'あいうえおかぶしきがいしゃ',
            'address' => $faker->address,
            'phone_number' => '987654321',
            'department'=>'開発部',
            'to' =>  $faker->name,
            'to_kana' => 'やまだじろう'
        ];
        
        $this->putJson(route('api.address.update', ['id' => $address->id]), $params);

        $updatedAddress = Address::findOrFail($address->id);
        $this->assertSame($params['name'], $updatedAddress->name);
        $this->assertSame($params['name_kana'], $updatedAddress->name_kana);
        $this->assertSame($params['address'], $updatedAddress->address);
        $this->assertSame($params['phone_number'], $updatedAddress->phone_number);
        $this->assertSame($params['department'], $updatedAddress->department);
        $this->assertSame($params['to'], $updatedAddress->to);
        $this->assertSame($params['to_kana'], $updatedAddress->to_kana);

    }

     /**
     * @test
     */
    public function 更新処理失敗()
    {
        $address = Address::factory()->create();
        $response = $this->putJson(route('api.address.update', ['id' => $address->id]));

        $response->assertStatus(422);

    }

     /**
     * @test
     */
    public function 詳細取得()
    {
        $address = Address::factory()->create();

        $response = $this->getJson(route('api.address.show', $address->id));
        $response->assertOk();

        $response->assertJson([
            'address'=>[
                'name' => $address->name,
                'name_kana' => $address->name_kana,
                'address' => $address->address,
                'phone_number' => $address->phone_number,
                'department'=>$address->department,
                'to' => $address->to,
                'to_kana' => $address->to_kana
            ]
        ]);
    }

     /**
     * @test
     */
    public function 詳細取得失敗()
    {
        $address = Address::factory()->create();
        $response = $this->getJson(route('api.address.show', $address->id +1));

        $response->assertStatus(404);
    }

    /**
    * @test
    */
    public function 削除処理()
    {
        $address = Address::factory()->create([
            'company_id' => Company::factory()->create()->id,
        ]);
        $response = $this->delete(route('api.address.destroy', ['id' => $address->id]));
        $response->assertOk();
        $this->assertCount(0, Address::all());
    }

    /**
    * @test
    */
    public function 削除処理失敗()
    {
        $address = Address::factory()->create();
        $response = $this->delete(route('api.address.destroy', $address->id +1));

        $response->assertStatus(404);
    }
}