<?php

require_once ('Entity.php');

/**
 *  Class Product
 * 
 *  Représente un produit avec uniquement 3 propriétés (id, name, category)
 * 
 *  Implémente l'interface JsonSerializable 
 *  qui oblige à définir une méthode jsonSerialize. Cette méthode permet de dire comment les objets
 *  de la classe Product doivent être converti en JSON. Voire la méthode pour plus de détails.
 */
class Category extends Entity {
    private int $id; // id de la catégorie
    private ?string $name = null; // nom de la catégorie (nullable)
    private ?string $image = null; // URL de l'image unique (nullable)

    public function __construct(int $id) {
        $this->id = $id;
    }

    /**
     * Get the value of id
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return self
     */
    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return self
     */
    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of image
     */
    public function getImage(): ?string {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @param string|null $image URL de l'image ou null
     * @return self
     */
    public function setImage(?string $image): self {
        $this->image = $image;
        return $this;
    }

    /**
     * Define how to convert/serialize a Category to a JSON format
     * This method will be automatically invoked by json_encode when applied to a Category.
     */
    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "image" => $this->image,
        ];
    }
}