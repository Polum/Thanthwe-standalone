<template>
<div class="app-body">

<!-- ############ PAGE START-->

<div class="item">
    <div class="item-bg cyan-100">
    </div>
    <div class="p-a-md">
      <div class="row m-t">
        <div class="col-sm-7">
          <div class="clear m-b">
            <h4 class="m-a-0 m-b-sm"> {{member.first_name}} {{member.last_name}} </h4>
            <p class="text-muted"><span class="m-r"> {{member.occupation}} </span></p>
          </div>
        </div>

        <div class="col-sm-5">
          <p class="text-md profile-status"> {{member.homecell && member.homecell.name}}  Homecell</p>
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
      <div class="col-sm-12 col-md-8">

      </div>
      <div class="col-sm-12 col-md-4">
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
                        <input class="form-control" v-model="activity_start_time" type="time">
                    </div>
                    <div class="form-group">
                        <label class="pull-left">End time</label>
                        <input class="form-control" v-model="activity_end_time" type="time">
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
import axios from './../interceptor';

export default {
    data(){
        return{
            activity_name: '',
            activity_description: '',
            activity_set_date: '',
            activity_start_time: '',
            activity_end_time: ''
        }
    },
    mounted(){
    },
    methods:{
        recordActivity(){
            const { activity_name, activity_description, activity_set_date,
            activity_start_time, activity_end_time } = this;
            axios.put('/homecell/activity', { activity_name, activity_description, activity_set_date,
            activity_start_time, activity_end_time })
            .then(res =>{
                this.$notify({
                    group: 'foo',
                    title: 'success',
                    text: res.data.message,
                    type: 'warn'
                });
            })
            .catch(err =>{
                this.$notify({
                    group: 'foo',
                    title: 'error',
                    text: err.response,
                    type: 'warn'
                });
            })
        },
        resetForm(){
        }
    }

}
</script>
