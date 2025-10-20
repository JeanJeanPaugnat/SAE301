import {getRequest, postRequest} from '../lib/api-request.js';


let UserData = {};


UserData.fetch = async function(id){
    let data = await getRequest('users/'+id);
    return data;
}

UserData.fetchAll = async function(){
    let data = await getRequest('users');
    return data;
}

UserData.createAccount = async function(userInfo){
    let data = await postRequest('users', userInfo);
    return data;
}





export { UserData };