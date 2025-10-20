import { genericRenderer, htmlToFragment } from "../../lib/utils.js";
import template from "./template.html?raw";

let DetailImgView = {
  html: function (data) {
    let htmlString = '<div class="swiper-wrapper">';
    for (let obj of data) {
      const item = template.replace('{{images}}', obj);
      htmlString += item;
    }
    return htmlString + '</div>';
  },

  dom: function (data) {
    return htmlToFragment(DetailImgView.html(data));
  }
};

export { DetailImgView };


