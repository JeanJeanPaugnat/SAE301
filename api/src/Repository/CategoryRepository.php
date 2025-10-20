<?php

require_once("src/Repository/EntityRepository.php");
require_once("src/Class/Category.php");


/**
 *  Classe ProductRepository
 * 
 *  Cette classe représente le "stock" de Product.
 *  Toutes les opérations sur les Product doivent se faire via cette classe 
 *  qui tient "synchro" la bdd en conséquence.
 * 
 *  La classe hérite de EntityRepository ce qui oblige à définir les méthodes  (find, findAll ... )
 *  Mais il est tout à fait possible d'ajouter des méthodes supplémentaires si
 *  c'est utile !
 *  
 */
class CategoryRepository extends EntityRepository {

    public function __construct(){
        // appel au constructeur de la classe mère (va ouvrir la connexion à la bdd)
        parent::__construct();
    }

    public function find($id): ?Category {
        $stmt = $this->cnx->prepare("select * from Category WHERE id = :value");
        $stmt->bindParam(':value', $id);
        $stmt->execute();
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$obj) return null;

        $category = new Category($obj->id);
        $category->setName($obj->name);

        return $category;
    }


    public function findAll(): array {
        $requete = $this->cnx->prepare("select * from Category");
        $requete->execute();
        $answer = $requete->fetchAll(PDO::FETCH_OBJ);

        $res = [];
        foreach ($answer as $obj) {
            $category = new Category($obj->id);
            $category->setName($obj->name);
            $res[] = $category;
        }
        return $res;
    }

    //ecris une fonction findByCategory
    public function findByCategory($categoryName): array {
        $sql = "
            SELECT 
                p.id AS product_id,
                p.name AS product_name,
                p.category AS product_category,
                p.price AS product_price,
                i.url AS image_url,
                i.alt_text AS image_alt
            FROM Product p
            JOIN Category c ON p.category = c.id
            LEFT JOIN Images i ON p.id = i.product_id
            WHERE c.name = :value
            ORDER BY p.id, i.ordre ASC
        ";

        $stmt = $this->cnx->prepare($sql);
        $stmt->bindParam(':value', $categoryName);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $res = [];
        $currentId = null;
        $currentProduct = null;

        foreach ($rows as $row) {
            // Nouveau produit détecté
            if ($row['product_id'] !== $currentId) {
                // Sauvegarde du produit précédent
                if ($currentProduct !== null) {
                    $res[] = $currentProduct;
                }

                // Création d’un nouveau produit
                $currentProduct = new Product($row['product_id']);
                $currentProduct->setName($row['product_name']);
                $currentProduct->setIdcategory($row['product_category']);
                $currentProduct->setPrice($row['product_price']);
                $currentProduct->setImages([]); // Initialise une liste d'images vide
                $currentId = $row['product_id'];
            }

            // Ajout d'une image si elle existe
            if (!empty($row['image_url'])) {
                $currentProduct->addImage($row['image_url']);
            }
        }

        // Ajouter le dernier produit à la liste
        if ($currentProduct !== null) {
            $res[] = $currentProduct;
        }

        return $res;
    }


    

    public function save($product){
        // Not implemented ! TODO when needed !
        return false;
    }

    public function delete($id){
        // Not implemented ! TODO when needed !
        return false;
    }

    public function update($product){
        // Not implemented ! TODO when needed !
        return false;
    }

   
    
}