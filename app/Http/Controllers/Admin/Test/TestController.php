<?php

namespace App\Http\Controllers\Admin\Test;

use App\Http\Controllers\Controller;
use App\Integrations\Odoo\Resources\Catalog\ProductResource;
use App\Integrations\Odoo\Resources\Order\OrderResource;
use App\Integrations\VadetoBrands\Resources\Catalog\CloudResource;
use App\Integrations\VadetoBrands\Resources\Catalog\ImageResource;
use App\Integrations\VadetoBrands\Resources\Catalog\ProductResource as VadetoBrandsProductResource;
use App\Models\Order;

class TestController extends Controller
{
    public function __construct() {}
    public function index() {
        // $orderResource = new OrderResource();
        // $params = ['page' => 1, 'per_page' => 200];
        // foreach($orderResource->getAll(params: $params) as $orders):
        //     foreach($orders as $order):
        //         dd($order);
        //     endforeach;
        // endforeach;

        // BRANDS PRODUCTOS
        // $productResource = new VadetoBrandsProductResource();
        // $res = $productResource->getAllContent();
        // dd($res);

        // // BRANDS CLOUD RESOURCES
        // $cloudResourceResource = new CloudResource();
        // $res = $cloudResourceResource->find('yeyian', 'es', 'YMO2001');
        // dd($res);

        // BRANDS IMAGES
        // $cloudResourceResource = new ImageResource();
        // $res = $cloudResourceResource->getAll('yeyian', 'es_MX', 'YMC-VA34-01');
        // dd($res);

        // CORRER EL SYNC DE PRODUCTOS
        // $productController = new ProductController();
        // $productController->save();

        // TESTEAR EL SERVICIO DE PRODUCTOS find
        // $productResource = new ProductResource();
        // $result = $productResource->find(1);
        // dd($result);

        // TESTEAR EL SERVICIO DE PRODUCTOS getALL
        // $productResource = new ProductResource();
        // $params = ['page' => 1, 'per_page' => 200];
        // foreach($productResource->getAll($params) as $products):
        //     foreach($products as $product):
        //         dd($product);
        //     endforeach;
        // endforeach;

        // TESTEAR EL SERVICIO DE ALMACENES getALL
        // $result = [];
        // $params = ['page' => 1, 'per_page' => 200];
        // $warehouseResource = new WarehouseResource();
        // foreach($warehouseResource->getAll($params) as $warehouses):
        //     $result = $warehouses;
        //     break;
        //     // foreach($warehouses as $warehouse):
        //     //     dd($warehouse);
        //     // endforeach;
        // endforeach;
        // dd($result);

        // TESTEAR EL SERVICIO DE ORDENES find
        // $result = [];
        // $params = ['page' => 1, 'per_page' => 200];
        // $domain = [];
        // $domain = [['state', 'in', ['sale', 'done']]];
        // $orderResource = new OrderResource();
        // $order = $orderResource->find(1);
        // dd($order);

        // // TESTEAR EL SERVICIO DE ORDENES getALL
        // $result = [];
        // $params = ['page' => 1, 'per_page' => 200];
        // $domain = [];
        // $domain = [['state', 'in', ['sale', 'done']]];
        // $orderResource = new OrderResource();
        // foreach($orderResource->getAll(domain: $domain, params: $params) as $orders):
        //     foreach($orders as $order):
        //         dd($order);
        //     endforeach;
        // endforeach;

        // TESTEAR CUSTOMER getAll
        // $result = [];
        // $params = ['page' => 1, 'per_page' => 200];
        // $customerResource = new CustomerResource();
        // foreach($customerResource->getAll(params: $params) as $customers):
        //     $result = $customers;
        //     dd($result);
        //     break;
        // endforeach;

        // TESTEAR CUSTOMER find
        // $result = [];
        // $customerResource = new CustomerResource();
        // $res = $customerResource->find(57);
        // dd($res);

        // TESTAR NEW CUSTOMER
        // try{
        //     $data = [
        //         'name' => 'Test User',
        //         'email' => 'testuser@example.com',
        //     ];
        //     $registrationService = new RegistrationService();
        //     $result = $registrationService->register($data);
        //     dd($result);
        // }catch(Exception $e){
        //     dd($e->getMessage());
        // }

        // TESTAER EL SERVICIO DE CUSTOMER findByEmail
        // $customerResource = new CustomerResource();
        // $result = $customerResource->findByEmail('deco_addict@yourcompany.example.com');
        // dd($result);

        // TESTAER EL SERVICIO DE ADDRESSES
        // $addressService = new AddressService();
        // $result = [];
        // $params = ['page' => 1, 'per_page' => 200];
        // $domain = ['|', ['id', '=', 9], ['parent_id', '=', 9]];
        // foreach($addressService->getAll(domain: $domain, params: $params) as $addresses):
        //     $result = $addresses;
        //     dd($result);
        //     break;
        // endforeach;

        // $order = Order::find(1);
        // $orderResource = new OrderResource();
        // $result = $orderResource->save($order);
        // dd($result);

        return view('admin.test.index');
    }
}
