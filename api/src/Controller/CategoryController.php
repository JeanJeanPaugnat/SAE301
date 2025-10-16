<?php
require_once "src/Controller/EntityController.php";
require_once "src/Repository/CategoryRepository.php" ;


// This class inherits the jsonResponse method  and the $cnx propertye from the parent class Controller
// Only the process????Request methods need to be (re)defined.

class CategoryController extends EntityController {

    private CategoryRepository $categories;

    public function __construct(){
        $this->categories = new CategoryRepository();
    }

   
    protected function processGetRequest(HttpRequest $request) {
        $id = $request->getId();
        if ($id) {
            $res = $this->categories->find($id);
            return $res ?: false;;
        } else {
            $res = $this->categories->findAll();
            return $res;
        }
        
    }

    protected function processPostRequest(HttpRequest $request) {
            // Pour plus tard
    }
   
}

?>