<template>

    <div class="col-md-4 col-sm-12">
        <div class="box">
            <div class="box-header primary">
                <h3>Ministries</h3>
            </div>
            <div class="box-body">
                <form role="form" class="form-inline m-b-1" @submit.prevent="onSubmit()">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" placeholder="Offering Title"
                        v-model="name" required>
                    </div>
                    <button type="submit" class="btn btn-sm" :class="editMinistry? 'warning': 'primary'">{{ editMinistry? "Update": "Submit"}}</button>
                </form>
                <div class="list-group m-b">
                    <a class="list-group-item justify-content-between" v-for="ministry in ministries"
                    :key="ministry.id" @click="editMinistryData(ministry)">
                        {{ministry.name}}
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
            ministryId: null,
            name: '',
            ministries: null,
            editMinistry: false
        }
    },
    created(){
        this.getMinistries();
    },
    mounted(){
    },
    methods:{
        getMinistries(){
            axios.get('ministries')
            .then(res =>{
                this.ministries = res.data.data;
            })
            .catch(err =>{
                console.log(err.response);
            });
        },
        onSubmit(){
            if(this.editMinistry === true){
                this.updateMinistry();
            }
            else{
                this.addMinistry();
            }
        },
        editMinistryData(ministry){
            this.ministryId = ministry.id;
            this.name = ministry.name;
            this.editMinistry = true;
        },
        addMinistry(){
            let id = this.ministryId;
            let name = this.name;
            axios.post('/ministries', {'id':id, 'name': name})
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getMinistries();
                this.name = '';
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        updateMinistry(){
            let id = this.ministryId;
            let name = this.name;
            axios.put('/ministries/'+id, {'id':id, 'name': name})
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getMinistries();
                this.name = '';
                this.editMinistry = false;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
    }
}
</script>


