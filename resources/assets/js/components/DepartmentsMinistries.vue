<template>
    <div class="padding">
        <div class="row">
            <ministries></ministries>
            <departments></departments>
            <committees></committees>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="box">
                    <div class="box-header accent">
                        <h3>Offering Types</h3>
                    </div>
                    <div class="box-body">
                        <form role="form" class="form-inline m-b-1" @submit.prevent="onSubmit()">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm" placeholder="Offering Title"
                                v-model="offering_title" required>
                            </div>
                            <button type="submit" class="btn btn-sm primary">Submit</button>
                        </form>
                        <div class="list-group m-b">
                            <a class="list-group-item justify-content-between" v-for="offeringType in offeringTypes"
                            :key="offeringType.id" @click="editOfferingType(offeringType)">
                                {{offeringType.offering_title}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import axios from './../interceptor';
import Ministries from './partials/Ministries';
import Departments from './partials/Departments';
import Committees from './partials/Committees';

export default {
    components:{
        'ministries': Ministries,
        'departments': Departments,
        'committees': Committees,
    },
    data(){
        return {
            offeringTypeId: null,
            offering_title: '',
            offeringTypes: null,
        }
    },
    created(){
        this.getOfferingTypes();
    },
    mounted(){
    },
    methods:{
        getOfferingTypes(){
            axios.get('offering-types')
            .then(res =>{
                this.offeringTypes = res.data;
            })
            .catch(err =>{
                console.log(err.response);
            });
        },
        onSubmit(){
            if(this.editOffering === true){
                this.updateOfferingType();
            }
            else{
                this.addOfferingType();
            }
        },
        editOfferingType(offeringType){
            this.offeringTypeId = offeringType.id;
            this.offering_title = offeringType.offering_title;
            this.editOffering = true;
        },
        addOfferingType(){
            let id = this.offeringTypeId;
            let offering_title = this.offering_title;
            axios.post('/offering-types', {'id':id, 'offering_title': offering_title})
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getOfferingTypes();
                this.offering_title = null;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        updateOfferingType(){
            let id = this.offeringTypeId;
            let offering_title = this.offering_title;
            axios.put('/offering-types/'+id, {'id':id, 'offering_title': offering_title})
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getOfferingTypes();
                this.offering_title = null;
                this.editOfferingType = false;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
    }
}
</script>


