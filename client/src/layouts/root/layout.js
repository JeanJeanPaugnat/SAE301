import template from "./template.html?raw";
import { htmlToFragment } from "../../lib/utils.js";
import { HeaderView } from "../../ui/header/index.js";
import { FooterView } from "../../ui/footer/index.js";



/**
 * Construit et retourne le layout principal de l'application.
 *
 * @function
 * @returns {DocumentFragment} Le fragment DOM représentant le layout complet.
 *
 * @description
 * - Crée un fragment DOM à partir du template HTML.
 * - Génère le DOM de l'en-tête via HeaderView.dom().
 * - Génère le DOM du pied de page via FooterView.dom().
 * - Remplace le slot nommé "header" par le DOM de l'en-tête.
 * - Remplace le slot nommé "footer" par le DOM du pied de page.
 * - Retourne le fragment DOM finalisé.
 */
export function RootLayout() {
    let layout = htmlToFragment(template);
    let header = HeaderView.dom();
    let footer = FooterView.dom();
    layout.querySelector('slot[name="header"]').replaceWith(header);
    layout.querySelector('slot[name="footer"]').replaceWith(footer);
    listenMenuBurger(layout);
    return layout;
}

// A gerer pour faire un menu burger avec en lien le composant header



let listenMenuBurger = function(layout) {
        

    const burgerButton = layout.querySelector("#menuButton");
    const sideMenu = layout.querySelector("#sideMenu");
    const menuOverlay = layout.querySelector("#menuOverlay");
    const closeButton = layout.querySelector("#closeMenu");
    console.log(burgerButton);

    // Fonction pour ouvrir le menu
    const openMenu = () => {
        if (!sideMenu || !menuOverlay) return;
        sideMenu.classList.remove("-translate-x-full");
        menuOverlay.classList.remove("hidden");
    };

    // Fonction pour fermer le menu
    const closeMenuFn = () => {
        if (!sideMenu || !menuOverlay) return;
        sideMenu.classList.add("-translate-x-full");
        menuOverlay.classList.add("hidden");
    };

    // Attache les événements seulement si les éléments existent
    if (burgerButton) burgerButton.addEventListener("click", openMenu);
    if (closeButton) closeButton.addEventListener("click", closeMenuFn);
    if (menuOverlay) menuOverlay.addEventListener("click", closeMenuFn);

    // Ferme le menu quand on change de page (optionnel)
    layout.addEventListener("click", (e) => {
        if (e.target.matches("[data-link]")) closeMenuFn();
    });
;
}

