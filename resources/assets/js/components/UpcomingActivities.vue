<template>
    <div class="padding">
        <div class="text-md text-primary">
            <p><i class="fa fa-briefcase"></i> UPCOMING ACTIVITIES</p>
        </div>
        <button class="btn btn-md accent m-b-2" data-toggle="modal" data-target="#activity-form"
        v-if="isClerk">
            <i class="material-icons md-24 m-r-xs">&#xe145;</i>Add activity
        </button>
        <div class="row">
            <div class="col-md-8" v-for="activity in activities" :key="activity.id">
                <div class="box p-a">
                    <ul class="nav pull-right">
                        <li class="nav-item inline dropdown">
                            <a class="nav-link" data-toggle="dropdown" aria-expanded="false">
                                <i class="material-icons md-18"></i>
                            </a>
                            <div class="dropdown-menu pull-right">
                                <a class="dropdown-item" data-toggle="modal" data-target="#activity-form" @click="editActivity(activity)">Edit</a>
                                <a class="dropdown-item" data-toggle="modal" data-target="#delete" @click="deleteActivity(activity.id)">Delete</a>
                            </div>
                        </li>
                    </ul>
                    <h5 class="m-a-0 m-b-sm"> {{ activity.name }} </h5>
                    <p class="text-muted"> {{ activity.description }} </p>
                    <p><span class="label label-md  m-r">Date: {{activity.start_date}} - {{activity.end_date}} </span>
                    <span class="label label-md m-r">Time: {{activity.start_time}} - {{activity.end_time}} </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- .modal -->
        <div id="activity-form" class="modal fade" data-backdrop="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Church Activity</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <form role="form" @submit.prevent="onSubmit">
                        <div class="form-group">
                            <label class="pull-left">Activity name</label>
                            <input class="form-control" v-model="name" type="text">
                        </div>
                        <div class="form-group">
                            <label class="pull-left">Decription</label>
                            <textarea class="form-control" rows="2" v-model="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="pull-left">Start date</label>
                            <input class="form-control" v-model="start_date" type="date">
                        </div>
                        <div class="form-group">
                            <label class="pull-left">End date</label>
                            <input class="form-control" v-model="end_date" type="date">
                        </div>
                        <div class="form-group">
                            <label class="pull-left">Start time</label>
                            <input class="form-control" v-model="start_time" type="text" placeholder="11:00:00">
                        </div>
                        <div class="form-group">
                            <label class="pull-left">End time</label>
                            <input class="form-control" v-model="end_time" type="text" placeholder="12:00:00">
                        </div>
                        <div class="btn-groups">
                            <button type="reset" class="btn danger p-x-md" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn primary m-b" data-dismiss="modal" @click="onSubmit">Submit</button>
                        </div>
                    </form>
                </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
        <!-- / .modal -->

        <!-- .modal -->
        <div id="delete" class="modal modal-sm pull-center fade" data-backdrop="true" v-if="isClerk">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <h6 class="_600">Are you sure?</h6>
                    <div class="btn-groups">
                        <button type="reset" class="btn info p-x-md" data-dismiss="modal" @click="resetForm">No</button>
                        <button type="submit" class="btn danger m-b" data-dismiss="modal" @click="destroyActivity">Yes</button>
                    </div>
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
    data(){
        return {
            id: null,
            name: '',
            description: '',
            start_date: null,
            end_date: null,
            start_time: null,
            end_time: null,
            activities: null,
            update: false,
            delete: false,
            us: null
        }
    },
    created(){
        this.getActivities();
    },
    mounted(){
    },
    computed:{
        ...mapState(['isClerk']),
    },
    methods:{
        getActivities(){
            axios.get('/activity')
            .then(res =>{
                this.activities = res.data;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            })
        },
        onSubmit(){
            if(this.update === true){
                this.updateActivity();
            }
            else{
                this.recordActivity();
            }
        },
        recordActivity(){
            const { name, description, start_date, end_date, start_time, end_time } = this;
            axios.post('/activity', { name, description, start_date, end_date, start_time, end_time })
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getActivities();
                this.resetForm();
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
                this.resetForm();
            })
        },
        updateActivity(){
            const { id, name, description, start_date, end_date, start_time, end_time } = this;
            axios.put('/activity/'+this.id, { id, name, description, start_date, end_date, start_time, end_time })
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getActivities();
                this.resetForm();
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
                this.resetForm();
            });
        },
        destroyActivity(){
            axios.delete('/activity/'+this.id)
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getActivities();
                this.resetForm();
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
                this.resetForm();
            });
        },
        editActivity(activity){
            this.id = activity.id;
            this.name = activity.name;
            this.description = activity.description;
            this.start_date = activity.start_date;
            this.end_date = activity.end_date;
            this.start_time = activity.start_time;
            this.end_time = activity.end_time;
            this.update = true;
        },
        deleteActivity(id){
            this.id = id;
            this.delete = true;
        },
        resetForm(){
            this.id = null;
            this.name = '';
            this.description = '';
            this.start_date = 2344;
            this.end_date = 123;
            this.start_time = '';
            this.end_time = '';
            this.update = false;
            this.delete = false;
        }
    }
}
</script>
