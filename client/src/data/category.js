import {getRequest} from '../lib/api-request.js';


let CategoryData = {};


CategoryData.fetch = async function(id){
    let data = await getRequest('categories/'+id);
    return data;
}

CategoryData.fetchAll = async function(){
    let data = await getRequest('categories');
    return data;
}



export {CategoryData};