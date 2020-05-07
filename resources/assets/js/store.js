import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import { loginURL } from './config';
import { Language } from './components/MockData/Language';

Vue.use(Vuex);
Vue.use(axios);

export default new Vuex.Store({
    state: {
        isAuthenticated: !!localStorage.getItem('user-token') || false,
        token: localStorage.getItem('user-token') || '',
        userId: localStorage.getItem('userId') || 0,
        user: JSON.parse(localStorage.getItem('user')) || '',
        status: '',
        isAdmin: localStorage.getItem('isAdmin') || '',
        isHomecellManager: localStorage.getItem('isHomecellManager') || '',
        isClerk: localStorage.getItem('isClerk') || '',
        isPastor: localStorage.getItem('isPastor') || '',
        isAccountant: localStorage.getItem('isAccountant') || '',
        isTreasure: localStorage.getItem('isTreasure') || '',
        languages: Language.languages,
        language: localStorage.getItem('language') || 'english',
        languageData: Language[localStorage.getItem('language')]
    },

    mutations: {
        AUTH_REQUEST(state){
            state.status = 'loading'
        },
        AUTH_SUCCESS(state, token, userId, user){
            state.status = 'success'
            state.token = token
        },
        AUTH_ERROR(state){
            state.status = 'error'
        },
        AUTH_LOGOUT(state, token, user, userId){
            state.status = "logged out"
            state.token = token
            state.userId = userId
            state.user = user
            state.isAdmin = false
            state.isHomecellManager = false
            state.isClerk = false
            state.isPastor = false
            state.isAccountant = false
            state.isTreasure = false
        },
        USER(state, user){
            state.user = user
            state.userId = user.id
        },
        ADMIN(state){
            state.isAdmin = true
        },
        HOMECELLMANAGER(state){
            state.isHomecellManager = true
        },
        CLERK(state){
           state.isClerk = true
        },
        PASTOR(state){
            state.isPastor = true
         },
        ACCOUNTANT(state){
            state.isAccountant = true
        },
        TREASURE(state){
            state.isTreasure = true
        },
        LANGUAGE(state, language){
            state.language = language;
            state.languageData = Language[localStorage.getItem('language')];
        }

    },

    actions : {
        AUTH_REQUEST({commit, dispatch}, user){
            return new Promise((resolve, reject) => { // The Promise used for router redirect in login
              commit('AUTH_REQUEST');
              axios({url: loginURL, data: user, method: 'post' })
                .then(resp => {
                    const rrr = resp;
                    console.log(rrr);
                    const token = resp.data.token;
                    localStorage.setItem('user-token', token) // store the token in localstorage
                    if(localStorage.getItem('language')){

                    }else{
                        localStorage.setItem('language', 'english');
                        commit('LANGUAGE', language, 'english');
                    }
                    axios.defaults.headers.common['Authorization'] = token
                    commit('AUTH_SUCCESS', token)
                    resolve(resp);
                })
              .catch(err => {
                commit('AUTH_ERROR', err)
                localStorage.removeItem('user-token') // if the request fails, remove any possible user token if possible
                reject(err);
              });
            });
        },
        AUTH_LOGOUT({commit, dispatch}){
            return new Promise((resolve, reject) => {
              localStorage.removeItem('user-token') // clear your user's token from localstorage
              localStorage.removeItem('user')
              localStorage.removeItem('userId')
              localStorage.removeItem('isAdmin')
              localStorage.removeItem('isHomecellManager')
              localStorage.removeItem('isClerk')
              localStorage.removeItem('isPastor')
              localStorage.removeItem('isAccountant')
              localStorage.removeItem('isTreasure')
              delete axios.defaults.headers.common['Authorization'] // remove the axios default header
              commit('AUTH_LOGOUT', '', '', 0)
              resolve()
            })
        },
        USER({commit}, user){
            localStorage.setItem('user', JSON.stringify(user));
            localStorage.setItem('userId', user.id);
            commit('USER', user, user.id);
        },
        ADMIN({commit}){
            localStorage.setItem('isAdmin', true);
            commit('ADMIN');
        },
        HOMECELLMANAGER({commit}){
            localStorage.setItem('isHomecellManager', true);
            commit('HOMECELLMANAGER');
        },
        CLERK({commit}){
            localStorage.setItem('isClerk', true);
           commit('CLERK');
        },
        PASTOR({commit}){
            localStorage.setItem('isPastor', true);
           commit('PASTOR');
        },
        ACCOUNTANT({commit}){
            localStorage.setItem('isAccountant', true);
            commit('ACCOUNTANT');
        },
        TREASURE({commit}){
            localStorage.setItem('isTreasure', true);
            commit('TREASURE');
        },
        LANGUAGE({commit}, language){
            localStorage.setItem('language', language);
            commit('LANGUAGE', language);
        },
    },

    getters: {
        isAuthenticated: state => !!state.token,
        authStatus: state => state.status,
        userId: state => state.userId,
        user: state => state.user,
        isAdmin: state => !!state.isAdmin,
        isHomecellManager: state => !!state.isHomecellManager,
        isClerk: state => !!state.isClerk,
        isPastor: state => !!state.isPastor,
        isAccountant: state => !!state.isAccountant,
        isTreasure: state => !!state.isTreasure,
        languages: state => state.languages,
        language: state => state.language,
        languageData: state => state.languageData

    }
})
