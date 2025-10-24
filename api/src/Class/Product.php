<?php

require_once ('Entity.php');

class Product extends Entity implements JsonSerializable {
    private int $id;
    private ?string $name = null;
    private ?int $idcategory = null;
    private ?float $price = null;
    private ?int $quantity = null;
    private ?string $imagePrincipale = null;
    private array $images = [];

    public function __construct(int $id){
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "category" => $this->idcategory,
            "price" => number_format($this->price, 2, ',', ' '),
            "quantity" => $this->quantity,
            "stockStatus" => $this->getStockStatus(),
            "imagePrincipale" => $this->imagePrincipale,
            "images" => $this->images,
        ];
    }

    public function getStockStatus(): string {
        if ($this->quantity === null || $this->quantity <= 0) {
            return "Out of stock";
        } elseif ($this->quantity <= 15) {
            return "Running low";
        } else {
            return "In stock";
        }
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): ?float {
        return $this->price;
    }

    public function setPrice(float $price): self {
        $this->price = $price;
        return $this;
    }

    public function getQuantity(): ?int {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self {
        $this->quantity = $quantity;
        return $this;
    }

    public function getImagePrincipale(): ?string {
        return $this->imagePrincipale;
    }

    public function setImagePrincipale(?string $imagePrincipale): self {
        $this->imagePrincipale = $imagePrincipale;
        return $this;
    }

    public function getIdcategory(): ?int {
        return $this->idcategory;
    }

    public function setIdcategory(int $idcategory): self {
        $this->idcategory = $idcategory;
        return $this;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    // 🆕 GETTER des images
    public function getImages(): array {
        return $this->images;
    }

    // 🆕 SETTER des images (liste complète)
    public function setImages(array $images): self {
        $this->images = $images;
        return $this;
    }

    // 🆕 Ajout d'une seule image
    public function addImage(mixed $image): self {
        $this->images[] = $image;
        return $this;
    }
}
