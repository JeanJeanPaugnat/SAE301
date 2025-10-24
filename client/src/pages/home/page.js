import template from "./template.html?raw";
import { htmlToFragment } from "../../lib/utils.js";
import { UserData } from "../../data/user.js";

let M = {
    user: null,
    router: null
};

let C = {};

C.init = async function(params, router){
    M.router = router;

    const authCheck = await UserData.checkAuth();
    if (authCheck.logged) {
        M.user = authCheck;
        localStorage.setItem('connectedUser', JSON.stringify(authCheck));
    }

    return V.init();
}

let V = {};

V.init = function(){
    let fragment = htmlToFragment(template);
    V.personalizeWelcome(fragment);
    return fragment;
}

V.personalizeWelcome = function(fragment){
    const welcomeSpan = fragment.querySelector('#homePersonalized');
    if(M.user){
        welcomeSpan.innerHTML = M.user.name;
        M.user= null; 
    }else{
        welcomeSpan.innerHTML = "chez Louis Vuitton";
    }
}

export function HomePage(params, router){
    return C.init(params, router);
}
