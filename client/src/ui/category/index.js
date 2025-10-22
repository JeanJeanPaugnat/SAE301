import { genericRenderer, htmlToFragment } from "../../lib/utils.js";
import template from "./template.html?raw";

let CategoryView = {
  html: function (data) {
    let htmlString = '<div id="categories" class="flex justify-center gap-2 pt-4 pb-4">';
    htmlString += '<button href="/products" data-link data-id="all" data-name="all" class="w-30 h-30 text-center bg-black text-white">All</button>';
    for (let obj of data) {
      htmlString  += genericRenderer(template, obj);
    }
    return htmlString + '</div>';
  },

  dom: function (data) {
    return htmlToFragment( CategoryView.html(data) );
  }

};

export { CategoryView };

