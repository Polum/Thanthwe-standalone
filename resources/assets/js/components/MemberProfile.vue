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
          <p class="text-md profile-status"> Zone : {{member.homecell && member.homecell.zone && member.homecell.zone.name}}</p>
          <p class="text-md profile-status"> Homecell : {{member.homecell && member.homecell.name}}</p>
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
              <a class="nav-link active" href="#" data-toggle="tab" data-target="#tab_profile">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-toggle="tab" data-target="#tithe_history">Tithe history</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-toggle="tab" data-target="#committee">Committee</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-md-6 pull-right">
          <div class="p-y-md clearfix nav-active-info">
            <button class="btn btn-md pull-right warning" v-if="member.deceased == null" data-toggle="modal" data-target="#dod">
                Record death
            </button>
            <p class="pull-right text-md text-danger" v-if="member.deceased != null">DECEASED</p>
          </div>
      </div>
    </div>
  </div>
  <div class="padding">
    <div class="row">
      <div class="col-sm-12 col-md-8">
        <div class="tab-content">
          <div class="tab-pane p-v-sm active" id="tab_profile">
            <div class="row m-b">
              <div class="col-xs-6">
                <small class="text-muted">Name</small>
                <div class="_500"> {{member.first_name}} {{member.last_name}} </div>
              </div>
              <div class="col-xs-6">
                <small class="text-muted">Phone</small>
                <div class="_500"> {{member.phone}} </div>
              </div>
            </div>
            <div class="row m-b">
              <div class="col-xs-6">
                <small class="text-muted">Occupation</small>
                <div class="_500"> {{member.occupation}} </div>
              </div>
              <div class="col-xs-6">
                <small class="text-muted">Marital status</small>
                <div class="_500">{{member.marital_status}}</div>
              </div>
            </div>
            <div class="row m-b">
              <div class="col-xs-6">
                <small class="text-muted">DOB</small>
                <div class="_500"> {{member.dob}} </div>
              </div>
              <div class="col-xs-6">
                <small class="text-muted">Address</small>
                <div class="_500">{{member.address}}</div>
              </div>
            </div>
          </div>

          <!-- Tithe history -->
          <div class="tab-pane p-v-sm" id="tithe_history">
            <div class="list-group m-b">
                <div class="list-group-item" v-for="tithe in member.offering" :key="tithe.id">
                    <span class="pull-right">MWK {{ tithe.offering_amount | formatNumber }} </span>
                    {{ tithe.offering_date }}
                </div>
            </div>
          </div>

          <div class="tab-pane p-v-sm" id="committee">
            <div class="box">
                <table class="table stripped">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Ministry</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="committee in member.committee" :key="committee.id">
                            <td> {{committee.position}} </td>
                            <td> {{committee.ministry}} </td>
                            <td> {{committee.department}} </td>
                        </tr>
                    </tbody>
                </table>
            </div>
          </div>

        </div>
      </div>
      <div class="col-sm-12 col-md-4">
          <p>FAMILY MEMBERS</p>
        <div class="box">
            <ul class="list inset m-a-0">
                <li class="list-item" v-for="family_member in member.family && member.family.members" :key="family_member.id">
                    <ul class="nav pull-right">
                        <span class="label blue" v-if="(member.isNew && member.deceased == null)">New</span>
                        <span class="label red" v-if="(member.deceased != null)">Deceased</span>
                    </ul>
                    <a href="#" class="list-left">
                    <span class="w-40 circle accent">
                        <i class="material-icons md-24">&#xe7fd;</i>
                    </span>
                    </a>
                    <div class="list-body">
                        <a @click="reload(family_member.id)">
                            <div>{{ family_member.first_name }} {{ family_member.last_name }}</div>
                            <small class="text-muted text-ellipsis"> {{family_member.occupation}} </small>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- .modal -->
    <div id="dod" class="modal fade" data-backdrop="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Homecel</h5>
            </div>
            <div class="modal-body text-center p-lg">
                <form role="form" @submit.prevent="recordDeath">
                    <div class="form-group">
                        <label class="pull-left">Date of death</label>
                        <input class="form-control" v-model="deceased" type="date">
                    </div>
                    <div class="btn-groups">
                        <button type="button" class="btn danger p-x-md" data-dismiss="modal" @click="resetForm">Cancel</button>
                        <button type="submit" class="btn primary m-b" data-dismiss="modal" @click="recordDeath">Submit</button>
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
            id:null,
            deceased: null,
            member: {},
            ministries: [],
            departments: [],
            committeeTypes: [],
        }
    },
    mounted(){
        this.getMinistries();
        this.getDepartments();
        this.getCommitteeTypes();
    },
    created(){
        if(this.$route.params.id){
            this.id = this.$route.params.id;
        }else{
            this.$router.push('/members');
        }
        this.getMember();
    },
    computed:{},
    methods:{
        reload(id){
            this.id = id;
            this.getMember();
        },
        getMember(){
            axios.get('/member/profile/'+this.id)
            .then(res =>{
                this.member = res.data;
            })
            .catch(err =>{
                console.log(err.response);
            });
        },
        getMinistries(){
            axios.get('/ministries').then(res =>{ this.ministries = res.data.data ;})
            .catch(err =>{ console.log(err.response);});
        },
        getDepartments(){
            axios.get('/departments').then(res =>{ this.departments = res.data.data ;})
            .catch(err =>{ console.log(err.response);});
        },
        getCommitteeTypes(){
            axios.get('/committee_types').then(res =>{ this.committeeTypes = res.data.data ;})
            .catch(err =>{ console.log(err.response);});
        },
        filterCommittee(){
            for(const committee of this.member.committees){
                for(const department of this.departments){
                    if(committee.department_id !== department.id){
                        // console.log(comm)
                    }
                }
            }
        },
        recordDeath(){
            axios.put('/member/deceased/'+this.id+'/'+this.deceased)
            .then(res =>{
                this.getMember();
                this.$notify({group: 'foo',title: 'success',text: res.data.message,type: 'success'});
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'error',text: err.response,type: 'warn'});
            })
        },
        resetForm(){
            this.deceased = null;
        }
    }

}
</script>
