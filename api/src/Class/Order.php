<?php
require_once "Entity.php";

class Order extends Entity implements JsonSerializable {
    private int $id;
    private int $userId;
    private float $totalAmount;
    private string $createdAt;
    private array $items = []; // Pour stocker les items de la commande

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'totalAmount' => $this->getTotalAmount(),
            'createdAt' => $this->getCreatedAt(),
            'items' => $this->getItems()
        ];
    }

    public function __construct(int $id){
         $this->id = $id;
        $this->createdAt = date('Y-m-d H:i:s');
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function getTotalAmount(): float {
        return $this->totalAmount;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function getItems(): array {
        return $this->items;
    }

    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setUserId(int $userId): void {
        $this->userId = $userId;
    }

    public function setTotalAmount(float $totalAmount): void {
        $this->totalAmount = $totalAmount;
    }

    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function setItems(array $items): void {
        $this->items = $items;
    }

    public function addItem(array $item): void {
        $this->items[] = $item;
    }


}