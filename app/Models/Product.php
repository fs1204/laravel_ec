<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'secondary_category_id',
        'image1',
    ];

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

    public function category() {
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
                                                // メソッド名をcategoryと短くしたので、外部キーのカラムを指定する
    }

    // image1() とすると、DBのカラム名と同じため、エラーになってしまうため、名前を変える
    public function imageFirst() {
        return $this->belongsTo(Image::class, 'image1');
    }
}
