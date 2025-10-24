<?php

require_once("src/Repository/EntityRepository.php");
require_once("src/Class/Product.php");


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
class ProductRepository extends EntityRepository {

    public function __construct(){
        // appel au constructeur de la classe mère (va ouvrir la connexion à la bdd)
        parent::__construct();
    }

    public function find($id): ?Product {
        // Requête pour récupérer le produit
        $requete = $this->cnx->prepare("SELECT * FROM Product WHERE id = :value");
        $requete->bindParam(':value', $id, PDO::PARAM_INT);
        $requete->execute();
        $answer = $requete->fetch(PDO::FETCH_OBJ);

        if ($answer == false) return null;

        // Création de l'objet Product
        $p = new Product($answer->id);
        $p->setName($answer->name);
        $p->setIdcategory($answer->category);
        $p->setPrice($answer->price);
        $p->setQuantity($answer->quantity ?? 0);


        $reqImg = $this->cnx->prepare("SELECT url, alt_text FROM Images WHERE product_id = :pid ORDER BY ordre ASC");
        $reqImg->bindParam(':pid', $id, PDO::PARAM_INT);
        $reqImg->execute();
        $images = $reqImg->fetchAll(PDO::FETCH_ASSOC);

        //pour garfer que les urls des img
        $imageUrls = array_map(fn($img) => $img['url'], $images);

        // mettre les images au produit
        $p->setImages($imageUrls);
        // on met la première image comme image princi
        $p->setImagePrincipale($imageUrls[0] ?? null);

        return $p;
    }


    public function findAll(): array {
        $sql = "SELECT p.id AS product_id, p.name AS product_name, p.category AS product_category, 
                p.price AS product_price, p.quantity AS product_quantity,
                i.url AS image_url, i.alt_text AS image_alt 
                FROM Product p 
                LEFT JOIN Images i ON p.id = i.product_id 
                ORDER BY p.id, i.ordre ASC";

        $requete = $this->cnx->prepare($sql);
        $requete->execute();
        $rows = $requete->fetchAll(PDO::FETCH_ASSOC);

        $res = [];
        $currentId = null;
        $currentProduct = null;

        // parcours de chaque lignes 
        foreach ($rows as $row) {
            // Si c’est un nouveau produit
            if ($row['product_id'] !== $currentId) {
                // Si un produit précédent est en cours → on le sauvegarde
                if ($currentProduct !== null) {
                    $res[] = $currentProduct;
                }

                // Création du nouveau produit
                $currentProduct = new Product($row['product_id']);
                $currentProduct->setName($row['product_name']);
                $currentProduct->setIdcategory($row['product_category']);
                $currentProduct->setPrice($row['product_price']);
                $currentProduct->setQuantity($row['product_quantity'] ?? 0);
                $currentProduct->setImages([]); // initialise la liste vide
                $currentProduct->setImagePrincipale(null); // initialise l'image principale à null
                $currentId = $row['product_id'];
            }

            // Si la ligne contient une image, on l’ajoute
            if (!empty($row['image_url'])) {
                $currentProduct->addImage($row['image_url']);
                if ($currentProduct->getImagePrincipale() === null) {
                    $currentProduct->setImagePrincipale($row['image_url']);
                }
            }
        }

        // N’oublie pas d’ajouter le dernier produit après la boucle
        if ($currentProduct !== null) {
            $res[] = $currentProduct;
        }

        return $res;
    }


    public function save($product){
        $requete = $this->cnx->prepare("insert into Product (name, category) values (:name, :idcategory)");
        $name = $product->getName();
        $idcat = $product->getIdcategory();
        $requete->bindParam(':name', $name );
        $requete->bindParam(':idcategory', $idcat);
        $answer = $requete->execute(); // an insert query returns true or false. $answer is a boolean.

        if ($answer){
            $id = $this->cnx->lastInsertId(); // retrieve the id of the last insert query
            $product->setId($id); // set the product id to its real value.
            return true;
        }
          
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