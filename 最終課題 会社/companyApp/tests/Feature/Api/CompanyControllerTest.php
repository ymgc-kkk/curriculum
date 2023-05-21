<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Company;

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
            'company' => 'あいうえお株式会社',
            'company_ruby' => 'あいうえおかぶしきがいしゃ',
            'address' => '東京都千代田区111-111',
            'phone_number' => '123456789',
            'ceo' => '田中太郎',
            'ceo_ruby' => 'たなかたろう'
        ];

        $res = $this->postJson(route('api.company.store'), $params);
        $res->assertOk();
        $companies = company::all();

        $this->assertCount(1, $companies);

        $company = $companies->first();

        $this->assertSame($params['company'], $company->company);
        $this->assertSame($params['company_ruby'], $company->company_ruby);
        $this->assertSame($params['address'], $company->address);
        $this->assertSame($params['phone_number'], $company->phone_number);
        $this->assertSame($params['ceo'], $company->ceo);
        $this->assertSame($params['ceo_ruby'], $company->ceo_ruby);

    }

    /**
     * @test
     */
    public function 新規作成の失敗時()
    {
        $params = [
            'company' => '',
            'company_ruby' => '',
            'address' => '',
            'phone_number' => null,
            'ceo' => '',
            'ceo_ruby' => ''
        ];

        $res = $this->postJson(route('api.company.store'), $params);
        $res->assertStatus(422);
        $companies = Company::all();

        $this->assertCount(0, $companies);
    }

     /**
     * @test
     */
    public function 更新処理()
    {
        // テスト用データ
        $company = Company::factory()->create([
            'company' => 'あいうえお株式会社',
            'company_ruby' => 'あいうえおかぶしきがいしゃ',
            'address' => '東京都千代田区111-111',
            'phone_number' => '123456789',
            'ceo' => '田中太郎',
            'ceo_ruby' => 'たなかたろう'
        ]);

        // 更新APIを呼び出す
        $params = [
            'company' => 'かきくけこ株式会社',
            'company_ruby' => 'かきくけこかぶしきがいしゃ',
            'address' => '東京都品川区2222-222',
            'phone_number' => '987654321',
            'ceo' => '山田二郎',
            'ceo_ruby' => 'やまだじろう'
        ];
        $this->putJson(route('api.company.update', ['id' => $company->id]), $params);

        // データが更新されていることを確認する
        $updatedCompany = Company::findOrFail($company->id);
        $this->assertSame($params['company'], $updatedCompany->company);
        $this->assertSame($params['company_ruby'], $updatedCompany->company_ruby);
        $this->assertSame($params['address'], $updatedCompany->address);
        $this->assertSame($params['phone_number'], $updatedCompany->phone_number);
        $this->assertSame($params['ceo'], $updatedCompany->ceo);
        $this->assertSame($params['ceo_ruby'], $updatedCompany->ceo_ruby);

    }

     /**
     * @test
     */
    public function 更新処理失敗()
    {
        // テスト用データ
        $company = Company::factory()->create([
            'company' => 'あいうえお株式会社',
            'company_ruby' => 'あいうえおかぶしきがいしゃ',
            'address' => '東京都千代田区111-111',
            'phone_number' => '123456789',
            'ceo' => '田中太郎',
            'ceo_ruby' => 'たなかたろう'
        ]);

        // 更新APIを呼び出す
        $response = $this->putJson(route('api.company.update', ['id' => $company->id]));

        // ステータスコードが422であることを確認
        $response->assertStatus(422);

        // データが更新されていないことを確認
        $updatedCompany = Company::findOrFail($company->id);
        $this->assertSame($company->company, $updatedCompany->company);
        $this->assertSame($company->company_ruby, $updatedCompany->company_ruby);
        $this->assertSame($company->address, $updatedCompany->address);
        $this->assertSame($company->phone_number, $updatedCompany->phone_number);
        $this->assertSame($company->ceo, $updatedCompany->ceo);
        $this->assertSame($company->ceo_ruby, $updatedCompany->ceo_ruby);
    }

     /**
     * @test
     */
    public function 詳細取得()
    {
        // テスト用データ
        $company = Company::factory()->create([
            'company' => 'TEST',
            'company_ruby' => 'てすと',
            'address' => '東京都テスト',
            'phone_number' => '0000',
            'ceo' => 'テスト太郎',
            'ceo_ruby' => 'てすとたろう'
        ]);
        // 詳細取得APIを呼び出す
        $response = $this->getJson(route('api.company.show', $company->id));
        $response->assertOk();

        // 取得したデータが正しいことを確認する
        $response->assertJson([
            'company'=>[
                'company' => $company->company,
                'company_ruby' => $company->company_ruby,
                'address' => $company->address,
                'phone_number' => $company->phone_number,
                'ceo' => $company->ceo,
                'ceo_ruby' => $company->ceo_ruby
            ]
        ]);
    }

     /**
     * @test
     */
    public function 詳細取得失敗()
    {
        // 存在しないIDを使用して詳細を取得する
        $response = $this->getJson(route('api.company.show', ['id' => -1]));

        // ステータスコードとエラーメッセージを確認する
        $response->assertStatus(404);
    }

    /**
    * @test
    */
    public function 削除処理()
    {
        // テスト用データ
        $company = Company::factory()->create([
            'company' => 'TEST',
            'company_ruby' => 'てすと',
            'address' => '東京都テスト',
            'phone_number' => '0000',
            'ceo' => 'テスト太郎',
            'ceo_ruby' => 'てすとたろう'
        ]);

        // データを確認
        $this->assertDatabaseHas('companies', $company->toArray());

        // 削除
        $response = $this->delete(route('api.company.destroy', ['id' => $company->id]));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('companies', $company->toArray());
    }

    /**
    * @test
    */
    public function 削除処理失敗()
    {
        // 削除処理を実行
        $response = $this->delete(route('api.company.destroy', ['id' => -1]));

        // ステータスコード 404 (Not Found) が返されることを検証
        $response->assertStatus(404);
    }
}