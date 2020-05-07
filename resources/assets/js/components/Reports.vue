<template>
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="text-lg">
                    <p>Reports</p>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                        <div class="col-md-6">
                            <p class="text-md">Quick Access</p>
                        </div>
                        <div class="col-md-3 pull-right">
                            <select class="form-control form-control-sm" v-model="year" @change="loadStats()">
                                <option value="2018">2018</option>
                                <option value="2017">2017</option>
                                <option value="2016">2016</option>
                                <option value="2015">2015</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="card-body row">
                        <ul class="list col-md-5">
                            <li class="list-item" @click="log('tithe')"><a>
                                <i class="material-icons text-accent md-24">&#xe838;</i>
                                Tithe report</a>
                            </li>
                            <li class="list-item" @click="log('sunday-basket')"><a>
                                <i class="material-icons text-accent md-24">&#xe838;</i>
                                Sunday basket report</a>
                            </li>
                            <li class="list-item" @click="log('income-statement')"><a>
                                <i class="material-icons text-accent md-24">&#xe838;</i>
                                Income statement</a>
                            </li>
                        </ul>
                        <ul class="list col-md-5">
                            <li class="list-item" @click="log('members-tithe')"><a>
                                <i class="material-icons text-accent md-24">&#xe838;</i>
                                Members Tithe report</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header primary">
                        <h3>Tithe quartery stats</h3>
                    </div>
                    <table class="table stripped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Q1</th>
                                <th>Q2</th>
                                <th>Q3</th>
                                <th>Q4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Tithe totals</th>
                                <td v-for="(tithe, index) in titheStats" :key="index">{{tithe | formatNumber}}</td>
                            </tr>
                            <tr>
                                <th>Members tithed</th>
                                <td v-for="(NOMembers, index) in membersTithed" :key="index">{{NOMembers}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header info">
                        <h3>Members quartery stats</h3>
                    </div>
                    <table class="table stripped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Q1</th>
                                <th>Q2</th>
                                <th>Q3</th>
                                <th>Q4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Members joining</th>
                                <td v-for="(joining, index) in membersJoiningStats" :key="index"> {{joining}} </td>
                            </tr>
                            <tr>
                                <th>Members leaving</th>
                                <td v-for="(leaving, index) in membersLeavingStats" :key="index"> {{leaving}} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header accent">
                        <h3>Sunday Basket quartery stats</h3>
                    </div>
                    <table class="table stripped">
                        <thead>
                            <tr>
                                <th>Q1</th>
                                <th>Q2</th>
                                <th>Q3</th>
                                <th>Q4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td v-for="(basket, index) in sundayBasketStats" :key="index">{{basket | formatNumber}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import moment from 'moment';
import axios from './../interceptor';

export default {
    data(){
        return {
            report_type: 'tithe',
            year: moment().year(),
            title: 'Report',
            user_id: 1,
            filename: 'Report',
            url: '',
            titheStats: [],
            sundayBasketStats: [],
            membersTithed: [],
            membersJoiningStats: [],
            membersLeavingStats: []

        }
    },
    created(){
        this.loadStats();
    },
    mounted(){
    },
    computed:{
        ...mapState(['userId']),
    },
    methods:{
        log(report_type){
            window.location.replace('/report/'+report_type+'/'+this.year);
        },
        loadStats(){
            axios.get('/report-stats/'+this.year)
            .then(res =>{
                this.titheStats = res.data.tithe;
                this.membersTithed = res.data.members_tithed;
                this.sundayBasketStats = res.data.sundayBasket;
                this.membersJoiningStats = res.data.member_joining;
                this.membersLeavingStats = res.data.member_leaving;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response,type: 'warn'});
            })
        }
    }
}
</script>
