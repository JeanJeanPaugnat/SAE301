<?php
require_once "src/Controller/EntityController.php";
require_once "src/Repository/UserRepository.php" ;
require_once "src/Class/User.php";


class UserController extends EntityController {

    private UserRepository $users;

    public function __construct(){
        $this->users = new UserRepository();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

   
    protected function processGetRequest(HttpRequest $request) {
        $id = $request->getId();
        
        // vérifier si connecté via ?check
        if (isset($_GET['check'])) {
            if (isset($_SESSION['user_id'])) {
                $user = $this->users->find($_SESSION['user_id']);
                if ($user) {
                    return [
                        "logged" => true,
                        "id" => $user->getId(),
                        "name" => $user->getName(),
                        "lastName" => $user->getLastName(),
                        "email" => $user->getEmail(),
                    ];
                }
            }
            return ["logged" => false];
        }
        
        if ($id) {
            return $this->users->find($id);
        } else {
            return $this->users->findAll();
        }
    }

    protected function processPostRequest(HttpRequest $request) {
        $json = $request->getJson();
        $data = is_string($json) ? json_decode($json) : $json;

        // logout
        if (isset($_GET['logout'])) {
            session_destroy();
            return ["message" => "Déconnexion réussie"];
        }

        // login
        $isLogin = isset($_GET['login']);

        if ($isLogin) {
            if (!isset($data->email) || !isset($data->password)) {
                http_response_code(400);
                return ["error" => "Email et mot de passe requis"];
            }

            $user = $this->users->findByEmail($data->email);
            if (!$user || !password_verify($data->password, $user->getPassword())) {
                http_response_code(401);
                return ["error" => "Email ou mot de passe incorrect"];
            }

            // stocker l'utilisateur en session
            $_SESSION['user_id'] = $user->getId();

            return [
                "id" => $user->getId(),
                "name" => $user->getName(),
                "lastName" => $user->getLastName(),
                "email" => $user->getEmail(),
            ];
        } else {
            // création de compte
            $lastNameValue = $data->lastname ?? $data->lastName ?? null;

            if (!isset($data->name) || !$lastNameValue || !isset($data->email) || !isset($data->password)) {
                http_response_code(400);
                return ["error" => "Champs manquants pour la création du compte"];
            }

            $user = new User();
            $user->setName($data->name);
            $user->setLastName($lastNameValue);
            $user->setEmail($data->email);
            $user->setPassword(password_hash($data->password, PASSWORD_DEFAULT));

            $savedUser = $this->users->save($user);

            if ($savedUser) {
                // connecter automatiquement après inscription
                $_SESSION['user_id'] = $savedUser->getId();

                return [
                    "id" => $savedUser->getId(),
                    "name" => $savedUser->getName(),
                    "lastName" => $savedUser->getLastName(),
                    "email" => $savedUser->getEmail(),
                ];
            } else {
                http_response_code(500);
                return ["error" => "Erreur lors de la création du compte"];
            }
        }
    }

}
?>