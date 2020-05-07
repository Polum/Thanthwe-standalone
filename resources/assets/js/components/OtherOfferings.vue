<template>
    <div class="padding">
        <div class="btn-groups m-b" v-if="isAccountant || isClerk">
            <button class="btn primary" data-toggle="modal" data-target="#offering">Add offering</button>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="form-group col-sm-3">
                            <select class="form-control form-control-sm" v-model="year" @change="stats">
                                <option v-if="year < current_year" :value="current_year">Current Year</option>
                                <option :value="year">{{year}}</option>
                                <option :value="year-1">{{year-1}}</option>
                                <option :value="year-2">{{year-2}}</option>
                                <option :value="year-3">{{year-3}}</option>
                                <option :value="year-4">{{year-4}}</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3 pull-left">
                            <select class="form-control form-control-sm" v-model="month" @change="stats">
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
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-4" v-for="(offer, index) in offerings" :key="index">
                <div class="box p-a">
                    <div class="clear">
                        <h4 class="m-0 text-lg _400"> {{offer.offer}} </h4>
                    </div>
                    <div class="box-body">
                        <h5 class="m-0 text-md _300">MWK {{offer.total | formatNumber}} </h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- .modal -->
        <div id="offering" class="modal fade" data-backdrop="true" v-if="isAccountant || isClerk">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Offering</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <form role="form" @submit.prevent="recordOffering">
                        <div class="row mb m-b-2">
                            <div class="col-md-6">
                                <label for="requested-by">Offering types</label>
                                <select class="form-control form-control-sm" v-model="offering_type_id" required @change="checkOther">
                                    <option v-for="offer in offer_types" :key="offer.id" :value="offer.id" class="form-control form-control-sm"> {{offer.offering_title}} </option>
                                </select>
                            </div>

                            <div class="col-md-6" v-if="other">
                                <label for="service_no" class="pull-left">Other offer (specify)</label>
                                <input class="form-control" v-model="other_type" type="text">
                            </div>
                        </div>

                        <div class="row mb m-b-2">
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input checked="" v-model="by_member" :value="true" type="checkbox">
                                        <i class="dark-white"></i>
                                        By member
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6" v-if="by_member">
                                <label for="envelop_no" class="pull-left">envelop number</label>
                                <input class="form-control" v-model="envelop_no" placeholder="1">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="offering_amount" class="pull-left">Offering amount</label>
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
                            <button type="submit" class="btn primary m-b" data-dismiss="modal" @click="recordOffering">Submit</button>
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
    data(){
        return {
            envelop_no: 0,
            offerings: [],
            offer_types: [],
            offering_type_id: 0,
            by_member: false,
            other_type: null,
            offering_amount: 0,
            offering_date: '',
            current_year: moment().year(),
            year: moment().year(),
            month: moment().month()+1,
            other: false
        }
    },
    created(){
        let store = this.$store.getters;
        if(store.isClerk || store.isAccountant){
            this.getOfferTypes();
        }
        this.stats();
    },
    mounted(){
    },
    computed:{
        ...mapState(['isAccountant', 'isClerk', 'isAdmin']),
    },
    methods:{
        getOfferTypes(){
            axios.get('/offering_types')
            .then(res =>{
                this.offer_types = res.data;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: 'Offering types data missing.',type: 'warn'});
            })
        },
        stats(){
            axios.get('/other-offering/stats/'+this.year+'/'+this.month)
            .then(res =>{
                this.offerings = res.data;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: 'Error collecting offering stats',type: 'warn'});
            })
        },
        recordOffering(){
            const { other_type, envelop_no, offer_types, offering_type_id, offering_amount, offering_date } = this;
            axios.post('/other-offering', { other_type, envelop_no, offer_types, offering_type_id, offering_amount, offering_date })
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.stats();
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        checkOther(){
            let other = this.offer_types.find(x => x.id === this.offering_type_id);
            if(other.offering_title === 'other'){
                this.other = true;
            }
            else{
                this.other = false;
            }
        },
        resetForm(){
            this.envelop_no = 0;
            this.offerings = [];
            this.offer_types = [];
            this.offering_type_id = 0;
            this.by_member = false;
            this.other_type = null;
            this.offering_amount = 0;
            this.offering_date = '';
            this.year = moment().year();
            this.month = moment().month()+1;
            this.other = false;
        }
    }
}
</script>
