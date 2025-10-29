import { UserData } from "../../data/user.js";

import { OrderData } from "../../data/order.js";
import { OrderView } from "../../ui/orderUser/index.js";

import { htmlToFragment } from "../../lib/utils.js";
import template from "./template.html?raw";

let M = {
    user: null,
    orders: null
};

let C = {};

C.init = async function(params, router){
    // Récupérer les données de l'utilisateur connecté depuis localStorage
    const connectedUser = localStorage.getItem('connectedUser');
    console.log(connectedUser);
    
    if (connectedUser) {
        M.user = JSON.parse(connectedUser);
    } else {
        // Si pas de données en localStorage, vérifier avec l'API
        const authCheck = await UserData.checkAuth();
        if (authCheck.logged) {
            M.user = authCheck;
            localStorage.setItem('connectedUser', JSON.stringify(authCheck));
        } else {
            // Pas connecté, rediriger vers login
            router.setAuth(false);
            router.navigate('/login');
            return;
        }
    }
    M.orders = await OrderData.fetchByUser(M.user.id);
    return V.init(M.user, M.orders, router);
}

C.attachEvents = function(fragment){
    
}

let V = {};

V.init = function(dataUser, dataOrders, router){
    let fragment = V.createPageFragment(dataUser, dataOrders, router);
    return fragment;
}

V.createPageFragment = function(dataUser, dataOrders, router){
    let pageFragment = htmlToFragment(template);
    console.log(dataOrders);
    let allOrdersDom = OrderView.domList(dataOrders);
    console.log(allOrdersDom);
    pageFragment.querySelector('slot[name="orders"]').replaceWith(allOrdersDom);
    
    // Ajouter le bouton de déconnexion
    const logoutBtn = pageFragment.querySelector('#logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async (ev) => {
            ev.preventDefault();
            try {
                await UserData.signOut();
                localStorage.removeItem('connectedUser');
                router.setAuth(false);
                router.navigate('/login');
            } catch (error) {
                console.error('Erreur lors de la déconnexion:', error);
            }
        });
    }
    
    C.attachEvents(pageFragment);
    return pageFragment;
}

export function OrdersPage(params, router) {
    console.log("OrdersPage", params);
    return C.init(params, router);
}