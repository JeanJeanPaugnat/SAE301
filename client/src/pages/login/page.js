import { htmlToFragment } from "../../lib/utils.js";
import { UserData } from "../../data/user.js";
import template from "./template.html?raw";
import templatelogin from "../../ui/login/template.html?raw";



let C = {};


C.init = async function(){
    let pageFragment = htmlToFragment(template);
    let loginDOM = htmlToFragment(templatelogin);
    pageFragment.querySelector('slot[name="login"]').replaceWith(loginDOM);
    return pageFragment;
}


export function LoginPage(params) {
    console.log("LoginPage", params);
    return C.init();
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('loginForm');
  if (!form) return;

  form.addEventListener('submit', async (ev) => {
    ev.preventDefault();
    console.log('Formulaire soumis');
    const email = document.querySelector("#email").value.trim();
    const password = document.querySelector("#password").value.trim();

   let data = {};

   data = {
        email: email,
        password: password
   };

    console.log('Données du formulaire:', data);
    try {
      await UserData.login(data);
      console.log('Connexion réussie');
    } catch (error) {
      console.error('Erreur lors de la connexion:', error);
    }
    
  });
});
