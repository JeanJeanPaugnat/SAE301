<?php

require_once("src/Repository/EntityRepository.php");
require_once("src/Class/User.php");


class UserRepository extends EntityRepository {

    public function __construct(){
        // appel au constructeur de la classe mère (va ouvrir la connexion à la bdd)
        parent::__construct();
    }
    
    public function find($id): ?User {
        try {
            $requete = $this->cnx->prepare("SELECT * FROM User WHERE id = :value");
            $requete->bindValue(':value', $id, PDO::PARAM_INT);
            $requete->execute();
            $answer = $requete->fetch(PDO::FETCH_OBJ);
            if ($answer === false) return null;
            $p = new User($answer->id);
            $p->setName($answer->name);
            $p->setLastName($answer->lastName);
            $p->setEmail($answer->email);
            // Ne pas exposer le mot de passe inutilement
            return $p;
        } catch (PDOException $e) {
            // log si besoin
            return null;
        }
    }

    public function findAll(): array {
        $res = [];
        try {
            $requete = $this->cnx->prepare("SELECT * FROM User");
            $requete->execute();
            $answer = $requete->fetchAll(PDO::FETCH_OBJ);
            foreach($answer as $obj){
                $p = new User($obj->id);
                $p->setName($obj->name);
                $p->setLastName($obj->lastName);
                $p->setEmail($obj->email);
                $res[] = $p;
            }
        } catch (PDOException $e) {
            // loger si besoin
        }
        return $res;
    }

//     public function findByEmail(string $email): ?User {
//     $stmt = $this->cnx->prepare("SELECT * FROM user WHERE email = :email");
//     $stmt->bindParam(":email", $email);
//     $stmt->execute();

//     $row = $stmt->fetch(PDO::FETCH_ASSOC);
//     if ($row) {
//         $user = new User();
//         $user->setId($row['id']);
//         $user->setName($row['name']);
//         $user->setEmail($row['email']);
//         $user->setPassword($row['password']);
//         $user->setAdmin((bool)$row['is_admin']);
//         return $user;
//     }

//     return null;
// }


    public function save($entity): ?User {
        if (!($entity instanceof User)) {
            throw new InvalidArgumentException("L'entité doit être de type User");
        }

        try {
            $stmt = $this->cnx->prepare(
                "INSERT INTO User (name, lastName, email, password) VALUES (:name, :lastName, :email, :password)"
            );

            $name = $entity->getName();
            $lastName = $entity->getLastName();
            $email = $entity->getEmail();
            // Le controller hash le mot de passe ; ici on stocke tel quel (déjà hashé)
            $password = $entity->getPassword();

            $stmt->bindValue(":name", $name, PDO::PARAM_STR);
            $stmt->bindValue(":lastName", $lastName, PDO::PARAM_STR);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":password", $password, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                return null;
            }

            $id = $this->cnx->lastInsertId();
            $entity->setId($id);
            return $entity;
        } catch (PDOException $e) {
            // si email unique, gérer duplicate key ici
            return null;
        }
    }

    public function findByEmail($email): ?User {
        $stmt = $this->cnx->prepare("SELECT * FROM User WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $user = new User();
            $user->setId($row['id']);
            $user->setName($row['name']);
            $user->setLastName($row['lastName']);
            $user->setEmail($row['email']);
            $user->setPassword($row['password']);
            return $user;
        }

        return null;
    }




    public function update($object) {
        // Implémente ce que tu veux ou laisse vide si tu ne l’utilises pas
    }

    public function delete($id) {
        // Implémente ce que tu veux ou laisse vide si tu ne l’utilises pas
    }

}