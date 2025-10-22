import { htmlToFragment } from "../../lib/utils.js";
import { UserData } from "../../data/user.js";
import { Router } from "../../lib/router.js";
import template from "./template.html?raw";
import templateCreatelogin from "../../ui/createLogin/template.html?raw";



let C = {};


C.init = async function(router){
    let pageFragment = htmlToFragment(template);
    let loginDOM = htmlToFragment(templateCreatelogin);
    pageFragment.querySelector('slot[name="login"]').replaceWith(loginDOM);
    
    // Attacher l'événement au formulaire
    const form = pageFragment.querySelector('form');
    if (form) {
        form.addEventListener('submit', async (ev) => {
            ev.preventDefault();

            const name = form.querySelector("#prenom").value.trim();
            const lastname = form.querySelector("#nom").value.trim();
            const email = form.querySelector("#email").value.trim();
            const password = form.querySelector("#password").value.trim();

            let data = {
                name: name,
                lastname: lastname,
                email: email,
                password: password
            };

            console.log('Données du formulaire:', data);
            try {
                let response = await UserData.signUp(data);
                console.log('Compte créé avec succès', response);
                
                // Stocker les données utilisateur
                localStorage.setItem('connectedUser', JSON.stringify(response));
                
                // Mettre à jour l'état d'authentification du router
                if (router) {
                    router.setAuth(true);
                    router.navigate('/profile');
                }
            } catch (error) {
                console.error('Erreur lors de la création du compte:', error);
            }
        });
    }
    
    return pageFragment;
}



export function CreateLoginPage(params, router) {
    console.log("CreateLoginPage", params);
    return C.init(router);
}
