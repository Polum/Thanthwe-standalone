<template>
    <div class="app-header white bg b-b">
        <div class="navbar">
            <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up p-r m-a-0">
                <i class="ion-navicon"></i>
            </a>
            <div class="navbar-item pull-left h5" id="pageTitle"> {{languageData.menu.m1}} </div>
            <!-- nabar right -->
            <ul class="nav navbar-nav pull-right">
                <li class="nav-item pos-stc-xs" v-if="isAdmin">
                    <router-link to="/notifications" class="nav-link">
                        <i class="material-icons md-24">&#xe7f5;</i>
                        <span class="label label-sm rounded up danger" v-if="notifications">
                            {{notifications}}
                        </span>
                    </router-link>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link clear" data-toggle="dropdown">
                    <span class="nav-icon">
                    <i class="material-icons md-24">&#xe7ff;</i>
                  </span>
                </a>

                <div class="dropdown-menu text-color w-md animated fadeInRight pull-right">
                    <router-link class="dropdown-item" to ="/profile">
                        <span>{{ user? user.name : '' }} {{authStatus}}</span>
                    </router-link>
                    <a class="dropdown-item" @click="logout">Sign out</a>
                </div>
                </li>
            </ul>
            <!-- / navbar right -->
        </div>
    </div>
</template>
<script>
import {mapState, mapActions} from 'vuex';
import axios from './../../interceptor';
import store from '../../store';

export default {
    data(){
        return {
            notifications: null
        }
    },
    computed: {
        ...mapState(['user', 'authStatus', 'isAdmin', 'languageData']),
        //
    },
    created(){
    },
    mounted(){
        this.getNotifications();
    },
    methods:{
        ...mapActions(['AUTH_LOGOUT']),
        logout(){
            this.$store.dispatch('AUTH_LOGOUT')
            .then(() => {
                axios.post('/logout').then(res =>{
                    this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                })
                .catch(err => {
                    this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
                });
                this.$router.push('/login');
            });
        },
        getNotifications(){
            axios.get('/dashboard')
            .then( (res) => {
                this.notifications = res.data.notifications;
            })
            .catch( (err) => {

            })
        }
    }
}
</script>

