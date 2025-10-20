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
        $ressource = $request->getRessources();
        $id = $request->getId();

        if ($ressource === "categories") {
            if ($id) {
                return $this->categories->findByCategory($id);
            }

            $cat = $request->getParam("categories");
            if ($cat) {
                return $this->categories->findByCategory($cat);
            }

            return $this->categories->findAll();
        }

        return false;
    }


    protected function processPostRequest(HttpRequest $request) {
            // Pour plus tard
    }
   
}

?>