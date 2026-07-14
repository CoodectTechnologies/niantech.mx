<?php

namespace App\Http\Controllers\Admin\Test;

use App\Http\Controllers\Controller;
use App\Services\Integrations\Odoo\Address\AddressService;
use App\Services\Integrations\Odoo\Customer\CustomerService;
use App\Services\Integrations\Odoo\Order\OrderService;
use App\Services\Integrations\Odoo\Product\ProductService;
use App\Services\Integrations\Odoo\Product\WarehouseService;
use App\Services\Integrations\VadetoBrands\Product\CloudResourceService;
use App\Services\Integrations\VadetoBrands\Product\ImageService;
use App\Services\Integrations\VadetoBrands\Product\ProductService as ProductProductService;
use App\Services\Synchronizers\Catalog\ProductController;
use App\Services\User\RegistrationService;
use Exception;

class TestController extends Controller
{
    public function __construct() {}
    public function index() {
        // BRANDS PRODUCTOS
        // $productService = new ProductProductService();
        // $res = $productService->getAllContent();
        // dd($res);

        // // BRANDS CLOUD RESOURCES
        // $cloudResourceService = new CloudResourceService();
        // $res = $cloudResourceService->find('yeyian', 'es', 'YMO2001');
        // dd($res);

        // BRANDS IMAGES
        // $cloudResourceService = new ImageService();
        // $res = $cloudResourceService->getAll('yeyian', 'es_MX', 'YMC-VA34-01');
        // dd($res);

        // CORRER EL SYNC DE PRODUCTOS
        // $productController = new ProductController();
        // $productController->save();

        // TESTEAR EL SERVICIO DE PRODUCTOS find
        // $productService = new ProductService();
        // $result = $productService->find(1);
        // dd($result);

        // TESTEAR EL SERVICIO DE PRODUCTOS getALL
        // $productService = new ProductService();
        // $params = ['page' => 1, 'per_page' => 200];
        // foreach($productService->getAll($params) as $products):
        //     foreach($products as $product):
        //         dd($product);
        //     endforeach;
        // endforeach;

        // TESTEAR EL SERVICIO DE ALMACENES getALL
        // $result = [];
        // $params = ['page' => 1, 'per_page' => 200];
        // $warehouseService = new WarehouseService();
        // foreach($warehouseService->getAll($params) as $warehouses):
        //     $result = $warehouses;
        //     break;
        //     // foreach($warehouses as $warehouse):
        //     //     dd($warehouse);
        //     // endforeach;
        // endforeach;

        // TESTEAR EL SERVICIO DE ORDENES find
        // $result = [];
        // $params = ['page' => 1, 'per_page' => 200];
        // $domain = [];
        // $domain = [['state', 'in', ['sale', 'done']]];
        // $orderService = new OrderService();
        // $order = $orderService->find(1);
        // dd($order);

        // // TESTEAR EL SERVICIO DE ORDENES getALL
        // $result = [];
        // $params = ['page' => 1, 'per_page' => 200];
        // $domain = [];
        // $domain = [['state', 'in', ['sale', 'done']]];
        // $orderService = new OrderService();
        // foreach($orderService->getAll(domain: $domain, params: $params) as $orders):
        //     foreach($orders as $order):
        //         dd($order);
        //     endforeach;
        // endforeach;

        // TESTEAR CUSTOMER getAll
        // $result = [];
        // $params = ['page' => 1, 'per_page' => 200];
        // $customerService = new CustomerService();
        // foreach($customerService->getAll(params: $params) as $customers):
        //     $result = $customers;
        //     dd($result);
        //     break;
        // endforeach;

        // TESTEAR CUSTOMER find
        // $result = [];
        // $customerService = new CustomerService();
        // $res = $customerService->find(57);
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
        // $customerService = new CustomerService();
        // $result = $customerService->findByEmail('deco_addict@yourcompany.example.com');
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

        return view('admin.test.index');
    }
}
