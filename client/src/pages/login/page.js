import { htmlToFragment } from "../../lib/utils.js";
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
