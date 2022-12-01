import {create,update,deleteById,getById,getAll}  from './AxiosService'

export function getAllUser(){
    return getAll('/agent/getAllUsers');
}
 export function  createUser(data){
     return create('/agent/User',data);
}
export function  createSuperUser(data){
    return create('/agent/User',data);
}
export function   getUserById(id){
    return   getById(`/User/${id}`);
}

export function   getMainUserById(id){
    return   getById(`/User/mainUser/${id}`);
}
export function   getSuperUserById(id){
    return  getById(`/User/mainUser/${id}`);
}
 export function  updateUser(data){
   
    return update('');
}

 export function  deleteUser(id){
    return deleteById(id);
}
