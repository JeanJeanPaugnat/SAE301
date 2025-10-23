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

C.attachEvents = function(fragment){
    // Les événements sont attachés dans la vue
    const form = fragment.querySelector('form');
    console.log(form);
    if (form) {
        form.addEventListener('submit', async (ev) => {
            ev.preventDefault();

            const userId = form.dataset.id;
            const prenom = form.querySelector("#prenom").value.trim();
            const nom = form.querySelector("#nom").value.trim();
            const email = form.querySelector("#email").value.trim();
            const oldPassword = form.querySelector("#password").value.trim();
            const newPassword = form.querySelector("#newPassword").value.trim();

            // Validation: l'ancien mot de passe est obligatoire
            if (!oldPassword) {
                alert("L'ancien mot de passe est requis pour enregistrer les modifications");
                return;
            }

            let data = {
                name: prenom,
                lastName: nom,
                email: email,
                oldPassword: oldPassword
            };

            // Ajouter le nouveau mot de passe seulement s'il est renseigné
            if (newPassword) {
                data.newPassword = newPassword;
            }

            try {
                const response = await UserData.update(userId, data);
                console.log('Profil mis à jour', response);
                
                // Mettre à jour le localStorage
                const connectedUser = JSON.parse(localStorage.getItem('connectedUser'));
                if (connectedUser) {
                    connectedUser.name = prenom;
                    connectedUser.lastName = nom;
                    connectedUser.email = email;
                    localStorage.setItem('connectedUser', JSON.stringify(connectedUser));
                }
                
                alert("Profil mis à jour avec succès");
                // Réinitialiser les champs de mot de passe
                form.querySelector("#password").value = "";
                form.querySelector("#newPassword").value = "";
            } catch (error) {
                console.error('Erreur lors de la mise à jour:', error);
                alert("Erreur: " + (error.message || "Impossible de mettre à jour le profil"));
            }
        });
    }
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
    
    C.attachEvents(pageFragment);
    return pageFragment;
}

export function ProfilePage(params, router) {
    console.log("ProfilePage", params);
    return C.init(params, router);
}