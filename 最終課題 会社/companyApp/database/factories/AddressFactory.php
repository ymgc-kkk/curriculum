<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory()->create()->id,
            'billing' => $this->faker->company(),
            'billing_ruby' => 'あいうえおかぶしきがいしゃ', //生成した名前の読み仮名を作成できなかったため仮で使用
            'address' =>  $this->faker->address(),
            'phone_number' => '0000',
            'department' => '部署名',
            'to' => $this->faker->name(),
            'to_ruby' => 'たなかたろう' //生成した名前の読み仮名を作成できなかったため仮で使用
        ];
    }
}
