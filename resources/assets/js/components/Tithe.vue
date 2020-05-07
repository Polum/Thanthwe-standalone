<template>
    <div class="padding">
        <div class="btn-groups m-b" v-if="isAccountant || isClerk">
            <button class="btn primary" data-toggle="modal" data-target="#tithe">Add tithe</button>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-4">
                <div class="box p-a">
                    <div class="clear">
                        <h4 class="m-0 text-lg _400">Last month <span class="text-sm">in tithes</span></h4>
                    </div>
                    <div class="box-body">
                        <h5 class="m-0 text-md _300">MWK {{last_month | formatNumber}} </h5>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-4">
                <div class="box p-a">
                    <div class="clear">
                        <h4 class="m-0 text-lg _400">This month <span class="text-sm">in tithes</span></h4>
                    </div>
                    <div class="box-body">
                        <h5 class="m-0 text-md _300">MWK {{this_month | formatNumber}} </h5>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-4">
                <div class="box p-a">
                    <div class="clear">
                        <h4 class="m-0 text-lg _400">Last year this month <span class="text-sm">in tithes</span></h4>
                    </div>
                    <div class="box-body">
                        <h5 class="m-0 text-md _300">MWK {{last_year_this_month | formatNumber}} </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="form-group col-sm-3 pull-right">
                            <select class="form-control form-control-sm" v-model="year" @change="loadGraphData">
                                <option v-if="year < current_year" :value="current_year">Current Year</option>
                                <option :value="year">{{year}}</option>
                                <option :value="year-1">{{year-1}}</option>
                                <option :value="year-2">{{year-2}}</option>
                                <option :value="year-3">{{year-3}}</option>
                                <option :value="year-4">{{year-4}}</option>
                            </select>
                        </div>
                    </div>
                <line-chart v-if="loaded"
                    v-bind:data="graphData"
                    v-bind:options="options"
                    :width="400"
                    :height="300">
                </line-chart>
                </div>
            </div>
        </div>

        <!-- .modal -->
        <div id="tithe" class="modal fade" data-backdrop="true"  v-if="isAccountant || isClerk">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Tithe</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <form role="form" @submit.prevent="makeTithe">
                        <div class="form-group">
                            <label for="envelop_no" class="pull-left">envelop number</label>
                            <input class="form-control" v-model="envelop_no" placeholder="1">
                        </div>

                        <div class="form-group">
                            <label for="offering_amount" class="pull-left">Tithe amount</label>
                            <div class="input-group">
                                <div class="input-group-addon form-control-sm">MWK</div>
                                <input type="text" v-model="offering_amount" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="offering_date" class="pull-left">Date</label>
                            <input class="form-control" v-model="offering_date" type="date" required>
                        </div>
                        <div class="btn-groups">
                            <button type="reset" class="btn danger p-x-md" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn primary m-b" data-dismiss="modal" @click="makeTithe">Submit</button>
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
import moment from 'moment';

export default {
    data:function(){
        return{
            envelop_no: null,
            offering_amount: 0,
            this_month: 0,
            last_month: 0,
            last_year_this_month: 0,
            year: moment().year(),
            current_year: moment().year(),
            offering_date: null,
            graphData: {labels:[], datasets:[]},
            options: {responsive: true, maintainAspectRatio:false},
            loaded: false
        }
    },
    created(){
        this.loadTitheStats();
        this.loadGraphData();
    },
    mounted(){
    },
    computed:{
        ...mapState(['isAccountant', 'isClerk', 'isAdmin']),
    },
    methods:{
        loadTitheStats(){
            axios.get('/tithe/stats')
            .then(res =>{
                this.this_month = res.data.this_month;
                this.last_month = res.data.last_month;
                this.last_year_this_month = res.data.last_year_this_month;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: 'Error collecting tithe stats',type: 'warn'});
            })
        },
        loadGraphData(){
            this.loaded = false;
            axios.get('/tithe/graph_data/'+this.year)
            .then(res =>{
                this.graphData = res.data;
                this.loaded = true;
            })
            .catch(err =>{
                console.log(err.response);
                this.$notify({group: 'foo',title: 'Error',text: 'Error loading graph data',type: 'warn'});
            })
        },
        makeTithe(){
            const { offering_date, envelop_no, offering_amount } = this;
            axios.post('/tithe', { offering_date, envelop_no, offering_amount })
            .then(res =>{
                this.loadGraphData();
                this.loadTitheStats();
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            })
        },
        resetForm(){}
    }
}
</script>
