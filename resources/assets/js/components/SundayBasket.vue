<template>
    <div class="padding">
        <div class="btn-groups m-b" v-if="isAccountant || isClerk">
            <button class="btn primary" data-toggle="modal" data-target="#basket">Add basket offering</button>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="form-group col-sm-3">
                            <select class="form-control form-control-sm" v-model="year" @change="graphBasket">
                                <option v-if="year < current_year" :value="current_year">Current Year</option>
                                <option :value="year">{{year}}</option>
                                <option :value="year-1">{{year-1}}</option>
                                <option :value="year-2">{{year-2}}</option>
                                <option :value="year-3">{{year-3}}</option>
                                <option :value="year-4">{{year-4}}</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3 pull-left">
                            <select class="form-control form-control-sm" v-model="month" @change="graphBasket">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                <bar-chart v-if="loaded"
                    v-bind:data="graphData"
                    v-bind:options="options"
                    :width="400"
                    :height="300">
                </bar-chart>
                </div>
            </div>
        </div>

        <!-- .modal -->
        <div id="basket" class="modal fade" data-backdrop="true" v-if="isAccountant || isClerk">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Offering</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <form role="form" @submit.prevent="makeOffering">
                        <div class="form-group">
                            <label for="service_no" class="pull-left">Service number</label>
                            <select class="form-control form-control-sm" v-model="service_no">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="total_offerings" class="pull-left">Total offerings</label>
                            <div class="input-group">
                                <div class="input-group-addon form-control-sm">MWK</div>
                                <input type="text" v-model="total_offerings" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="offering_date" class="pull-left">Date</label>
                            <input class="form-control" v-model="offering_date" type="date" required>
                        </div>
                        <div class="btn-groups">
                            <button type="reset" class="btn danger p-x-md" data-dismiss="modal">Cancel</button>
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
import moment from 'moment';
import axios from './../interceptor';

export default {
    data(){
        return{
            loaded: false,
            service_no: 0,
            total_offerings: 0,
            offering_date: '',
            current_year: moment().year(),
            year: moment().year(),
            month: moment().month()+1,
            graphData: {labels:[], datasets:[]},
            options: {responsive: true, maintainAspectRatio:false}
        }
    },
    created(){
        this.graphBasket();
    },
    mounted(){
    },
    computed:{
        ...mapState(['isAccountant', 'isClerk', 'isAdmin']),
    },
    methods:{
        graphBasket(){
            this.loaded=false;
            axios.get('/sunday-basket/'+this.year+'/'+this.month)
            .then(res =>{
                this.graphData = res.data;
                this.loaded = true;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: 'Error collecting graph data.',type: 'warn'});
            })
        },
        makeOffering(){
            const { service_no, total_offerings, offering_date } = this;
            axios.post('/sunday-basket', { service_no, total_offerings, offering_date})
            .then(res =>{
                this.resetForm();
                this.graphBasket();
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            })

        },
        resetForm(){
            this.service_no = 0;
            this.total_offerings = 0;
        }
    }
}
</script>
