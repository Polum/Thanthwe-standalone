<template>

    <div class="col-md-4 col-sm-12">
        <div class="box">
            <div class="box-header info">
                <h3>Committee Positions</h3>
            </div>
            <div class="box-body">
                <form role="form" class="form-inline m-b-1" @submit.prevent="onSubmitCommittee()">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" placeholder="committee Title"
                        v-model="name" required>
                    </div>
                    <button type="submit" class="btn btn-sm" :class="editCommittee? 'warning': 'primary'">{{ editCommittee? "Update": "Submit"}}</button>
                </form>
                <div class="list-group m-b">
                    <a class="list-group-item justify-content-between" v-for="committee in committees"
                    :key="committee.id" @click="editCommitteeData(committee)">
                        {{committee.name}}
                    </a>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import { mapState } from "vuex";
import axios from './../../interceptor';

export default {
    data(){
        return {
            committees: null,
            committeeId: null,
            name: '',
            editCommittee: false,
        }
    },
    created(){
        this.getCommittees();
    },
    mounted(){
    },
    methods:{
        getCommittees(){
            axios.get('committee_types')
            .then(res =>{
                this.committees = res.data.data;
            })
            .catch(err =>{
                console.log(err.response);
            });
        },
        onSubmitCommittee(){
            if(this.editCommittee === true){
                this.updateCommittee();
            }
            else{
                this.addCommittee();
            }
        },
        editCommitteeData(committee){
            this.committeeId = committee.id;
            this.name = committee.name;
            this.editCommittee = true;
        },
        addCommittee(){
            let id = this.committeeId;
            let name = this.name;
            axios.post('/committee_types', {'id':id, 'name': name})
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getCommittees();
                this.name = '';
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        updateCommittee(){
            let id = this.committeeId;
            let name = this.name;
            axios.put('/committee_types/'+id, {'id':id, 'name': name})
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getCommittees();
                this.name = '';
                this.editCommittee = false;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        }
    }
}
</script>


