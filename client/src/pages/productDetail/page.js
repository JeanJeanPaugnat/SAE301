import { ProductData } from "../../data/product.js";
import { CartData } from "../../data/cart.js";
import { htmlToFragment } from "../../lib/utils.js";
import { DetailView } from "../../ui/detail/index.js";
import { DetailImgView } from "../../ui/galerie/index.js";
import template from "./template.html?raw";


let M = {
    products: {}
};

M.getProductById = function(id){
    return M.products.find(product => product.id == id);
}


let C = {};

C.handler_clickOnProduct = function(ev){
    let quantity = M.products[0].quantity;
    
    // Vérifier si le produit est en rupture de stock
    if (quantity <= 0){
        alert(`Produit en rupture de stock !`);
        return;
    }
    
    if (ev.target.dataset.buy !== undefined){
        let id = ev.target.dataset.buy;
        const cart = CartData.getCart();   
        const existingItem = cart.find(item => item.id === M.products[0].id);
        
        if (existingItem) {
            if (existingItem.quantity >= quantity) {
                alert(`Stock maximum atteint ! Vous avez déjà ${existingItem.quantity} article(s) dans votre panier. Stock disponible : ${quantity}`);
                return;
            }
        }
        
        alert(`Produit ajouté au panier !`);
        CartData.addToCart(M.products[0]);
    }
}

C.init = async function(params) {
    // Récupérer l'ID depuis les paramètres de route
    const productId = params.id;
    
    // Charger le produit depuis l'API
    M.products = await ProductData.fetch(productId);
    
    let p = M.getProductById(productId);
    console.log("Product loaded:", p);

    
    return V.init(p);
}


let V = {};

V.init = function(data) {
    let fragment = V.createPageFragment(data);
    V.attachEvents(fragment);
    V.renderStockStatus(fragment);
    return fragment;
}

V.createPageFragment = function(data) {
    // Créer le fragment depuis le template
    let pageFragment = htmlToFragment(template);

    // Générer le composant detail
    let detailDOM = DetailView.dom(data);
    let detailImgDOM = DetailImgView.dom(data.images);

    
    // Remplacer le slot par le composant detail
    pageFragment.querySelector('slot[name="detail"]').replaceWith(detailDOM);
    pageFragment.querySelector('slot[name="galerie"]').replaceWith(detailImgDOM);
    V.createSlider(pageFragment);
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

V.attachEvents = function(pageFragment) {
    // Attacher un event listener au bouton
    const addToCartBtn = pageFragment.querySelector('[data-buy]');
    addToCartBtn.addEventListener('click', C.handler_clickOnProduct);

    
    return pageFragment;
}

export function ProductDetailPage(params) {
    console.log("ProductDetailPage", params);
    return C.init(params);
}






//Pour le slider d'images

V.createSlider = function(pageFragment){
      const swiper = pageFragment.querySelector(".swiper");
  const wrapper = swiper.querySelector(".swiper-wrapper");
  const slides = swiper.querySelectorAll(".swiper-slide");
  const pagination = swiper.querySelector(".swiper-pagination");

  let currentIndex = 0;
  const totalSlides = slides.length;

  // Créer la pagination
  for (let i = 0; i < totalSlides; i++) {
    const dot = document.createElement("span");
    dot.classList.add("swiper-dot");
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => {
      goToSlide(i);
    });
    pagination.appendChild(dot);
  }

  const dots = pagination.querySelectorAll(".swiper-dot");

  function goToSlide(index) {
    if (index < 0) index = 0;
    if (index >= totalSlides) index = totalSlides - 1;
    wrapper.style.transform = `translateX(-${index * 100}%)`;
    currentIndex = index;
    updatePagination();
  }

  function updatePagination() {
    dots.forEach(dot => dot.classList.remove("active"));
    dots[currentIndex].classList.add("active");
  }

  // Swipe tactile
  let startX = 0;
  let isDragging = false;

  wrapper.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
    isDragging = true;
  });

  wrapper.addEventListener("touchmove", (e) => {
    if (!isDragging) return;
    const moveX = e.touches[0].clientX - startX;
    wrapper.style.transform = `translateX(${ -currentIndex * 100 + (moveX / wrapper.offsetWidth) * 100 }%)`;
  });

  wrapper.addEventListener("touchend", (e) => {
    isDragging = false;
    const endX = e.changedTouches[0].clientX;
    const diff = endX - startX;
    if (diff > 50) goToSlide(currentIndex - 1);
    else if (diff < -50) goToSlide(currentIndex + 1);
    else goToSlide(currentIndex);
  });
}
