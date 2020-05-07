<template>
    <div class="padding">
        <div v-if="isAccountant || isAdmin || isTreasure || isPastor">
        <div class="row" v-if="isAccountant">
            <h5 class="text-primary">{{update? 'Edit' : 'Add'}} account</h5>
            <div class="col-sm-12 box" :class="(update ? 'col-md-4' : 'col-md-8')">
                <form role="form" class="p-a" @submit.prevent="onSubmit">
                    <span class="text-info" v-if="main">The first account will be the main account for this church.</span>
                    <div class="row">
                        <div class="col-sm-12" :class="(update ? 'col-md-12' : 'col-md-6')">
                            <div class="form-group">
                                <input type="hidden" v-if="update" v-model="id">
                                <label for="account_name">Account name</label>
                                <input type="text" id="account_name" v-model="account_name" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group" v-if="!update">
                                <label for="account_balance">Initial amount</label>
                                <input type="text" id="account_balance" v-model="account_balance" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-md accent">Submit</button>
                </form>
                <button class="btn btn-md warning" v-if="update" @click="resetForm()">Cancel</button>
                <button class="btn btn-md danger" v-if="update" @click="deleteAccount()">Delete</button>
            </div>
        </div>
        <div class="text-md text-primary">
            <p><i class="fa fa-briefcase"></i> ACCOUNTS</p>
        </div>
        <div class="row">
            <div class="list-item box r m-b" v-for="account in accounts" :key="account.id">
                <a @click="editAccount(account)">
                <div class="col-sm-12 col-md-4">
                    <div>ACCOUNT ID</div>
                    <div class="text-muted"> {{account.id}} </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div>ACCOUNT NAME</div>
                    <div class="text-muted"> {{account.account_name}} </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div>ACCOUNT BALANCE</div>
                    <div class="text-muted">MWK {{account.account_balance | formatNumber}} </div>
                </div></a>
            </div>
        </div>
        </div>
        <access-error v-else></access-error>
    </div>
</template>
<script>
import { mapState } from "vuex";
import axios from './../interceptor';

export default {
    data(){
        return {
            id: null,
            account_name: '',
            account_balance: 0,
            accounts: null,
            main: false,
            update: false,
            alert: null
        }
    },
    created(){
        let store = this.$store.getters;
        if(store.isClerk || store.isAccountant || store.isAdmin || store.isTreasure || store.isPastor){
            this.getAccounts();
        }
    },
    mounted(){
    },
    computed:{
        ...mapState(['isClerk', 'isAccountant', 'isAdmin', 'isTreasure', 'isPastor']),
    },
    methods:{
        onSubmit(){
            if(this.update === true){ this.updateAccount(); }
            else{ this.addAccount(); }
        },
        getAccounts(){
            axios.get('/account').then(res =>{
                this.accounts = res.data;
                this.main = (this.accounts.length == 0)? true: false;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            })
        },
        addAccount(){
            const { main, account_name, account_balance } = this;
            axios.post('/account', {main, account_name, account_balance}).then(res =>{
                this.getAccounts();
                this.resetForm();
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            })
        },
        editAccount(account){
            this.update = true;
            this.id = account.id;
            this.account_name = account.account_name;
            this.account_balance = account.account_balance;
        },
        updateAccount(){
            const { id, account_name, account_balance } = this;
            axios.put('/account/'+this.id, {id, account_name, account_balance}).then(res =>{
                this.resetForm();
                this.getAccounts();
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            })
        },
        deleteAccount(){
            if(confirm('All transactions made to this account will not be visible. Continue?')){
                axios.delete('/account/'+this.id).then(res =>{
                    this.resetForm();
                    this.getAccounts();
                    this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                })
                .catch(err =>{
                    console.log(err.response);
                    this.resetForm();
                    this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
                })
            }
        },
        resetForm(){
            this.id = null,
            this.account_name = '',
            this.account_balance = 0,
            this.update = false
        }
    }
}
</script>
