<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ProductType;
use App\Models\Store;
use App\Models\StoreCreate;
use App\Models\StoreOperationType;
use App\Models\StoreOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    protected Store $store;
    protected ProductType $productType;
    protected Client $client;
    protected StoreOut $storeOut;
    protected StoreOperationType $storeOperationType;
    protected StoreCreate $storeCreate;

    /**
     * @param Store $store
     * @param ProductType $productType
     * @param Client $client
     * @param StoreOut $storeOut
     * @param StoreOperationType $storeOperationType
     * @param StoreCreate $storeCreate
     */
    public function __construct(Store $store, ProductType $productType, Client $client, StoreOut $storeOut, StoreOperationType $storeOperationType, StoreCreate $storeCreate)
    {
        $this->store = $store;
        $this->productType = $productType;
        $this->client = $client;
        $this->storeOut = $storeOut;
        $this->storeOperationType = $storeOperationType;
        $this->storeCreate = $storeCreate;
        $this->middleware('auth:api');
    }


    public function getAll(Request $request)
    {
        $club_id = $request->club_id;
        $store = $this->store->getAllStore($club_id);

        $productType = $this->productType->GetProductType($club_id);
        $outData = array($store, $productType);
        return($outData);
    }
    public function find(Request $request)
    {
        $searchText = $request->search;
        $club_id = $request->club_id;
        return $this->store->storeFind($searchText, $club_id);
    }
    public function searchClient(Request $request)
    {
        $login = $request->login;
        return $this->client->getByLogin($login);
    }
    public function addEdit(Request $request)
    {
        $admin_id = $request->admin_id;
        $product = $request->product;
        $price = $request->price ?? 0;
        $num = $request->num ?? 0;
        $barcode = $request->barcode;
        $discount = $request->discount ?? false;
        $types = $request->types ?? 0;
        $club_id = $request->club_id;
        $icon = $request->icon;
        $shell_show = $request->showInClient;
        $nalog = $request->nalog;
        $id = $request->storeid;
        if ($id == null) {
            $store = $this->store->addStore($admin_id, $product, $price, $num, $barcode, $discount, $types, $club_id, $icon, $shell_show, $nalog);
        }
        else {
            $store = $this->store->updateStore($id, $admin_id, $product, $price, $num, $barcode, $discount, $types, $icon, $shell_show, $nalog);
        }
        return $store;
    }
    public function storeOutSave(Request $request)
    {
        $position = $request->position;
        $num = $request->num;
        $description = $request->description;
        $user_id = $request->user_id;
        $club_id = $request->club_id;
        $type_id = $request->type_id;

        $result = $this->storeOut->saveStore($position, $num, $description, $user_id, $club_id, $type_id);
        if ($result)
            return 'OK';
        else
            return 'Error';
    }
    public function addToWarehouse(Request $request)
    {
        $product = $request->product;
        $club_id = $request->club_id;
        $user_id = $request->user_id;
        $store_id = $request->store_id;


        $product = json_decode($product);
        $this->store->updateProductInStore($store_id, $product);

        $this->storeCreate->addToStore($product, $club_id, $user_id, $store_id);
        return array('status' => 'OK');
    }
    public function getStoreOparationType()
    {
        $types = $this->storeOperationType->getAll();
        return $types;
    }
}
