<template>

    <div class="col-md-4 col-sm-12">
        <div class="box">
            <div class="box-header accent">
                <h3>Departments</h3>
            </div>
            <div class="box-body">
                <form role="form" class="form-inline m-b-1" @submit.prevent="onSubmitDepartment()">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" placeholder="department Title"
                        v-model="department_name" required>
                    </div>
                    <button type="submit" class="btn btn-sm" :class="editDepartment? 'warning': 'primary'">{{ editDepartment? "Update": "Submit"}}</button>
                </form>
                <div class="list-group m-b">
                    <a class="list-group-item justify-content-between" v-for="department in departments"
                    :key="department.id" @click="editDepartmentData(department)">
                        {{department.name}}
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
            departments: null,
            departmentId: null,
            department_name: '',
            editDepartment: false,
        }
    },
    created(){
        this.getDepartments();
    },
    mounted(){
    },
    methods:{
        getDepartments(){
            axios.get('departments')
            .then(res =>{
                this.departments = res.data.data;
            })
            .catch(err =>{
                console.log(err.response);
            });
        },
        onSubmitDepartment(){
            if(this.editDepartment === true){
                this.updateDepartment();
            }
            else{
                this.addDepartment();
            }
        },
        editDepartmentData(department){
            this.departmentId = department.id;
            this.department_name = department.name;
            this.editDepartment = true;
        },
        addDepartment(){
            let id = this.departmentId;
            let department_name = this.department_name;
            axios.post('/departments', {'id':id, 'name': department_name})
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getDepartments();
                this.department_name = '';
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        updateDepartment(){
            let id = this.departmentId;
            let department_name = this.department_name;
            axios.put('/departments/'+id, {'id':id, 'name': department_name})
            .then(res =>{
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                this.getDepartments();
                this.department_name = '';
                this.editDepartment = false;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        }
    }
}
</script>


