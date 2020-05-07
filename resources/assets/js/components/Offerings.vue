<template>
    <div class="padding">
        <div class="btn-groups m-b">
            <button class="btn primary" data-toggle="modal" data-target="#basket">Add tithe</button>
            <button class="btn primary">Tithe history</button>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-4">
                <div class="box p-a">
                    <div class="clear">
                        <h4 class="m-0 text-lg _400">Last month <span class="text-sm">in tithes</span></h4>
                    </div>
                    <div class="box-body">
                        <h5 class="m-0 text-md _300">MWK 10,000</h5>
                        <small>By 20 members</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-4">
                <div class="box p-a">
                    <div class="clear">
                        <h4 class="m-0 text-lg _400">This month <span class="text-sm">in tithes</span></h4>
                    </div>
                    <div class="box-body">
                        <h5 class="m-0 text-md _300">MWK 12,500</h5>
                        <small>By 20 members</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-4">
                <div class="box p-a">
                    <div class="clear">
                        <h4 class="m-0 text-lg _400">Last year this month <span class="text-sm">in tithes</span></h4>
                    </div>
                    <div class="box-body">
                        <h5 class="m-0 text-md _300">MWK 12,500</h5>
                        <small>By 20 members</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="form-group col-sm-3 pull-right">
                            <select class="form-control form-control-sm">
                                <option>2018</option>
                                <option>2017</option>
                                <option>2016</option>
                                <option>2015</option>
                            </select>
                        </div>
                    </div>
                <line-chart
                    v-bind:data="data"
                    v-bind:options="options"
                    :width="400"
                    :height="300">
                </line-chart>
                </div>
            </div>
        </div>
        <!-- .modal -->
        <div id="offering" class="modal fade" data-backdrop="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Offering</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <form role="form" @submit.prevent="makeOffering">
                        <div class="form-group">
                            <label for="offering_type_id" class="fonrm-control">Offering type</label>
                            <select name="offering_type_id" v-model="offering_type_id" class="form-control form-control-sm">
                                <option v-for="offer_type in offer_types" :value="offer_type.id" :key="offer_type.id"> {{offer_type.offering_title}}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="offering_amount" class="pull-left">Offering amount</label>
                            <div class="input-group">
                                <div class="input-group-addon form-control-sm">MWK</div>
                                <input type="text" v-model="offering_amount" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="btn-groups">
                            <button type="button" class="btn danger p-x-md" data-dismiss="modal" @click="resetForm">Cancel</button>
                            <button type="submit" class="btn primary m-b" data-dismiss="modal" @click="makeOffering">Submit</button>
                        </div>
                    </form>
                </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
        <!-- / .modal -->
    </div>
</template>
<script>
import { mapState } from "vuex";
import axios from './../interceptor';

export default {
    data:function(){
        return{
            offering_type_id: null,
            offer_types: null,
            offering_amount: 0,
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [
                    {
                    label: 'GitHub Commits',
                    backgroundColor: '#0cc2aa',
                    data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 100],
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: 'blue',
                    }
                ]
            },
            options: {responsive: true, maintainAspectRatio:false}
        }
    },
    created(){
        this.getOfferTypes();
    },
    mounted:function(){
    },
    methods:{
        getOfferTypes(){
            axios.get('/offering_types')
            .then(res =>{
                this.offer_types = res.data.data;
            })
            .catch(err =>{
                console.log(err.response);
            })
        }
    }
}
</script>
