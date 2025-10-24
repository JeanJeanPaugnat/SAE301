import { ProductData } from "../../data/product.js";
import { ProductView } from "../../ui/product/index.js";
import { CategoryData } from "../../data/category.js";
import { CategoryView } from "../../ui/category/index.js";
import { htmlToFragment } from "../../lib/utils.js";
import template from "./template.html?raw";


let M = {
    products: [],
    categories: []
};

M.filterProductsByCategory = function(categoryId){
    if(categoryId==="all"){
        return M.products;
    }else{
        let data = M.products.filter(p => p.category == categoryId);
        return data;
    }

}

let C = {};

C.handler_clickOnProduct = function(ev){
    if (ev.target.dataset.buy!==undefined){
        let id = ev.target.dataset.buy;
        alert(`Le produit d'identifiant ${id} ? Excellent choix !`);
    }
}

C.handler_ClickOnCategory = async function(ev){
    console.log(ev.target.dataset.id)
    let categoryName = ev.target.dataset.name;
    if (categoryName !== undefined){
        let data = await CategoryData.fetchAllByCat(categoryName);
        V.renderProductsbyCategory(data);
    };

}

C.init = async function(params){
    M.products = await CategoryData.fetchAllByCat(params); 
    console.log(M.products);
    M.categories = await CategoryData.fetchAll();
    console.log(M.categories);
    
    return V.init( M.products, M.categories );
}


let V = {};

V.init = function(dataProducts, dataCategories){
    let fragment = V.createPageFragment(dataProducts, dataCategories);
    V.attachEvents(fragment);
    V.renderStockStatus(fragment);
    return fragment;
}

V.createPageFragment = function( dataProducts, dataCategories ){
   // Créer le fragment depuis le template
   let pageFragment = htmlToFragment(template);
   
   // Générer les produits
   let productsDOM = ProductView.dom(dataProducts);
   let categoriesDOM = CategoryView.dom(dataCategories);
   
   // Remplacer le slot par les produits
   pageFragment.querySelector('slot[name="products"]').replaceWith(productsDOM);
   pageFragment.querySelector('slot[name="categories"]').replaceWith(categoriesDOM);
   
   return pageFragment;
}

V.renderProductsbyCategory = function(dataProducts){
    console.log(dataProducts);
    let productsDOM = ProductView.dom(dataProducts);
    let container = document.querySelector("#products");
    console.log(container);
    container.replaceWith(productsDOM);
}

V.attachEvents = function(pageFragment) {
    let categories = pageFragment.querySelector("#categories");
    categories.addEventListener("click", C.handler_ClickOnCategory);
    return pageFragment;
}

V.renderStockStatus = function(fragment){
    const productElements = fragment.querySelectorAll('.stock-status');
    console.log(productElements);
    productElements.forEach(productElement => {
        let stockStatus = productElement.textContent;
        if (stockStatus === "In stock") {
            productElement.classList.add('in-stock');
        } else if (stockStatus === "Running low") {
            productElement.classList.add('running-low');
        } else if (stockStatus === "Out of stock") {
            productElement.classList.add('out-of-stock');
        }
    });
}


export function CategoriesPage(params) {
    console.log("CategoriesPage", params);
    return C.init(params.name);
}
