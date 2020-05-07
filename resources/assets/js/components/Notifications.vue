<template>
    <div class="padding">
        <div class="text-md text-primary">
            <p><i class="material-icons">&#xe873;</i> REVERSE TRANSACTION REQUESTS</p>
        </div>
        <div class="box">
            <div class="row p-a">

            </div>
            <div class="table-responsive">
                <table class="table table-striped b-t">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Source</th>
                        <th>Account</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="transaction in transactions" :key="transaction.id">
                        <td> {{ transaction.transaction_type }} </td>
                        <td> {{ transaction.transaction_source }} </td>
                        <td> {{ transaction.account_name }} </td>
                        <td> {{ transaction.transaction_amount | formatNumber}} </td>
                        <td> {{ transaction.transaction_date }} </td>
                        <td v-if="isAdmin">
                            <button class="btn btn-sm btn-outline b-warning text-warning"
                            @click="comfirm(transaction.id, 'allow')">
                            Comfirm</button>

                            <button class="btn btn-sm btn-outline b-success text-warning"
                            @click="comfirm(transaction.id, 'deny')">
                            Deny</button>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
import axios from './../interceptor';

export default {
    data(){
        return {
            transactions: null
        }
    },
    created(){
        this.getTransactions();
    },
    mounted(){
    },
    computed:{
        ...mapState(['isAdmin']),
    },
    methods:{
        getTransactions(){
            axios.get('/notification/transaction-list')
            .then(res =>{
                this.transactions = res.data;
            })
            .catch(err =>{
                console.log(err.response);
            });
        },
        comfirm(id, response){
            const transaction_id = id;
            axios.post('/transactions/reverse', {'transaction_id': transaction_id, 'response': response})
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getTransactions();
                window.location.replace('/notifications');
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response,type: 'warn'});
            })
        }
    }
}
</script>
