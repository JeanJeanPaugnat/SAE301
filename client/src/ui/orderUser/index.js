import { genericRenderer, htmlToFragment } from "../../lib/utils.js";
import template from "./template.html?raw";
import itemTemplate from "./item-template.html?raw";


let OrderView = {
  html: function (order) {
    // Rendre le template principal de la commande
    return genericRenderer(template, {
      id: order.id,
      createdAt: order.createdAt,
      totalAmount: order.totalAmount,
      userId: order.userId
    });
  },

  htmlItem: function (item) {
    // Rendre un item individuel
    return genericRenderer(itemTemplate, item);
  },

  dom: function (order) {
    // Créer le fragment DOM de la commande
    const orderFragment = htmlToFragment(OrderView.html(order));
    
    // Trouver le conteneur des items
    const itemsContainer = orderFragment.querySelector('[data-items-container]');
    
    // Ajouter chaque item
    order.items.forEach(item => {
      const itemFragment = htmlToFragment(OrderView.htmlItem(item));
      itemsContainer.appendChild(itemFragment);
    });
    
    return orderFragment;
  },

  // Fonction pour afficher toutes les commandes
  domList: function (orders) {
    const container = document.createDocumentFragment();
    
    orders.forEach(order => {
      const orderDom = OrderView.dom(order);
      container.appendChild(orderDom);
    });
    
    return container;
  }
};



export { OrderView };