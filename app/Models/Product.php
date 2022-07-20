<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function users()
    {
        return $this->belongsToMany(User::class,'carts')
                        ->withPivot(['id', 'quantity']);
    }

    public function scopeAvailableItems($query) {
        $stocks = DB::table('t_stocks')
        ->select('product_id', DB::raw('sum(quantity) as quantity'))
        ->groupBy('product_id')
        ->having('quantity', '>=', 1);


        // Product::availableItems() という形で使うので、DB::table('products')という箇所は不要
        return $query->
        joinSub($stocks, 'stock', function($join){
            $join->on('products.id', '=', 'stock.product_id'); })
        ->join('shops', 'products.shop_id', '=', 'shops.id')
        ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
        ->join('images as image1', 'products.image1', '=', 'image1.id')
        // ->join('images as image2', 'products.image2', '=', 'image2.id')
        // ->join('images as image3', 'products.image3', '=', 'image3.id')
        // ->join('images as image4', 'products.image4', '=', 'image4.id')
        // 商品一覧としてはimageは1つで良かった。
        ->where('shops.is_selling', true)
        ->where('products.is_selling', true)
        ->select('products.id', 'products.name', 'products.price', 'products.sort_order', 'products.information', 'secondary_categories.name as category', 'image1.filename as filename');
    }

    public function scopeSortOrder($query, $sortOrder){
        if($sortOrder === null || $sortOrder === \Constant::SORT_ORDER['recommend']){
            return $query->orderBy('sort_order', 'asc');
                                    // shopが自由に数字を当てることができる 小さい順に並べる
        }
        if($sortOrder === \Constant::SORT_ORDER['higherPrice']){
            return $query->orderBy('price', 'desc');    // 価格の高い順
        }
        if($sortOrder === \Constant::SORT_ORDER['lowerPrice']){
            return $query->orderBy('price', 'asc');     // 価格の低い順
        }
        if($sortOrder === \Constant::SORT_ORDER['later']){
            return $query->orderBy('products.created_at', 'desc');  // 新しい順
        }
        if($sortOrder === \Constant::SORT_ORDER['older']){
            return $query->orderBy('products.created_at', 'asc');   // 古い順
        }
    }
}

