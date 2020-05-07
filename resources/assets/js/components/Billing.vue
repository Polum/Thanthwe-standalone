<template>
    <div class="padding">
        <div class="text-md text-primary">
            <p><i class="fa fa-briefcase"></i> Billing</p>
        </div>

        <div class="text-md">
            <p>Current Billing Period:</p>
        </div>

        <div class="text-md">
            <p>Billing history</p>
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
                        <tr v-for="bill in billing" :key="bill.id">
                            <td> {{ bill.subscription_start_date }} </td>
                            <td> Subscription billing for {{ bill.months }} </td>
                            <td> -- </td>
                            <td>
                                <button class="btn btn-sm btn-outline b-primary text-primary">
                                View Details</button>
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
                                    @click="getBilling(pagination.prev_page_url)">
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
                                    @click="getBilling(pagination.next_page_url)">
                                    <i class="fa fa-chevron-right"></i></a>
                            </li>
                        </ul>
                    </div>
                    </div>
                </footer>
            </div>
        </div>

    </div>
</template>

<script>
import { mapState } from "vuex";
import axios from './../interceptor';
import { billingURL } from './../config';

export default {
    data:function(){
        return{
            columns: [
                { title: 'Date', field: 'transaction_date' },
                { title: 'Description', field: 'description' },
                { title: 'Amount', field: 'transaction_amount' },
            ],
            billing: [],
            total: 0,
            pagination: {}
        }
    },
    mounted: function(){
        let store = this.$store.state;
        if(store.isAdmin){
            this.getBilling();
        }
    },
    computed:{
        ...mapState(['isAdmin']),
    },
    methods:{
        getBilling: function(page_url){
            page_url = page_url || billingURL;
            axios.get(page_url)
            .then(res =>{
                console.log(res)
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
                this.billing = data.data;
                this.total = data.total;
            } )
            .catch(err => {
                console.log(err.response);
            });
        }
    }
}
</script>
