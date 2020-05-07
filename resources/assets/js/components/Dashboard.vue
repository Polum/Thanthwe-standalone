<template>
<div class="padding">
	<div class="row">
		<div class="col-xs-12 col-sm-4">
	        <div class="box p-a">
	          <div class="pull-left m-r">
	            <span class="w-48 rounded  accent">
	              <i class="fa fa-group"></i>
	            </span>
	          </div>
	          <div class="clear">
	            <h4 class="m-0 text-lg _300">
                    <router-link class="nav-link" to ="/members"><span class="text-sm">Members</span></router-link>
                </h4>
	            <small class="m-0 text-md _500">{{memberCount}}</small>
	          </div>
	        </div>
	    </div>
	    <div class="col-xs-6 col-sm-4">
	        <div class="box p-a">
	          <div class="pull-left m-r">
	            <span class="w-48 rounded primary">
	              <i class="material-icons">&#xe84f;</i>
	            </span>
	          </div>
	          <div class="clear">
	            <h4 class="m-0 text-lg _300">
                    <router-link class="nav-link" to ="/homecells"><span class="text-sm">Homecels</span> </router-link> </h4>
	            <small class="m-0 text-md _500">{{homecells}}</small>
	          </div>
	        </div>
	    </div>
	    <div class="col-xs-6 col-sm-4">
	        <div class="box p-a">
	          <div class="pull-left m-r">
	            <span class="w-48 rounded warn">
	              <i class="material-icons">&#xe8d3;</i>
	            </span>
	          </div>
	          <div class="clear">
	            <h4 class="m-0 text-lg _300">
                    <router-link class="nav-link" to ="/users">60 <span class="text-sm">Users</span></router-link>
                </h4>
	            <small class="text-muted">.</small>
	          </div>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-md-6">
			<div class="box">
				<div class="box-header">
					<h3>Activities</h3>
				</div>
				<div class="box-body">
				  	<div class="streamline">
				        <div class="sl-item b-warning" v-for="activity in activities" :key="activity.id">
				          <div class="sl-content">
				            <div class="sl-date text-muted"> {{activity.start_date}} </div>
				            <div> {{activity.name}} </div>
				          </div>
				        </div>
				    </div>
				</div>
			  	<div class="box-footer">
			  		<router-link to ="/upcoming-activities" class="btn btn-sm warn text-u-c">More</router-link>
			  	</div>
		  	</div>
		</div>
        <div class="col-sm-12 col-md-6">
	    	<div class="box">
		      <div class="box-header">
		        <h3>Stats</h3>
		        <small>Your data status</small>
		      </div>
		      <table class="table">
		        <thead>
		          <tr>
		            <th>Means of joining</th>
		            <th style="width:70px;">Percentage</th>
		          </tr>
		        </thead>
		        <tbody>
		          <tr v-for="(stat,index) in member_stats" :key="index">
		            <td>{{stat.means}}</td>
		            <td :class="stat.percent<50? 'text-danger': 'text-success'">
		              {{stat.percent}}%
		            </td>
		          </tr>
		        </tbody>
		      </table>
		    </div>
	    </div>
	</div>
</div>
</template>
<script>
import { mapState } from "vuex";
import axios from './../interceptor';

export default {
    data(){
        return{
            memberCount: null,
            activities: {},
            homecells: 0,
            member_stats: {}
        }
    },
    created(){
        this.getMemberCount();
        this.getDashboard();
    },
    methods:{
        getMemberCount(){
            axios.get('/memberCount')
            .then(res =>{
                this.memberCount = res.data.data;
            })
            .catch(err => {
                console.log(err);
            });
        },
        getDashboard(){
            axios.get('/dashboard')
            .then(res =>{
                this.activities = res.data.activities;
                this.homecells = res.data.homecells;
                this.member_stats = res.data.member_stats;
            })
            .catch(err =>{
                console.log(err.response);
            })
        }
    }
}
</script>
