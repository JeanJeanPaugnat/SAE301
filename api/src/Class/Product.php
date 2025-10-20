<?php

require_once ('Entity.php');

class Product extends Entity implements JsonSerializable {
    private int $id;
    private ?string $name = null;
    private ?int $idcategory = null;
    private ?float $price = null;

    // 🆕 Propriété pour l'image principale
    private ?string $imagePrincipale = null;

    // 🆕 Liste des images liées à ce produit
    private array $images = []; // contiendra par exemple une liste d'objets Image ou de chaînes (URL)

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
            "imagePrincipale" => $this->imagePrincipale,
            // 🆕 On renvoie la liste des images dans le JSON
            "images" => $this->images,
        ];
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

    // 🆕 GETTER pour l'image principale
    public function getImagePrincipale(): ?string {
        return $this->imagePrincipale;
    }

    // 🆕 SETTER pour l'image principale (autorise null)
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
