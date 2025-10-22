<?php
require_once "src/Controller/EntityController.php";
require_once "src/Repository/UserRepository.php" ;
require_once "src/Class/User.php";


class UserController extends EntityController {

    private UserRepository $users;

    public function __construct(){
        $this->users = new UserRepository();
            session_start();

    }

   
    protected function processGetRequest(HttpRequest $request) {
        $id = $request->getId();
        
        //check
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

        //logout
        if (isset($_GET['logout'])) {
            session_destroy();
            return ["message" => "Déconnexion réussie"];
        }

        //login
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

            //stocke utili en session
            $_SESSION['user_id'] = $user->getId();

            return [
                "id" => $user->getId(),
                "name" => $user->getName(),
                "lastName" => $user->getLastName(),
                "email" => $user->getEmail(),
            ];
        } else {
            //je cré uncompte
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

    protected function processPatchRequest(HttpRequest $request) {
        $json = $request->getJson();
        $data = is_string($json) ? json_decode($json) : $json;

        // Récupérer l'ID depuis le paramètre ?update
        $userId = isset($_GET['update']) ? intval($_GET['update']) : null;

        // Récupérer l'utilisateur existant
        $user = $this->users->find($userId);

        if (!$user) {
            http_response_code(404);
            return ["error" => "Utilisateur non trouvé"];
        }

        // Vérification obligatoire de l'ancien mot de passe
        if (!isset($data->oldPassword) || empty($data->oldPassword)) {
            http_response_code(400);
            return ["error" => "L'ancien mot de passe est requis pour effectuer des modifications"];
        }

        // Vérifier que l'ancien mot de passe est correct
        if (!password_verify($data->oldPassword, $user->getPassword())) {
            http_response_code(401);
            return ["error" => "Mot de passe incorrect"];
        }

        // Mise à jour des champs fournis
        if (isset($data->name)) {
            $user->setName($data->name);
        }

        $lastNameValue = $data->lastname ?? $data->lastName ?? null;
        if ($lastNameValue) {
            $user->setLastName($lastNameValue);
        }

        if (isset($data->email)) {
            $user->setEmail($data->email);
        }

        // Gestion du mot de passe
        if (isset($data->newPassword) && !empty($data->newPassword)) {
            // Si un nouveau mot de passe est fourni, on le hash et on le met à jour
            $user->setPassword(password_hash($data->newPassword, PASSWORD_DEFAULT));
        } else {
            // Sinon, on garde l'ancien mot de passe (déjà dans l'objet user)
            // Pas besoin de re-hasher, il est déjà hashé en BDD
        }

        // Sauvegarder via le repository
        $updatedUser = $this->users->update($user, $userId);
        if ($updatedUser) {
            return [
                "success" => true,
                "message" => "Profil mis à jour avec succès",
                "user" => [
                    "id" => $updatedUser->getId(),
                    "name" => $updatedUser->getName(),
                    "lastName" => $updatedUser->getLastName(),
                    "email" => $updatedUser->getEmail(),
                ]
            ];
        } else {
            http_response_code(500);
            return ["error" => "Erreur lors de la mise à jour"];
        }
    }

}

?>