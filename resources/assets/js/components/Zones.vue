<template>
<div>
	<admin-zone v-if="isAdmin"></admin-zone>
    <zone-list v-else></zone-list>
</div>
</template>
<script>
import { mapState } from "vuex";
import AdminZone from './partials/zones/AdminZones';
import ZoneList from './partials/zones/ZoneList';

export default {
    props:{},
    components:{
        'admin-zone': AdminZone,
        'zone-list' : ZoneList
    },
    data(){
        return{
        }
    },
    computed:{
        ...mapState(['isAdmin']),
    }
}
</script>
