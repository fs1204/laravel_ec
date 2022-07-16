<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'information',
        'price',
        'is_selling',
        'sort_order',
        'secondary_category_id',
        'image1',
        'image2',
        'image3',
        'image4',
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
    public function imageSecond() {
        return $this->belongsTo(Image::class, 'image2');
    }
    public function imageThird() {
        return $this->belongsTo(Image::class, 'image3');
    }
    public function imageFourth() {
        return $this->belongsTo(Image::class, 'image4');
    }

    public function stock() {
        return $this->hasMany(Stock::class);
    }
}

