<template>
    <div class="padding">
        <div class="row" v-if="isAccountant || isClerk || isAdmin || isTreasure || isPastor">
            <div class="col-sm-12" v-if="isAccountant || isClerk">
                <div class="box">
                <div class="b-b b-primary nav-active-primary">
                    <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="" data-toggle="tab" data-target="#tab4" aria-expanded="true">Add Deposit/Contribution</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="" data-toggle="tab" data-target="#payment-voucher" aria-expanded="false">Add Payment voucher</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="" data-toggle="tab" data-target="#expenses" aria-expanded="false">Add expenses</a>
                    </li>
                    </ul>
                </div>
                <div class="tab-content p-a m-b-md">
                    <div class="tab-pane animated fadeIn text-muted active" id="tab4" aria-expanded="true">
                        <form role="form" @submit.prevent="deposit()">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payee">Source</label>
                                        <input v-model="deposit_source" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-addon form-control-sm">MWK</div>
                                            <input type="text" v-model="deposit_amount" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account">Account</label>
                                        <select class="form-control form-control-sm" v-model="account_id" required>
                                            <option v-for="account in accounts" :key="account.id" :value="account.id"> {{account.account_name}} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="expenditure-details">Deposit details</label>
                                        <textarea v-model="deposit_details" rows="2" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="deposit_type">Deposit type</label>
                                        <select id="deposit_type" class="form-control form-control-sm" v-model="deposit_type" required>
                                            <option value="contribution">Contribution</option>
                                            <option value="deposit">Deposit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 padding text-center">
                                    <button class="btn accent">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane animated fadeIn text-muted" id="payment-voucher" aria-expanded="false">
                        <form role="form" @submit.prevent="paymentVoucher()">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="requested-by">Requested by</label>
                                        <input type="text" v-model="requested_by" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payee">Payee</label>
                                        <input type="text" v-model="payee" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-addon form-control-sm">MWK</div>
                                            <input type="text" v-model="amount" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="expenditure-details">Details of expenditure</label>
                                        <textarea v-model="expenditure_details" rows="2" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="requested-by">Date</label>
                                        <input type="date" v-model="expenditure_date" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="requested-by">Account</label>
                                        <select class="form-control form-control-sm" v-model="account_id" required>
                                            <option v-for="account in accounts" :key="account.id" :value="account.id" class="form-control form-control-sm"> {{account.account_name}} </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button class="btn accent">Add</button>
                        </form>
                    </div>
                    <div class="tab-pane animated fadeIn text-muted" id="expenses" aria-expanded="false">
                        <form role="form" @submit.prevent="addExpense()">
                            <div class="row">
                                <div class="col-md-4">
                                    <div>
                                        <label for="payee">Expense Type</label>
                                        <select class="form-control form-control-sm" v-model="expense_type" required>
                                            <option v-for="(expenditure, index) in expenditures" :key="index" :value="expenditure" class="form-control form-control-sm"> {{expenditure}} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payee">Expense Amount</label>
                                        <input type="text" v-model="expense_amount" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="requested-by">Date</label>
                                        <input type="date" v-model="expense_date" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="expense-details">Expense details</label>
                                        <textarea v-model="comment" rows="2" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="requested-by">Account</label>
                                        <select class="form-control form-control-sm" v-model="account_id" required>
                                            <option v-for="account in accounts" :key="account.id" :value="account.id" class="form-control form-control-sm"> {{account.account_name}} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 padding text-center">
                                    <button class="btn accent">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
            <div class="text-md">
                <p>Transaction History</p>
            </div>
            <div class="col-md-12">
                <div class="box">
                    <div class="row p-a">

                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped b-t">
                        <thead>
                            <tr>
                                <th v-for="(column, index) in columns" :key="index"> {{column.title}} </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="transaction in transactions" :key="transaction.id">
                                <td> {{ transaction.id }} </td>
                                <td> {{ transaction.transaction_type }} </td>
                                <td> {{ transaction.transaction_source }} </td>
                                <td> {{ transaction.account_name }} </td>
                                <td> {{ transaction.transaction_amount | formatNumber}} </td>
                                <td> {{ transaction.transaction_date }} </td>
                                <td v-if="isAccountant || isClerk">
                                    <button class="btn btn-sm btn-outline b-warning text-warning"
                                    @click="reverse(transaction.id)" v-if="(transaction.reverse == null)">
                                    Reverse</button>
                                    <span v-if="(transaction.reverse == 'requested')">reversal {{transaction.reverse}}</span>
                                    <span v-if="(transaction.reverse == 'approved')">reversal {{transaction.reverse}}</span>
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <footer class="light lt p-a">
                        <div class="row">
                        <div class="col-sm-6 text-left">
                            <small class="text-muted inline m-t-sm m-b-sm">showing {{pagination.from}}-{{pagination.to}} of {{total}} items</small>
                        </div>
                        <div class="col-sm-6 text-right text-center-xs">
                            <ul class="pagination m-a-0">
                                <li>
                                    <a :class="[{disabled: !pagination.prev_page_url}]" class="btn"
                                     @click="getTransactions(pagination.prev_page_url)">
                                        <i class="fa fa-chevron-left"></i>
                                    </a>
                                </li>

                                <li>
                                    <a :class="[{disabled: !pagination.prev_page_url}]" class="btn">
                                        {{pagination.current_page}} of {{pagination.last_page}}
                                    </a>
                                </li>

                                <li>
                                    <a :class="[{disabled: !pagination.next_page_url}]" class="btn"
                                     @click="getTransactions(pagination.next_page_url)">
                                        <i class="fa fa-chevron-right"></i></a>
                                </li>
                            </ul>
                        </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
        <access-error v-else></access-error>
    </div>
