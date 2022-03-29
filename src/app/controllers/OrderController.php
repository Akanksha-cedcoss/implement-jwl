<?php

use Phalcon\Mvc\Controller;
use App\Components\NotificationsAware;

class OrderController extends Controller
{
    public function indexAction()
    {
        $query = $this
            ->modelsManager
            ->createQuery(
                'SELECT Products.name as product_name, Orders.* FROM Orders join Products on Orders.product_id=Products.product_id'
            );

        $this->view->orders = $query->execute();
    }
    public function addAction()
    {
        $this->view->products = Products::find(['columns' => 'name, product_id']);
        // die(json_encode(Products::find(['columns' => 'name, product_id'])));
        if ($_POST) {
            $escaper = new \App\Components\MyEscaper();
            $name = $escaper->sanitize($this->request->getPost('customer_name'));
            $address = $escaper->sanitize($this->request->getPost('address'));
            $zip = $escaper->sanitize($this->request->getPost('zipcode'));
            $product = $escaper->sanitize($this->request->getPost('product'));
            $quantity = $escaper->sanitize($this->request->getPost('quantity'));
            $component = new NotificationsAware();
            $name = $component->defaultZipCode($zip);
            $order = new Orders();
            try {
                $order->assign(
                    array(
                        'customer_name' => $name,
                        'address' => $address,
                        'zipcode' => $zip,
                        'product_id' => $product,
                        'quantity' => $quantity
                    ),
                    [
                        'customer_name',
                        'address',
                        'zipcode',
                        'product_id',
                        'quantity'
                    ]
                );
                if ($order->save()) {
                    $this->flash->success("Order Added successFully");
                    return;
                } else {
                    $this->flash->error("One or More field is empty.");
                }
            } catch (Exception $e) {
                $this->flash->error($e);
            }
        }
    }
}
