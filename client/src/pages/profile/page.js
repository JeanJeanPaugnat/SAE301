import { UserData } from "../../data/user.js";
import { UserInfoView } from "../../ui/infoUser/index.js";
import { htmlToFragment } from "../../lib/utils.js";
import template from "./template.html?raw";

let M = {
    user: null,
};

let C = {};

C.init = async function(params, router){
    // Récupérer les données de l'utilisateur connecté depuis localStorage
    const connectedUser = localStorage.getItem('connectedUser');
    
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

    console.log(M.user);
    return V.init(M.user, router);
}

let V = {};

V.init = function(dataUser, router){
    let fragment = V.createPageFragment(dataUser, router);
    return fragment;
}

V.createPageFragment = function(dataUser, router){
    let pageFragment = htmlToFragment(template);
    let userInfoDOM = UserInfoView.dom(dataUser);
    pageFragment.querySelector('slot[name="infos"]').replaceWith(userInfoDOM);
    
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
    
    return pageFragment;
}

export function ProfilePage(params, router) {
    console.log("ProfilePage", params);
    return C.init(params, router);
}