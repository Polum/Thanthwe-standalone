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

	    <div class="col-xs-12 col-sm-12 col-md-3" v-for="homecell in homecells" :key="homecell.id">
	        <div class="box p-a">
                <ul class="nav pull-right">
                    <li class="nav-item inline dropdown">
                        <a class="nav-link" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons md-18"></i>
                        </a>
                        <div class="dropdown-menu pull-right">
                            <a class="dropdown-item" @click="editHomecell(homecell)" data-toggle="modal" data-target="#m">Edit</a>
                            <a class="dropdown-item" @click="deleteHomecell(homecell.id)">Delete</a>
                        </div>
                    </li>
                </ul>
                <div class="pull-left m-r">
                    <span class="w-48 rounded primary">
                    <i class="material-icons">&#xe84f;</i>
                    </span>
                </div>
                <div class="clear">
                    <h4 class="m-0 text-lg _300">{{homecell.name}}</h4>
                    <small class="text-muted">{{homecell.members_count}} Members.</small>
                </div>
	        </div>
	    </div>
	</div>
    <!-- .modal -->
    <div id="m" class="modal fade" data-backdrop="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Homecel</h5>
            </div>
            <div class="modal-body text-center p-lg">
                <form role="form" @submit.prevent="onSubmit">
                    <input class="form-control" id="id" v-if="id !== null" :value="id" type="hidden">
                    <div class="form-group">
                        <label for="homecel-name" class="pull-left">Name</label>
                        <input class="form-control" id="homecel-name" v-model="name" placeholder="Optional" type="text">
                    </div>

                    <div class="form-group">
                        <label for="location" class="pull-left">Location</label>
                        <input class="form-control" id="location" v-model="location" placeholder="Area 18b" type="text">
                    </div>
                    <div class="form-group">
                        <label for="zone_id" class="fonrm-control">Zone</label>
                        <select name="zone_id" v-model="zone_id" class="form-control form-control-sm" required>
                            <option v-for="zone in zones" :value="zone.id" :key="zone.id"> {{zone.name}}</option>
                        </select>
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
import axios from './../../interceptor';

export default {
    data(){
        return{
            id: null,
            name: '',
            location: '',
            homecells: null,
            zone_id: null,
            zones: null,
            update: false
        }
    },
    mounted(){
        if(this.$store.state.isAdmin){
            this.getHomecells();
            this.getZones();
        }
    },
    methods:{
        getHomecells(){
            axios.get('/homecells').then(res =>{ this.homecells = res.data.data; } )
            .catch(err => { console.log(err); });
        },
        getZones(){
            axios.get('/zones').then(res =>{ this.zones = res.data.data; } )
            .catch(err => { console.log(err); });
        },
        onSubmit:function(){
            if(this.update === false){ this.addHomecell(); }
            else if(this.update === true) { this.updateHomecell(); }
        },
        addHomecell(){
            const { name, location, zone_id } = this;
            axios.post('/homecells', { name, location, zone_id } )
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.resetForm();
                this.getHomecells();
            })
            .catch(err => {
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        editHomecell(homecell){
            this.id = homecell.id;
            this.zone_id = homecell.zone_id;
            this.name = homecell.name,
            this.location = homecell.location,
            this.update = true;
        },
        updateHomecell(){
            const { id, name, location, zone_id } = this;
            axios.put('/homecells/'+this.id, { id, name, location, zone_id } )
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.resetForm();
                this.getHomecells();
            })
            .catch(err => {
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        deleteHomecell(id){
            if(confirm("Delete Homecell?")){
                axios.delete('/homecells/'+id)
                .then(res =>{
                    this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                    this.getHomecells();
                    this.resetForm();
                })
                .catch(err => {
                    this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
                });
            }
        },
        resetForm(){
            this.name = '';
            this.location = '';
            this.zone_id = null;
        }
    }
}
</script>
