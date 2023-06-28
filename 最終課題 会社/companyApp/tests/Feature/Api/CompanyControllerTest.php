<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Company;
use App\Models\Address;

class CompanyControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function 新規作成()
    {
        $params = [
            'name' => 'あいうえお株式会社',
            'name_kana' => 'あいうえおかぶしきがいしゃ',
            'address' => '東京都千代田区111-111',
            'phone_number' => '123456789',
            'ceo' => '田中太郎',
            'ceo_kana' => 'たなかたろう'
        ];

        $res = $this->postJson(route('api.company.store'), $params);
        $res->assertOk();
        $companies = company::all();

        $this->assertCount(1, $companies);

        $company = $companies->first();

        $this->assertSame($params['name'], $company->name);
        $this->assertSame($params['name_kana'], $company->name_kana);
        $this->assertSame($params['address'], $company->address);
        $this->assertSame($params['phone_number'], $company->phone_number);
        $this->assertSame($params['ceo'], $company->ceo);
        $this->assertSame($params['ceo_kana'], $company->ceo_kana);

    }

    /**
     * @test
     */
    public function 新規作成の失敗時()
    {
        $params = [
            'name' => '',
            'name_kana' => '',
            'address' => '',
            'phone_number' => null,
            'ceo' => '',
            'ceo_kana' => ''
        ];

        $res = $this->postJson(route('api.company.store'), $params);
        $res->assertStatus(422);
    }

     /**
     * @test
     */
    public function 更新処理()
    {
        $company = Company::factory()->create();

        $params = [
            'name' => 'かきくけこ株式会社',
            'name_kana' => 'かきくけこかぶしきがいしゃ',
            'address' => '東京都品川区2222-222',
            'phone_number' => '987654321',
            'ceo' => '山田二郎',
            'ceo_kana' => 'やまだじろう'
        ];
        $this->putJson(route('api.company.update', ['id' => $company->id]), $params);

        $updatedCompany = Company::findOrFail($company->id);
        $this->assertSame($params['name'], $updatedCompany->name);
        $this->assertSame($params['name_kana'], $updatedCompany->name_kana);
        $this->assertSame($params['address'], $updatedCompany->address);
        $this->assertSame($params['phone_number'], $updatedCompany->phone_number);
        $this->assertSame($params['ceo'], $updatedCompany->ceo);
        $this->assertSame($params['ceo_kana'], $updatedCompany->ceo_kana);

    }

     /**
     * @test
     */
    public function 更新処理失敗()
    {
        $company = Company::factory()->create();
        $response = $this->putJson(route('api.company.update', ['id' => $company->id]));

        $response->assertStatus(422);
    }

     /**
     * @test
     */
    public function 詳細取得()
    {
        $company = Company::factory()->create();
        $response = $this->getJson(route('api.company.show', $company->id));
        $response->assertOk();

        $response->assertJson([
            'company'=>[
                'name' => $company->name,
                'name_kana' => $company->name_kana,
                'address' => $company->address,
                'phone_number' => $company->phone_number,
                'ceo' => $company->ceo,
                'ceo_kana' => $company->ceo_kana
            ]
        ]);
    }

     /**
     * @test
     */
    public function 詳細取得不可()
    {
        $company = Company::factory()->create();
        $response = $this->getJson(route('api.company.show', ['id' => $company->id+1]));
        $response->assertStatus(404);
    }

    /**
    * @test
    */
    public function 削除処理()
    {
        $company = Company::factory()->create();
        $response = $this->deleteJson(route('api.company.destroy', $company->id));
        $response->assertOk();
        $this->assertCount(0, Company::all());
    }

    /**
    * @test
    */
    public function 削除処理失敗()
    {
        $company = Company::factory()->create();
        $response = $this->delete(route('api.company.destroy', ['id' => $company->id+1]));

        $response->assertStatus(404);
        $this->assertDatabaseHas('companies', ['id' => $company->id]);
    }
}