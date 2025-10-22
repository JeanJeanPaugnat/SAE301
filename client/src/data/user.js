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

UserData.signUp = async function(userInfo){
    console.log(userInfo);
    let data = await postRequest('users', userInfo);
    console.log(data);
    return data;
}

UserData.signIn = async function(credentials){
    console.log(credentials);
    let data = await postRequest('users?login', credentials);
    console.log(data);
    return data;
}

UserData.signOut = async function(){
    let data = await postRequest('users?logout');
    console.log(data);
    return data;
}

UserData.checkAuth = async function(){
    let data = await getRequest('users?check');
    return data;
}

export { UserData };