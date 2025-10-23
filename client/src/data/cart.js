

let CartData = {};

// CartData.



CartData.addToCart = async function(product){
    // Récupérer le panier actuel depuis localStorage
    let cart = localStorage.getItem('cart');
    let cartArray = cart ? JSON.parse(cart) : [];
    
    // Chercher si le produit existe déjà dans le panier

    const existingProductIndex = cartArray.findIndex(item => item.id === product.id);
    
    if (existingProductIndex !== -1) {
        // Si le produit existe, augmenter sa quantité
        cartArray[existingProductIndex].quantity += 1;
    } else {
        // Sinon, ajouter le nouveau produit avec une quantité de 1
        cartArray.push({
            ...product,
            quantity: 1
        });
    }
    
    // Sauvegarder le panier mis à jour
    localStorage.setItem('cart', JSON.stringify(cartArray));
    console.log('Panier actuel:', cartArray);
    
    return cartArray;
}

// Fonction pour obtenir le panier complet
CartData.getCart = function() {
    let cart = localStorage.getItem('cart');
    return cart ? JSON.parse(cart) : [];
}

// Fonction pour vider le panier
CartData.clearCart = function() {
    localStorage.removeItem('cart');
    return [];
}

// Fonction pour retirer un produit
CartData.removeFromCart = function(productId) {
    let cartArray = this.getCart();
    cartArray = cartArray.filter(item => item.id !== Number(productId));
    localStorage.setItem('cart', JSON.stringify(cartArray));
    return cartArray;
}

// Fonction pour modifier la quantité
CartData.updateQuantity = function(productId, quantity) {
    let cartArray = this.getCart();
    const productIndex = cartArray.findIndex(item => item.id === productId);
    
    if (productIndex !== -1) {
        if (quantity <= 0) {
            cartArray.splice(productIndex, 1);
        } else {
            cartArray[productIndex].quantity = quantity;
        }
        localStorage.setItem('cart', JSON.stringify(cartArray));
    }
    
    return cartArray;
}



export {CartData};