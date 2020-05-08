<template>
<div class="center-block w-xxl w-auto-xs p-y-md">
    <div class="navbar">
      <div class="pull-center">
            <router-link class="navbar-brand" to ="/">
                <img :src="'./../../../images/lwc.jpeg'" alt="logo" />
            </router-link>
      </div>
    </div>

    <div class="alert alert-warning alert-dismissible fade in" role="alert" v-if="(alert !== null)">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{alert.data}}
    </div>

    <div class="p-a-md box-color r box-shadow-z1 text-color m-a">
      <div class="m-b text-sm">
        Sign in with your Living Waters Account
      </div>
      <form name="form" @submit.prevent="login">
        <div class="md-form-group float-label">
          <input type="email" name="email" class="md-input" v-model="email" required>
          <label>Email</label>
        </div>
        <div class="md-form-group float-label">
          <input type="password" name="password" class="md-input" v-model="password" required>
          <label>Password</label>
        </div>
        <div class="m-b-md">
          <label class="md-check">
            <input type="checkbox"><i class="primary"></i> Keep me signed in
          </label>
        </div>
        <button type="submit" class="btn primary btn-block p-x-md">Sign in</button>
      </form>
    </div>
</div>
</template>
<script>
import axios from 'axios';
import { mapActions } from 'vuex';
export default {
    data(){
        return {
            alert: null,
            email : '',
            password : ''
        };
    },
    created(){
        let store = this.$store.state;
        if(store.isAuthenticated){
            window.location.replace('/dashboard');
        }
    },
    methods: {
        ...mapActions(['AUTH_REQUEST']),
        login: function () {
            const { email, password } = this;
            this.$store.dispatch('AUTH_REQUEST', { email, password }).then((res) => {
                const user = res.data.user;
                const userId = res.data.user.id;
                const role = res.data.user.role.name;
                this.isRole(role);
                this.isUser(user, userId);

                window.location.replace('/dashboard');

            }).catch(err => {
                this.$notify({group: 'foo',title: 'error',text: err.response.data.error,type: 'warn'});
                console.log(err.response);
            })
        },
        isUser(user, userId){
            this.$store.dispatch('USER', user, userId);
        },
        isRole(role){
            if(role === 'admin'){ this.$store.dispatch('ADMIN'); }
            else if(role === 'clerk'){ this.$store.dispatch('CLERK'); }
            else if(role === 'pastor'){ this.$store.dispatch('PASTOR'); }
            else if(role === 'homecell manager'){ this.$store.dispatch('HOMECELLMANAGER'); }
            else if(role === 'accountant'){ this.$store.dispatch('ACCOUNTANT'); }
            else if(role === 'treasure'){ this.$store.dispatch('TREASURE'); }
            else{console.log(role);}
        }

    }
}
</script>