</template>
<script>
import { mapState } from "vuex";
import axios from './../interceptor';
const { ExpenditureList } = require('./MockData/ExpenditureList.js');
import { transactionsURL } from './../config';
import printJS from 'print-js';

export default {
    data(){
        return {
            deposit_type: '',
            deposit_source: '',
            deposit_amount: 0,
            deposit_details: '',
            account_id: 0,
            requested_by: '',
            payee: '',
            amount: '',
            expenditures: ExpenditureList,
            expenditure_details: '',
            expenditure_date: '00-00-0000 00:00:00',
            expense_date: '00-00-0000 00:00:00',
            expense_type: '',
            expense_amount: 0,
            comment: '',
            columns: [
                { title: 'Transaction ID', field: 'id'},
                { title: 'Type', field: 'transaction_type' },
                { title: 'Source', field: 'transaction_source'},
                { title: 'Account', field: 'account_name' },
                { title: 'Amount', field: 'transaction_amount' },
                { title: 'Date', field: 'transaction_date' }
            ],
            transactions: [],
            total: 0,
            accounts: null,
            pagination: {}
        }
    },
    created(){
        let store = this.$store.getters;
        if(store.isClerk || store.isAccountant || store.isAdmin
        || store.isTreasure || store.isPastor){
            this.getAccounts();
            this.getTransactions();
        }
    },
    mounted(){
    },
    computed:{
        ...mapState(['isAccountant', 'isClerk', 'isAdmin', 'isTreasure', 'isPastor']),
    },
    methods:{
        getAccounts(){
            axios.get('/account').then(res =>{
                this.accounts = res.data;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: 'Error getting accounts details.',type: 'warn'});
            })
        },
        getTransactions(page_url){
            page_url = page_url || transactionsURL;
            axios.get(page_url).then(res =>{
                let data = res.data;
                let pagination = {
                    from: data.from,
                    to: data.to,
                    current_page: data.current_page,
                    last_page: data.last_page,
                    first_page_url: data.first_page_url,
                    last_page_url: data.last_page_url,
                    next_page_url: data.next_page_url,
                    prev_page_url: data.prev_page_url,
                }
                this.pagination = pagination;
                this.transactions = data.data;
                this.total = data.total;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        deposit(){
            const { account_id, deposit_type, deposit_source, deposit_amount, deposit_details } = this;
            axios.post('/deposit', { account_id, deposit_type, deposit_source, deposit_amount, deposit_details })
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.resetForms();
                this.getTransactions();
                this.printReceipt(res.data.data);
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        paymentVoucher(){
            const { account_id, requested_by, payee, amount, expenditure_date, expenditure_details } = this;
            axios.post('/payment-voucher', { account_id, requested_by, payee, amount, expenditure_date, expenditure_details })
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.resetForms();
                this.getTransactions();
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        addExpense(){
            const { account_id, expense_type, expense_amount, expense_date, comment } = this;
            axios.post('/expense', { account_id, expense_type, expense_amount, expense_date, comment })
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.resetForms();
                this.getTransactions();
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        printReceipt(data){
            printJS({printable: data,
            properties: ['transaction_type', 'transaction_source', 'transaction_amount', 'transaction_date'],
            type: 'json'});
        },
        reverse(id){
            axios.put('/transactions/request-reverse/'+id)
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getTransactions();
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        resetForms(){
            this.deposit_type = '',
            this.deposit_source = '',
            this.deposit_amount = 0,
            this.deposit_details = '',
            this.account_id = 0,
            this.requested_by = '',
            this.payee = '',
            this.amount = '',
            this.expenditure_details = '',
            this.expenditure_date = '00-00-0000 00:00:00',
            this.expense_date = '00-00-0000 00:00:00',
            this.expense_type = '',
            this.expense_amount = 0,
            this.comment = ''
        }
    }
}
</script>
