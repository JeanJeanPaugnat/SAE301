<?php
require_once "src/Controller/EntityController.php";
require_once "src/Repository/OrderRepository.php";
require_once "src/Class/Order.php";

class OrderController extends EntityController {

    private OrderRepository $orders;

    public function __construct(){
        $this->orders = new OrderRepository();
    }

    protected function processGetRequest(HttpRequest $request) {
        $id = $request->getId("id");
        $userId = $request->getParam("user");
        
        if ($id){
            // URI is .../orders/{id}
            $order = $this->orders->find($id);
            return $order == null ? false : $order;
        }
        else if ($userId) {
            // URI is .../orders?user={userId}
            return $this->orders->findByUserId($userId);
        }
        else{
            // URI is .../orders
            return $this->orders->findAll();
        }
    }

    protected function processPostRequest(HttpRequest $request) {
        $json = $request->getJson();
        $obj = json_decode($json);
        
        // Validation des données
        if (!isset($obj->userId) || !isset($obj->totalAmount) || !isset($obj->items)) {
            return false;
        }

        $order = new Order(0);
        $order->setUserId($obj->userId);
        $order->setTotalAmount($obj->totalAmount);
        
        // Ajouter les items à la commande
        foreach ($obj->items as $item) {
            $order->addItem([
                'productId' => $item->productId,
                'productName' => $item->productName,
                'quantity' => $item->quantity,
                'unitPrice' => $item->unitPrice,
                'totalPrice' => $item->totalPrice
            ]);
        }
        
        $ok = $this->orders->createOrder($order); 
        return $ok ? $order : false;
    }
}
