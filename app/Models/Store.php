<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $table = 'store';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getAllStore(mixed $club_id)
    {
        return Store::select('store.id as storeid', '*')
            ->where('store.club_id', $club_id)
            ->where('product_type.club_id', $club_id)
            ->join('product_type', function ($join) {
                $join->on('store.types', 'product_type.types');
            })
            ->orderBy('storeid')
            ->get();
    }

    /**
     * @param mixed $searchText
     * @param mixed $club_id
     * @return mixed
     */
    public function storeFind(mixed $searchText, mixed $club_id)
    {
        return Store::where('product', 'ILIKE', '%' . $searchText . '%')
            ->where('club_id', $club_id)
            ->orWhere('product_param', 'ILIKE', '%' . $searchText . '%')
            ->get()
            ->toArray();
    }

    /**
     * @param mixed $admin_id
     * @param mixed $product
     * @param mixed $price
     * @param mixed $num
     * @param mixed $barcode
     * @param mixed $discount
     * @param mixed $types
     * @param mixed $club_id
     * @param mixed $icon
     * @param mixed $shell_show
     * @param mixed $nalog
     * @return Store
     */
    public function addStore(mixed $admin_id, mixed $product, mixed $price, mixed $num, mixed $barcode, mixed $discount, mixed $types, mixed $club_id, mixed $icon, mixed $shell_show, mixed $nalog): Store
    {
        $store = new Store();
        $store->admin_id = $admin_id;
        $store->product = $product;
        $store->price = $price;
        $store->num = $num;
        $store->barcode = $barcode;
        $store->discount = $discount;
        $store->types = $types;
        $store->club_id = $club_id;
        $store->icon = $icon;
        $store->shell_show = $shell_show;
        $store->nalog = $nalog;
        $store->save();
        return $store;
    }

    /**
     * @param mixed $id
     * @param mixed $admin_id
     * @param mixed $product
     * @param mixed $price
     * @param mixed $num
     * @param mixed $barcode
     * @param mixed $discount
     * @param mixed $types
     * @param mixed $icon
     * @param mixed $shell_show
     * @param mixed $nalog
     * @return mixed
     */
    public function updateStore(mixed $id, mixed $admin_id, mixed $product, mixed $price, mixed $num, mixed $barcode, mixed $discount, mixed $types, mixed $icon, mixed $shell_show, mixed $nalog)
    {
        $store = Store::find($id);
        $store->admin_id = $admin_id;
        $store->product = $product;
        $store->price = $price;
        $store->num = $num;
        $store->barcode = $barcode;
        $store->discount = $discount;
        $store->types = $types;
        $store->icon = $icon;
        $store->shell_show = $shell_show;
        $store->nalog = $nalog;
        $store->save();
        return $store;
    }

    /**
     * @param mixed $store_id
     * @param mixed $product
     * @return void
     */
    public function updateProductInStore(mixed $store_id, mixed $product): void
    {
        $store = Store::find($store_id);
        $store->num += $product->qty;
        $store->save();
    }
}
