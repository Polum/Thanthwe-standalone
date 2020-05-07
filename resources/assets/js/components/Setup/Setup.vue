<template>
    <div class="padding">
        <form role="form" class="p-a" @submit.prevent="setup()">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label for="key">Key</label>
                        <input type="text" id="key" v-model="key" class="form-control form-control-sm" required>
                    </div>
                </div>
            </div>
            <button class="btn btn-md accent">Submit</button>
        </form>
    </div>
</template>
<script>
import axios from './../../interceptor';
import { setupUrl } from './../../config';

export default {
    data(){
        return {
            key: null,
        }
    },
    mounted(){
        this.setup();
    },
    methods:{
        setup(){
            const { key } = this;
            axios.post(setupUrl, { key })
            .then(res =>{
                console.log(res.data);
            })
            .catch(err =>{
                console.log(err.response);
            });
        }
    }

}
</script>
