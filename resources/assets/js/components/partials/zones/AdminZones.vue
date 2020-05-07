<template>
<div class="padding">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-3">
            <a class="btn btn-lg btn-block white m-r-xs m-b" style="height:90px;"
            data-toggle="modal" data-target="#m">
              <span class="pull-left m-r-sm">
                <i class="material-icons md-48 pull-center">&#xe145;</i>
              </span>
            </a>
	    </div>

	    <div class="col-xs-12 col-sm-12 col-md-3" v-for="zone in zones" :key="zone.id">
	        <div class="box p-a">
                <ul class="nav pull-right">
                    <li class="nav-item inline dropdown">
                        <a class="nav-link" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons md-18"></i>
                        </a>
                        <div class="dropdown-menu pull-right">
                            <a class="dropdown-item" @click="editZone(zone)" data-toggle="modal" data-target="#m">Edit</a>
                            <a class="dropdown-item" @click="deleteZone(zone.id)">Delete</a>
                        </div>
                    </li>
                </ul>
                <div class="pull-left m-r">
                    <span class="w-48 rounded primary">
                    <i class="material-icons">&#xe84f;</i>
                    </span>
                </div>
                <div class="clear">
                    <h4 class="m-0 text-lg _300">{{zone.name}}</h4>
                    <small class="text-muted">{{zone.homecells_count}} homecells.</small>
                </div>
	        </div>
	    </div>
	</div>
    <!-- .modal -->
    <div id="m" class="modal fade" data-backdrop="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Zone</h5>
            </div>
            <div class="modal-body text-center p-lg">
                <form role="form" @submit.prevent="onSubmit">
                    <input class="form-control" id="id" v-if="id !== null" :value="id" type="hidden">
                    <div class="form-group">
                        <label for="zone-name" class="pull-left">Name</label>
                        <input class="form-control" id="zone-name" v-model="name" placeholder="example" type="text">
                    </div>

                    <div class="form-group">
                        <label for="areas" class="pull-left">areas</label>
                        <input class="form-control" id="areas" v-model="areas" placeholder="Chinsapo, area 3, area 6" type="text">
                    </div>
                    <div class="btn-groups">
                        <button type="button" class="btn danger p-x-md" data-dismiss="modal" @click="resetForm">Cancel</button>
                        <button type="submit" class="btn primary m-b" data-dismiss="modal" @click="onSubmit">Submit</button>
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
import axios from './../../../interceptor';

export default {
    data(){
        return{
            id: null,
            name: '',
            areas: '',
            zones: null,
            update: false
        }
    },
    mounted(){
        if(this.$store.state.isAdmin){
            this.getzones();
        }
    },
    methods:{
        getzones(){
            axios.get('/zones').then(res =>{ this.zones = res.data.data; } )
            .catch(err => { console.log(err); });
        },
        onSubmit:function(){
            const userData = JSON.parse(localStorage.getItem('user'));
            this.church_id = userData.church_id;
            if(this.update === false){ this.addZone(); }
            else if(this.update === true) { this.updateZone(); }
        },
        addZone(){
            const { name, areas } = this;
            axios.post('/zones', { name, areas } )
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.resetForm();
                this.getzones();
            })
            .catch(err => {
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        editZone(zone){
            this.id = zone.id;
            this.name = zone.name,
            this.areas = zone.areas,
            this.update = true;
        },
        updateZone(){
            const { id, name, areas } = this;
            axios.put('/zones/'+this.id, { id, name, areas } )
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.resetForm();
                this.getzones();
            })
            .catch(err => {
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        deleteZone(id){
            if(confirm("Delete zone?")){
                axios.delete('/zones/'+id)
                .then(res =>{
                    this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                    this.getzones();
                    this.resetForm();
                })
                .catch(err => {
                    this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
                });
            }
        },
        resetForm(){
            this.name = '';
            this.areas = '';
        }
    }
}
</script>
