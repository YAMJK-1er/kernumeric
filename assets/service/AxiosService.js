import axios from 'axios';

const SERVER_API_BASE_URL = " ";

   export function getAll(urls){
        return axios.get(SERVER_API_BASE_URL+urls);
    }
   export function create(urls,data){
        return axios.post(SERVER_API_BASE_URL+urls, data);
    }
  export function getById(urls,dataId){
        return axios.get(`${SERVER_API_BASE_URL}${urls}/${dataId}`);
    }
   export function update(urls,data, dataId){
        return axios.put(SERVER_API_BASE_URL + urls+ dataId, data);
    }
    
   export function deleteById(urls,dataId){
        return axios.delete(SERVER_API_BASE_URL +urls + dataId);
    }

