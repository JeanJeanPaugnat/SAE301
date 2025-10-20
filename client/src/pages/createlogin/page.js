import { htmlToFragment } from "../../lib/utils.js";
import template from "./template.html?raw";
import templateCreatelogin from "../../ui/createLogin/template.html?raw";



let C = {};


C.init = async function(){
    let pageFragment = htmlToFragment(template);
    let loginDOM = htmlToFragment(templateCreatelogin);
    pageFragment.querySelector('slot[name="login"]').replaceWith(loginDOM);
    return pageFragment;
}


export function CreateLoginPage(params) {
    console.log("CreateLoginPage", params);
    return C.init();
}
