<template>
    <div class="padding">
        <div class="p-y-lg clearfix">
            <div class="text-center">

                <div class="list" data-ui-list="b-r b-3x b-primary" data-ui-list-target="#detail" data-ui-list-target-class="show">
                    <div class="list-item box r m-b" v-for="(member, index) in members" :key="index"
                    @click="add(index)" v-if="!readyToSubmit">
                        <div class="list-body">
                        <div class="item-title">
                            <a href="#" class="_500"> {{member.first_name}} {{member.last_name}} </a>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="list" data-ui-list="b-r b-3x b-primary" data-ui-list-target="#detail" data-ui-list-target-class="show">
                    <div class="list-item box r m-b" v-for="(attendant, index) in attendants" :key="index"
                    @click="remove(index)" v-if="readyToSubmit">
                        <div class="list-body">
                        <div class="item-title">
                            <a href="#" class="_500"> {{attendant.first_name}} {{attendant.last_name}} </a>
                        </div>
                        </div>
                    </div>
                </div>

                <button class="btn accent" @click="next" v-if="!readyToSubmit">continue</button>
                <button class="btn accent" @click="prev" v-if="readyToSubmit">Back</button>
                <button class="btn accent" @click="record" v-if="readyToSubmit">Submit</button>
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
            homecell_activity_id: null,
            members:[],
            attendants: [],
            attedanceBatch: [],
            readyToSubmit: false
        }
    },
    mounted(){
    },
    created(){
        if(this.$route.params.id){
            this.homecell_activity_id = this.$route.params.id;
            this.getMembers();
        }else{
            this.$router.push('/homecells');
        }
    },
    methods:{
        getMembers(){
            axios.get('/member')
            .then(res =>{ this.members = res.data; })
            .catch(err => { console.log(err.response); });
        },
        add(id){
            this.attendants.push(this.members[id]);
            this.members.splice(id, 1);
        },
        next(){
            this.readyToSubmit = true;
        },
        prev(){
            this.readyToSubmit = false;
        },
        remove(id){
            this.members.push(this.attendants[id]);
            this.attendants.splice(id, 1);
        },
        record(){
            for (var i = 0, len = this.attendants.length; i < len; i++) {
                this.someFn(this.attendants[i]);
            }
            axios.post('/homecell_activity/attendance', this.attedanceBatch)
            .then(res =>{
                this.$notify({group: 'foo',title: 'success',text: res.data.message,type: 'success'});
                this.$router.push('/homecells');
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'error',text: err.response.error,type: 'warn'});
                this.$router.push('/homecells');
            })
        },
        someFn(row){
            let record = {
                "member_id": row.id,
                "homecell_activity_id": this.homecell_activity_id
            }
            this.attedanceBatch.push(record);
        }
    }
}
</script>
