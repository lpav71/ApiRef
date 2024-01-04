<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;
    protected $table = 'product_type';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function GetProductType(mixed $club_id)
    {
        return ProductType::where('club_id', $club_id)->get();
    }
}
