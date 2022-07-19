<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
                //生成した順にproductのidが登録される
                // モデルファクトリ内でリレーションを定義。リレーションの外部キーへ新しいファクトリインスタンスを割り当てます。
                // Productを1件作って、作ったProductのidを'product_id'に設定する
            'type' => $this->faker->numberBetween(1,2),
            'quantity' => $this->faker->randomNumber,
        ];
    }
}
