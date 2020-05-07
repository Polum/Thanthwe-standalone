<template>
<div class="padding">
    <div class="row">
	    <div class="col-xs-12 col-sm-12 col-md-3" v-for="zone in zones" :key="zone.id">
	        <div class="box p-a">
                <div class="pull-left m-r">
                    <span class="w-48 rounded primary">
                    <i class="material-icons">&#xe84f;</i>
                    </span>
                </div>
                <div class="clear">
                    <h4 class="m-0 text-lg _300">{{zone.name}}</h4>
                    <small class="text-muted">{{zone.homecells_count}} Homecells.</small>
                </div>
	        </div>
	    </div>
	</div>
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
            location: '',
            zones: null,
            church_id: null,
            update: false
        }
    },
    mounted(){
        this.getZones();
    },
    methods:{
        getZones(){
            axios.get('/zones').then(res =>{ this.zones = res.data.data; } )
            .catch(err => { console.log(err); });
        }
    }
}
</script>
