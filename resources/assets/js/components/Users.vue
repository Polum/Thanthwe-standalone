<template>
    <div class="padding">
        <div v-if="isAdmin">
        <ul class="nav nav-md nav-pills nav-active-primary clearfix m-b-1">
            <button class="btn btn-md accent pull-right" data-toggle="modal" data-target="#m">
                <i class="material-icons md-24 m-r-xs">&#xe145;</i>Add User
            </button>
        </ul>

        <div class="row row-sm">
            <div class="col-xs-12 col-lg-4" v-if="users" v-for="user in users" :key="user.id">
                <div class="list-item box r m-b">
                    <ul class="nav pull-right">
                        <li class="nav-item inline dropdown">
                            <a class="nav-link" data-toggle="dropdown" aria-expanded="false">
                                <i class="material-icons md-18"></i>
                            </a>
                            <div class="dropdown-menu pull-right">
                                <a class="dropdown-item" @click="editUser(user)" data-toggle="modal" data-target="#m">Edit</a>
                                <a class="dropdown-item" @click="deleteUser(user.id)">Delete</a>
                            </div>
                        </li>
                    </ul>
                    <a herf="" class="list-left">
                        <span class="w-40 circle teal"><i class="material-icons md-24">&#xe7fd;</i></span>
                    </a>
                    <div class="list-body">
                        <div class="text-ellipsis"><a href="">{{user.name}}</a></div>
                        <small class="text-muted text-ellipsis">{{user.role}}</small>
                    </div>
                </div>
            </div>
        </div>
        <!-- .modal -->
        <div id="m" class="modal fade" data-backdrop="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{update ? 'Update' : 'Add'}} User</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <form role="form" @submit.prevent="onSubmit">
                        <input class="form-control" id="id" v-if="id !== null" :value="id" type="hidden">
                        <input class="form-control" id="church_id" v-if="church_id !== null" :value="church_id" type="hidden">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="pull-left">Full Name</label>
                                    <input class="form-control form-control-sm" v-model="name" id="name" placeholder="John Doe" type="text" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="pull-left">Email</label>
                                    <input class="form-control form-control-sm" v-model="email" id="email" placeholder="John@example.com" type="text" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="pull-left">Password</label>
                                    <input class="form-control form-control-sm" v-model="password" id="password" placeholder="password" type="password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_2" class="pull-left">Re-enter password</label>
                                    <input class="form-control form-control-sm" v-model="c_password"
                                    id="password_2" placeholder="password" type="password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="pull-left">Phone</label>
                                    <input class="form-control form-control-sm" v-model="phone" id="phone" placeholder="0999123456" type="tel" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address" class="pull-left">Address</label>
                                    <input class="form-control form-control-sm" v-model="address" id="address" placeholder="Area 47, sector 3, house_no 212" type="text">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="role" class="fonrm-control">Role</label>
                                    <select name="role_id" v-model="role_id" class="form-control form-control-sm" required>
                                        <option v-for="role in roles" :value="role.id" :key="role.id"> {{role.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="homecell_id" class="fonrm-control">Homecell</label>
                                    <select name="homecell_id" v-model="homecell_id" class="form-control form-control-sm">
                                        <option v-for="homecell in homecells" :value="homecell.id" :key="homecell.id"> {{homecell.name}}</option>
                                    </select>
                                    <small class="text-muted text-ellipsis warning">For homecell managers only</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="occupation" class="pull-left">occupation</label>
                                    <input class="form-control form-control-sm" v-model="occupation" id="occupation" placeholder="Doctor" type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee">Employee</label><br/>
                                    <label class="radio">
                                        <input name="employee" v-model="employee" id="employee" value="1" class="has-value" type="radio" required> Yes
                                    </label>
                                    <label class="radio">
                                        <input name="employee" v-model="employee" id="employee" value="0" class="has-value" type="radio" required> No
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="btn-groups">
                            <button type="button" class="btn danger p-x-md" data-dismiss="modal" @click="resetForm">Cancel</button>
                            <button type="submit" class="btn primary m-b" data-dismiss="modal" @click="onSubmit">Submit</button>
                        </div>
                    </form>
                </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
        <!-- / .modal -->
        </div>
        <access-error v-else></access-error>
    </div>
</template>
<script>
import { mapState } from "vuex";
import axios from './../interceptor';

export default {
    data:function(){
        return{
            users: null,
            id: null,
            church_id: null,
            name: '',
            email: '',
            password: '',
            c_password: '',
            phone: '',
            address: '',
            employee: '',
            occupation: '',
            role_id: null,
            roles:null,
            homecell_id: null,
            homecells:null,
            update: false,
        }
    },
    created(){
    },
    mounted: function(){
        let store = this.$store.state;
        if(store.isAdmin){
            this.getRoles();
            this.getUsers();
            this.getHomecells();
        }
    },
    computed:{
        ...mapState(['isAdmin']),
    },
    methods:{
        getRoles: function(){
            axios.get('/roles').then(res =>{ this.roles = res.data; } )
            .catch(err => {});
        },
        getHomecells(){
            axios.get('/homecells').then(res =>{ this.homecells = res.data.data; } )
            .catch(err => {});
        },
        getUsers:function(){
            axios.get('/users')
            .then(res =>{
                this.users = res.data;
            })
            .catch(err => {
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        onSubmit:function(){
            if(this.update === false){ this.addUser(); }
            else if(this.update === true) { this.updateUser(); }
        },
        addUser:function(){
            const {name, email, password, c_password, phone,
            address, employee, occupation, role_id, homecell_id} = this;
            axios.post('/users', {name, email, password, c_password, phone,
            address, employee, occupation, role_id, homecell_id})
            .then(res => {
                this.resetForm();
                this.getUsers();
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
             })
            .catch(err => {
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        editUser:function(user){
            this.id = user.id;
            this.church_id = user.church_id;
            this.name = user.name;
            this.email = user.email;
            this.phone = user.phone;
            this.address = user.address;
            this.employee = user.employee;
            this.occupation = user.occupation;
            this.role_id = user.role_id;
            this.homecell_id = user.homecell_id;
            this.update = true;
        },
        updateUser:function(){
            const {id, church_id, name, email, password, c_password, phone,
            address, employee, occupation, role_id, homecell_id} = this;
            axios.put('/users', {id, church_id, name, email, password, c_password, phone,
            address, employee, occupation, role_id, homecell_id})
            .then(res => {
                this.resetForm();
                this.getUsers();
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
            })
            .catch(err => {
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        deleteUser:function(id){
            if(confirm("Delete user?")){
                axios.delete('/users/'+id)
                .then(res =>{
                    this.getUsers();
                    this.resetForm();
                    this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                } )
                .catch(err => {
                    this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
                });
            }
        },
        resetForm:function(){
            this.id = null;
            this.churh_id = null;
            this.name = null;
            this.email = null;
            this.phone = null;
            this.address = null;
            this.employee = null;
            this.occupation = null;
            this.role_id = null;
            this.homecell_id = null;
            this.update = false;
        }
    }
}
</script>
