<template>
<div class="app-body">
    <div class="item">
        <div class="item-bg cyan-100">
        </div>
        <div class="p-a-md">
            <div class="row m-t">
                <div class="col-sm-7">
                <div class="clear m-b">
                    <h4 class="m-a-0 m-b-sm">  </h4>
                    <p class="text-muted"><span class="m-r">  </span></p>
                </div>
                </div>

                <div class="col-sm-5">
                <p class="text-md profile-status">Homecell</p>
                </div>
            </div>
        </div>
    </div>
    <div class="white bg b-b p-x">
        <div class="row">
        <div class="col-sm-6">
            <div class="p-y-md clearfix nav-active-info">
            <ul class="nav nav-pills nav-sm">
                <li class="nav-item">
                <button class="btn accent" data-toggle="modal" data-target="#activity">Activities</button>
                </li>
            </ul>
            </div>
        </div>
        <div class="col-md-6 pull-right">
            <div class="p-y-md clearfix nav-active-info">
            </div>
        </div>
        </div>
    </div>
    <div class="padding">
        <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="list-group m-b">
                <div class="list" data-ui-list="b-r b-4x b-theme">
                    <div class="list-item row-col" v-for="activity in activities" :key="activity.id">
                        <div class="list-body col-xs">
                            <router-link :to="{name:'attendance', params:{id: activity.id }}"
                            class="item-title _500"> {{activity.activity_name}} </router-link>
                            <div class="text-muted text-xs">
                                <i class="fa fa-clock-o"></i> {{activity.activity_set_date}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <nav>
                <ul class="pagination">
                    <li class="page-item">
                        <a :class="[{disabled: !pagination.prev_page_url}]"
                            class="btn page-link" aria-label="Previous"
                            @click="getActivities(pagination.prev_page_url)">
                            <span aria-hidden="true">«</span>
                            <span>Previous</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link disabled text-dark">
                            {{pagination.current_page}} of {{pagination.last_page}}
                        </a>
                    </li>

                    <li class="page-item" >
                        <a :class="[{disabled: !pagination.next_page_url}]"
                            class="btn page-link" aria-label="Next"
                            @click="getActivities(pagination.next_page_url)">
                            <span aria-hidden="true">»</span>
                            <span>Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="col-sm-12 col-md-6">
            <p></p>
        </div>
        </div>
    </div>
        <!-- .modal -->
    <div id="activity" class="modal fade" data-backdrop="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Homecel Activity</h5>
            </div>
            <div class="modal-body text-center p-lg">
                <form role="form" @submit.prevent="recordActivity">
                    <div class="form-group">
                        <label class="pull-left">Activity name</label>
                        <input class="form-control" v-model="activity_name" type="text">
                    </div>
                    <div class="form-group">
                        <label class="pull-left">Decription</label>
                        <textarea class="form-control" rows="2" v-model="activity_description"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="pull-left">Activity set date</label>
                        <input class="form-control" v-model="activity_set_date" type="date">
                    </div>
                    <div class="form-group">
                        <label class="pull-left">Start time</label>
                        <input class="form-control" v-model="activity_start_time" type="text" placeholder="11:00:00">
                    </div>
                    <div class="form-group">
                        <label class="pull-left">End time</label>
                        <input class="form-control" v-model="activity_end_time" type="text" placeholder="11:00:00">
                    </div>
                    <div class="btn-groups">
                        <button type="button" class="btn danger p-x-md" data-dismiss="modal" @click="resetForm">Cancel</button>
                        <button type="submit" class="btn primary m-b" data-dismiss="modal" @click="recordActivity">Submit</button>
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
import { homecellActivityURL } from '../../config';

export default {
    data(){
        return{
            activity_name: '',
            activity_description: '',
            activity_set_date: '',
            activity_start_time: '',
            activity_end_time: '',
            activities: [],
            pagination:{}
        }
    },
    mounted(){
        this.getActivities();
    },
    computed:{
        ...mapState(['isHomecellManager']),
    },
    methods:{
        getActivities(page_url){
            page_url = page_url || homecellActivityURL;
            axios.get(page_url)
            .then(res =>{
                let data = res.data;
                let pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    next_page_url: data.next_page_url,
                    prev_page_url: data.prev_page_url,
                }
                this.activities = data.data;
                this.pagination = pagination;

            })
            .catch(err =>{
                console.log(err.response);
            })
        },
        recordActivity(){
            const { activity_name, activity_description, activity_set_date,
            activity_start_time, activity_end_time } = this;
            axios.post('/homecell_activity', { activity_name, activity_description, activity_set_date,
            activity_start_time, activity_end_time })
            .then(res =>{
                this.$notify({group: 'foo',title: 'success',text: res.data.message,type: 'success'});
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'error',text: err.response.error,type: 'warn'});
            });
        },
        resetForm(){
            this.activity_name = '';
            this.activity_description = '';
            this.activity_set_date = '';
            this.activity_start_time = '';
            this.activity_end_time = '';
        }
    }

}
</script>
