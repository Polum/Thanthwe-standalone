<template>
    <div class="col-md-3 col-sm-12">
        <div class="box">
            <div class="box-header warn">
                <h3>Denominations</h3>
            </div>
            <div class="box-body">
                <form role="form" class="form-inline m-b-1" @submit.prevent="onSubmit()">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" placeholder="Denomination"
                        v-model="denomination" required>
                    </div>
                    <button type="submit" class="btn btn-sm primary">Submit</button>
                </form>
                <div class="list-group m-b">
                    <a class="list-group-item justify-content-between" v-for="denomination in denominations"
                    :key="denomination.id" @click="editDenomination(denomination)">
                        {{denomination.denomination}}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
import axios from './../../interceptor';

export default {
    data(){
        return {
            id: 0,
            denomination: '',
            denominations: null,
            edit: false
        }
    },
    mounted(){
        this.getDenominations();
    },
    methods:{
        getDenominations(){
            axios.get('/denominations/paged')
            .then(res =>{
                this.denominations = res.data.data;
            })
            .catch(err =>{
                console.log(err.response);
            });
        },
        editDenomination(denomination){
            this.id = denomination.id;
            this.denomination = denomination.denomination;
            this.edit = true;
        },
        onSubmit(){
            if(this.edit === true){
                this.updateDenomination();
            }
            else{
                this.addDenomination();
            }
        },
        addDenomination(){
            const { id, denomination } = this;
            axios.post('/denominations', { id, denomination })
            .then(res =>{
                this.getDenominations();
                this.denomination = '';
            })
            .catch(err =>{
                console.log(err.response);
            });
        },
        updateDenomination(){
            const { id, denomination } = this;
            axios.put('/denominations/'+this.id, { id, denomination })
            .then(res =>{
                this.getDenominations();
                this.denomination = '';
                this.edit = false;
            })
            .catch(err =>{
                console.log(err.response);
            });
        }
    }
}
</script>
