import template from "./template.html?raw";
import { CartData } from "../../data/cart.js";
import { htmlToFragment } from "../../lib/utils.js";
import { ProductCartView } from "../../ui/productCart/index.js";
import { UserData } from "../../data/user.js";
import { OrderData } from "../../data/order.js";
import { ProductData } from "../../data/product.js";

let M = {
    user: null,
    router: null
};

let C = {};

C.init = async function(params, router){
    M.router = router;

        // Si pas de données en localStorage, vérifier avec l'API
        const authCheck = await UserData.checkAuth();
        console.log("Auth check:", authCheck);  
        console.log(authCheck.logged);
        if (authCheck.logged) {
            M.user = authCheck;
            localStorage.setItem('connectedUser', JSON.stringify(authCheck));
        }

    let cartItems = CartData.getCart();
    return V.init(cartItems);
}

C.handlerRemoveItem = function(event){
    const productId = Number(event.currentTarget.dataset.id);
    CartData.removeFromCart(productId);
    
    // Recharger la page du panier
    const newFragment = C.init({}, M.router);
    const appContainer = document.querySelector('#replaceCart');
    appContainer.innerHTML = '';
    appContainer.appendChild(newFragment);
}

C.handlerDecreaseQuantity = function(event){
    const productId = Number(event.currentTarget.dataset.id);
    const element = event.currentTarget.closest('#product-cart-item');
    const currentQty = Number(element.querySelector('#quantity').textContent);
    
    if (currentQty > 1) {
        CartData.updateQuantity(productId, currentQty - 1);
        element.querySelector('#quantity').textContent = currentQty - 1;
        V.updateProductPrice(element, currentQty - 1);
        V.updateTotalPrice();
    }
}

C.handlerIncreaseQuantity = async function(event){
    const productId = Number(event.currentTarget.dataset.id);
    console.log("Increase quantity for product ID:", productId);
    const element = event.currentTarget.closest('#product-cart-item');
    const currentQty = Number(element.querySelector('#quantity').textContent);
    console.log("Current quantity:", currentQty);

        let response = await ProductData.fetch(productId);
        
        const availableStock = response[0].quantity;

        if (currentQty >= availableStock) {
            alert(`Stock maximum atteint (${availableStock} article${availableStock > 1 ? 's' : ''} disponible${availableStock > 1 ? 's' : ''})`);
            return;
        }
        
        CartData.updateQuantity(productId, currentQty + 1);
        element.querySelector('#quantity').textContent = currentQty + 1;
        V.updateProductPrice(element, currentQty + 1);
        V.updateTotalPrice();

}

C.handlerProcessPayment = async function(){
    // Vérifier si l'utilisateur est connecté
    console.log("User before payment:", M.user);
    if (!M.user) {
        alert("Vous devez être connecté pour effectuer un paiement");
        M.router.navigate('/login');
        return;
    }
    
    // Récupérer les données du panier
    const cartItems = CartData.getCart();
    
    if (cartItems.length === 0) {
        alert("Votre panier est vide");
        return;
    }
    
    // Préparer les données de commande
    const orderData = {
        userId: M.user.id,
        items: cartItems.map(item => ({
            productId: item.id,
            productName: item.name,
            quantity: item.quantity,
            unitPrice: V.parsePrice(item.price), // Prix unitaire
            totalPrice: V.parsePrice(item.price) * item.quantity // Prix total de la ligne
        })),
        totalAmount: cartItems.reduce((sum, item) => {
            return sum + (V.parsePrice(item.price) * item.quantity);
        }, 0)
    };
    
    console.log("Order data:", orderData);
    
    // TODO: Envoyer la commande à l'API

    await OrderData.create(orderData);
    
    alert("Paiement effectué avec succès ! Merci pour votre achat.");
    
    // Vider le panier après paiement
    CartData.clearCart();
    
    // Rediriger vers la page de profil ou de confirmation
    M.router.navigate('/cart');
}

let V = {};


V.parsePrice = function(priceString){
    // Convertir "2 400,00" en 2400.00
    return parseFloat(priceString.replace(/\s/g, '').replace(',', '.'));
}


V.formatPrice = function(number){
    // Convertir 2400.00 en "2 400,00"
    return number.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

V.attachEvents = function(fragment){
    let products = fragment.querySelectorAll('#product-cart-item');
    products.forEach(element => {
        element.querySelector('#removeItemCart').addEventListener('click', C.handlerRemoveItem);
        element.querySelector('#decreaseQuantity').addEventListener('click', C.handlerDecreaseQuantity);
        element.querySelector('#increaseQuantity').addEventListener('click', C.handlerIncreaseQuantity);
    });
    let processPaymentBtn = fragment.querySelector('#processPaymentBtn');
    processPaymentBtn.addEventListener('click', C.handlerProcessPayment);
}

V.nbItems = function(fragment){
    let nbItems = CartData.getCart().length;
    let cartCounter = fragment.querySelector('#nbItemsCart');
    if(cartCounter){
        cartCounter.textContent = nbItems;
    }
}

V.updateProductPrice = function(element, quantity){
    const productId = Number(element.dataset.id);
    const cartItems = CartData.getCart();
    const product = cartItems.find(item => item.id === productId);
    
    if(product){
        const priceNumber = V.parsePrice(product.price);
        const totalPrice = priceNumber * quantity;
        element.querySelector('#productPrice').textContent = V.formatPrice(totalPrice) + '€';
    }
}

V.updateAllProductsPrices = function(fragment){
    const products = fragment.querySelectorAll('#product-cart-item');
    products.forEach(element => {
        const quantity = Number(element.querySelector('#quantity').textContent);
        V.updateProductPrice(element, quantity);
    });
}

V.updateTotalPrice = function(fragment){
    const cartItems = CartData.getCart();
    const total = cartItems.reduce((sum, item) => {
        const priceNumber = V.parsePrice(item.price);
        return sum + (priceNumber * item.quantity);
    }, 0);
    if (fragment) {
        let totalElement = fragment.querySelector('#totalPrice');
        if(totalElement){
            totalElement.textContent = V.formatPrice(total) + '€';
        }
    }else{
        let totalElement = document.querySelector('#totalPrice');
        if(totalElement){
            totalElement.textContent = V.formatPrice(total) + '€';
        }
    }
}

V.init = function(cartItems){
    let fragment = V.createPageFragment(cartItems);
    V.nbItems(fragment);
    V.updateAllProductsPrices(fragment);
    V.updateTotalPrice(fragment);
    V.attachEvents(fragment);
    return fragment;
}

V.createPageFragment = function(cartItems){
    let pageFragment = htmlToFragment(template);
    let productCartDOM = ProductCartView.dom(cartItems);
    pageFragment.querySelector('slot[name="listproducts"]').replaceWith(productCartDOM);
    return pageFragment;
}

export function CartPage(params, router){
    console.log("Cart items:", CartData.getCart());
    return C.init(params, router);
}

