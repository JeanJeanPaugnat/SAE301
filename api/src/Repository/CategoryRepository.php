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