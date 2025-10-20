import { htmlToFragment } from "../../lib/utils.js";
import { UserData } from "../../data/user.js";
import template from "./template.html?raw";
import templateCreatelogin from "../../ui/createLogin/template.html?raw";



let C = {};


C.init = async function(){
    let pageFragment = htmlToFragment(template);
    let loginDOM = htmlToFragment(templateCreatelogin);
    pageFragment.querySelector('slot[name="login"]').replaceWith(loginDOM);
    const form = pageFragment.querySelector('form')
    return pageFragment;
}



export function CreateLoginPage(params) {
    console.log("CreateLoginPage", params);
    return C.init();
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('create-login-form');
  if (!form) return;

  form.addEventListener('submit', async (ev) => {
    ev.preventDefault();

    const name = document.querySelector("#prenom").value.trim();
    const lastname = document.querySelector("#nom").value.trim();
    const email = document.querySelector("#email").value.trim();
    const password = document.querySelector("#password").value.trim();

   let data = {};

   data = {
        name: name,
        lastname: lastname,
        email: email,
        password: password
   };

    console.log('Données du formulaire:', data);
    try {
      await UserData.createAccount(data);
      console.log('Compte créé avec succès');
    } catch (error) {
      console.error('Erreur lors de la création du compte:', error);
    }
    
  });
});
