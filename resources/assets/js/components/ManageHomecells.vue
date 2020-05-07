<template>
<div>
	<admin-homecell v-if="isAdmin"></admin-homecell>
    <manager-homecell v-else-if="isHomecellManager"></manager-homecell>
    <homecell-list v-else></homecell-list>
</div>
</template>
<script>
import { mapState } from "vuex";
import AdminHomecell from './partials/AdminHomecell';
import ManagerHomecell from './partials/ManagerHomecell';
import HomecellList from './partials/HomecellList';

export default {
    props:{},
    components:{
        'manager-homecell': ManagerHomecell,
        'admin-homecell': AdminHomecell,
        'homecell-list' : HomecellList
    },
    data(){
        return{
        }
    },
    computed:{
        ...mapState(['isAdmin', 'isHomecellManager']),
    }
}
</script>
