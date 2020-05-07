import axios from 'axios';

const tokenProvider = require('axios-token-interceptor');
const instance = axios.create({
    baseURL: 'http://localhost:8000/api'
});
instance.interceptors.request.use(tokenProvider({
    getToken: () => localStorage.getItem('user-token')
}));

const transactions = getData().then((result =>{
    return result
}))
function getData(){
    return instance.get('/transactions').then(res =>{
        return res.data.data;
    })
    .catch(err =>{
        return err.response;
    });
};

export default transactions
