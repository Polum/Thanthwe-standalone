<template>
<div class="app-body">

<!-- ############ PAGE START-->

<div class="item">
    <div class="item-bg primary">
    </div>
    <div class="p-a-md">
      <div class="row m-t">
        <div class="col-sm-7">
          <div class="clear m-b">
            <h4 class="m-a-0 m-b-sm"> {{user.name}} </h4>
            <p class="text-muted"><span class="m-r"> {{user && user.role && user.role.name}} </span></p>
          </div>
        </div>
        <div class="col-sm-5">
          <p class="text-md profile-status"> {{church.name}} </p>
        </div>
      </div>
    </div>
  </div>
  <div class="white bg b-b p-x">
    <div class="row">
      <div class="col-sm-6 push-sm-6">
        <div class="p-y text-center text-sm-right">
          <a href="#" class="inline p-x text-center">
            <span class="h4 block m-a-0"></span>
            <small class="text-xs text-muted"></small>
          </a>
          <a href="#" class="inline p-x b-l b-r text-center">
            <span class="h4 block m-a-0"></span>
            <small class="text-xs text-muted"></small>
          </a>
          <a href="#" class="inline p-x text-center">
            <span class="h4 block m-a-0"></span>
            <small class="text-xs text-muted"></small>
          </a>
        </div>
      </div>
      <div class="col-sm-6 pull-sm-6">
        <div class="p-y-md clearfix nav-active-info">
          <ul class="nav nav-pills nav-sm">
            <li class="nav-item">
              <a class="nav-link active" href="#" data-toggle="tab" data-target="#tab_profile">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-toggle="tab" data-target="#tab_church">Church</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-toggle="tab" data-target="#tab_edit">Edit Profile</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="padding">
    <div class="row">
      <div class="col-sm-8 col-lg-9">
        <div class="tab-content">
          <div class="tab-pane p-v-sm active" id="tab_profile">
            <div class="row m-b">
              <div class="col-xs-6">
                <small class="text-muted">Phone</small>
                <div class="_500">{{user.phone}}</div>
              </div>
              <div class="col-xs-6">
                <small class="text-muted">Email</small>
                <div class="_500"> {{user.email}} </div>
              </div>
            </div>
            <div class="row m-b">
              <div class="col-xs-6">
                <small class="text-muted">Occupation</small>
                <div class="_500"> {{user.occupation}} </div>
              </div>
              <div class="col-xs-6">
                <small class="text-muted">Manager</small>
                <div class="_500">James Richo</div>
              </div>
            </div>
            <div>
              <small class="text-muted">Address</small>
              <div> {{user.address}} </div>
            </div>
          </div>
          <div class="tab-pane p-v-sm" id="tab_church">
                <div class="row m-b">
                    <div class="col-xs-6">
                        <small class="text-muted">Name</small>
                        <div class="_500">{{church.name}}</div>
                    </div>
                    <div class="col-xs-6">
                        <small class="text-muted">Email</small>
                        <div class="_500"> {{church.email}} </div>
                    </div>
                </div>
                <div class="row m-b">
                    <div class="col-xs-6">
                        <small class="text-muted">Country</small>
                        <div class="_500">{{church.country}}</div>
                    </div>
                    <div class="col-xs-6">
                        <small class="text-muted">Phone</small>
                        <div class="_500"> {{church.phone}} </div>
                    </div>
                </div>
                <div class="row m-b">
                    <div class="col-xs-6">
                        <small class="text-muted">Region</small>
                        <div class="_500">{{church.district}}</div>
                    </div>
                    <div class="col-xs-6">
                        <small class="text-muted">Address</small>
                        <div class="_500"> {{church.address}} </div>
                    </div>
                </div>
          </div>
          <div class="tab-pane p-v-sm" id="tab_edit">
              <form role="form" @submit.prevent="editPassword">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="old_password" class="pull-left">Old Password</label>
                        <input class="form-control form-control-sm" v-model="old_password" id="old_password" placeholder="password" type="password" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="new_password" class="pull-left">New Password</label>
                        <input class="form-control form-control-sm" v-model="new_password" id="new_password" placeholder="password" type="password" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_2" class="pull-left">Re-enter password</label>
                        <input class="form-control form-control-sm" v-model="c_password"
                        id="password_2" placeholder="password" type="password" required>
                        <span class="danger" v-if="(new_password !== c_password)">Passwords do not match</span>
                    </div>
                </div>
                <div class="btn-groups">
                    <button type="reset" class="btn danger p-x-md">Cancel</button>
                    <button type="submit" class="btn primary m-b" @click="editPassword">Save</button>
                </div>
              </form>
          </div>
        </div>
      </div>
      <div class="col-sm-4 col-lg-3" v-if="isAdmin">
        <div class="box primary dk">
            <div class="box-body">
                <div class="clear">
                    <h5>Subscription</h5>
                    <span class="p-y block text-center accent dk" ui-toggle-class="">
                        <h1 class="block">{{daysLeft}}</h1>
                        <span class="block">Days left</span>
                    </span>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

<!-- ############ PAGE END-->

</div>
</template>
<script>
import { mapState } from "vuex";
import axios from './../interceptor';
import moment from 'moment';

export default {
    data(){
        return{
            id: null,
            old_password: '',
            new_password: '',
            c_password: '',
            daysLeft: null,
            church: [],
            memberCount: null
        }
    },
    created(){
        this.id = this.$store.getters.user.id;
        this.daysLeft = moment(this.$store.getters.user.subscription.subscription_end_date).diff(moment(), 'days');
        this.getChurch();
        this.getMemberCount();
    },
    mounted(){
    },
    computed:{
        ...mapState(['user', 'isAdmin']),
    },
    methods:{
        getChurch(){
            axios.get('/church').then(res =>{
                this.church = res.data.data;
            })
            .catch(err => { console.log(err); });
        },
        getMemberCount(){
            axios.get('/memberCount').then(res =>{ this.memberCount = res.data.data; })
            .catch(err => { console.log(err); });
        },
        editPassword(){
            const { id, old_password, new_password, c_password } = this;
            axios.post('/user/edit-password', { id, old_password, new_password, c_password })
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        }
    }

}
</script>
