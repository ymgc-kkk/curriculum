<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Company::class;

    public function definition()
    {
        return [
            'company' => $this->faker->company(),
            'company_ruby' => 'あいうえおかぶしきがいしゃ', //生成した名前の読み仮名を作成できなかったため仮で使用
            'address' =>  $this->faker->address(),
            'phone_number' => '0000',
            'ceo' => $this->faker->name(),
            'ceo_ruby' => 'たなかたろう' //生成した名前の読み仮名を作成できなかったため仮で使用
        ];
    }
}
