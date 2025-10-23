import {getRequest, patchRequest, postRequest, } from '../lib/api-request.js';

let OrderData = {};

OrderData.fetch = async function(id){
    let data = await getRequest('orders/'+id);
    return data;
}

OrderData.create = async function(orderData){
    let data = await postRequest('orders', orderData);
    return data;
}

// OrderData.update = async function(id, orderData){
//     let data = await patchRequest('orders/'+id, orderData);
//     return data;
// }

export { OrderData };