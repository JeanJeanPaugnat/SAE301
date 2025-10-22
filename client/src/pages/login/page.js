import { htmlToFragment } from "../../lib/utils.js";
import { UserData } from "../../data/user.js";
import template from "./template.html?raw";
import templatelogin from "../../ui/login/template.html?raw";

let C = {};

C.init = async function(router){
    let pageFragment = htmlToFragment(template);
    let loginDOM = htmlToFragment(templatelogin);
    pageFragment.querySelector('slot[name="login"]').replaceWith(loginDOM);
    
    // Attacher l'événement au formulaire
    const form = pageFragment.querySelector('form');
    if (form) {
        form.addEventListener('submit', async (ev) => {
            ev.preventDefault();

            console.log('Formulaire soumis');
            
            const email = form.querySelector("#email").value.trim();
            const password = form.querySelector("#password").value.trim();

            let data = {
                email: email,
                password: password
            };

            console.log('Données du formulaire:', data);
            try {
                let dataReturn = await UserData.signIn(data);
                console.log('Connexion réussie', dataReturn);
                
                // Stocker les données utilisateur
                localStorage.setItem('connectedUser', JSON.stringify(dataReturn));
                
                // Mettre à jour l'état d'authentification du router
                if (router) {
                    router.setAuth(true);
                    router.navigate('/profile');
                }
            } catch (error) {
                console.error('Erreur lors de la connexion:', error);
            }
        });
    }
    
    return pageFragment;
}

export function LoginPage(params, router) {
    console.log("LoginPage", params);
    return C.init(router);
}
