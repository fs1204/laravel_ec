<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryCategory extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'shop_id',
    //     'secondary_category_id',
    //     'image1',
    // ];

    public function primary() {
        return $this->belongsTo(PrimaryCategory::class);
        // use App\Models\PrimaryCategory とする必要はない
    }
}
