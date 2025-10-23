<?php

require_once "src/Class/Order.php";
require_once "src/Repository/EntityRepository.php";

class OrderRepository extends EntityRepository {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Créer une nouvelle commande avec ses items
     */
    public function createOrder(Order $order): bool {
        try {
            // Commencer une transaction
            $this->cnx->beginTransaction();
            
            // 1. Insérer la commande dans la table Orders
            $stmt = $this->cnx->prepare(
                "INSERT INTO Orders (user_id, created_at) VALUES (:user_id, :created_at)"
            );
            
            $stmt->bindValue(':user_id', $order->getUserId(), PDO::PARAM_INT);
            $stmt->bindValue(':created_at', $order->getCreatedAt(), PDO::PARAM_STR);
            
            $stmt->execute();
            
            // Récupérer l'ID de la commande créée
            $orderId = $this->cnx->lastInsertId();
            $order->setId($orderId);
            
            // 2. Insérer les items de la commande dans OrderItems
            $stmtItem = $this->cnx->prepare(
                "INSERT INTO OrderItems (order_id, product_id, product_name, quantity, unit_price, total_price) 
                 VALUES (:order_id, :product_id, :product_name, :quantity, :unit_price, :total_price)"
            );
            
            foreach ($order->getItems() as $item) {
                $stmtItem->bindValue(':order_id', $orderId, PDO::PARAM_INT);
                $stmtItem->bindValue(':product_id', $item['productId'], PDO::PARAM_INT);
                $stmtItem->bindValue(':product_name', $item['productName'], PDO::PARAM_STR);
                $stmtItem->bindValue(':quantity', $item['quantity'], PDO::PARAM_INT);
                $stmtItem->bindValue(':unit_price', $item['unitPrice'], PDO::PARAM_STR);
                $stmtItem->bindValue(':total_price', $item['totalPrice'], PDO::PARAM_STR);
                $stmtItem->execute();
            }
            
            // Valider la transaction
            $this->cnx->commit();
            return true;
            
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $this->cnx->rollBack();
            error_log("Error creating order: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer une commande par son ID avec ses items
     */
    public function find($id): ?Order {
        try {
            // Récupérer la commande
            $stmt = $this->cnx->prepare(
                "SELECT * FROM Orders WHERE id = :id"
            );
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$data) {
                return null;
            }
            
            $order = new Order($data['id']);
            $order->setUserId($data['user_id']);
            $order->setCreatedAt($data['created_at']);
            
            // Récupérer les items de la commande
            $stmtItems = $this->cnx->prepare(
                "SELECT * FROM OrderItems WHERE order_id = :order_id"
            );
            $stmtItems->bindValue(':order_id', $id, PDO::PARAM_INT);
            $stmtItems->execute();
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
            
            $totalAmount = 0;
            foreach ($items as $item) {
                $order->addItem([
                    'productId' => $item['product_id'],
                    'productName' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unitPrice' => floatval($item['unit_price']),
                    'totalPrice' => floatval($item['total_price'])
                ]);
                $totalAmount += floatval($item['total_price']);
            }
            
            $order->setTotalAmount($totalAmount);
            
            return $order;
            
        } catch (PDOException $e) {
            error_log("Error finding order: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer toutes les commandes
     */
    public function findAll(): array {
        try {
            $stmt = $this->cnx->prepare("SELECT * FROM Orders ORDER BY created_at DESC");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $orders = [];
            foreach ($data as $row) {
                $order = $this->find($row['id']);
                if ($order) {
                    $orders[] = $order;
                }
            }
            
            return $orders;
            
        } catch (PDOException $e) {
            error_log("Error finding all orders: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer toutes les commandes d'un utilisateur
     */
    public function findByUserId(int $userId): array {
        try {
            $stmt = $this->cnx->prepare(
                "SELECT * FROM Orders WHERE user_id = :user_id ORDER BY created_at DESC"
            );
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $orders = [];
            foreach ($data as $row) {
                $order = $this->find($row['id']);
                if ($order) {
                    $orders[] = $order;
                }
            }
            
            return $orders;
            
        } catch (PDOException $e) {
            error_log("Error finding orders by user: " . $e->getMessage());
            return [];
        }
    }

    public function save($product){
        // Not implemented ! TODO when needed !
        return false;
    }

    public function delete($id){
        // Not implemented ! TODO when needed !
        return false;
    }

    public function update($product, $id){
        // Not implemented ! TODO when needed !
        return false;
    }
}