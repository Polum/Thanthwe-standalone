<template>
    <div class="padding">
        <form role="form" @submit.prevent="searchMember" class="clearfix m-b">
            <div class="input-group input-group-md">
            <input class="form-control form-control-md" v-model="keyword" placeholder="Type keyword" type="text">
            <span class="input-group-btn">
                <button class="btn btn-md b-a no-shadow white" type="submit">Search</button>
            </span>
            </div>
        </form>

        <p class="m-b-md" v-if="searchResults"><strong> {{searchResults.total }} </strong>
        Results found for: <strong> {{keyword}} </strong>
        <button class="btn btn-sm btn-outline rounded b-accent text-accent" @click="resetForm">clear</button></p>


        <ul class="nav nav-md nav-pills nav-active-primary clearfix">
            <li class="nav-item">
                <a class="nav-link active" href="" data-toggle="tab" data-target="#members-list">All Members</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="" data-toggle="tab" data-target="#families-list">Families</a>
            </li>
            <button class="btn btn-md accent pull-right" data-toggle="modal" data-target="#m" v-if="isClerk">
                <i class="material-icons md-24 m-r-xs">&#xe145;</i> Add Member
            </button>
        </ul>

        <div class="tab-content">
            <div class="tab-pane p-v-sm active" id="members-list">
                <div class="m-t">
                    <div class="row row-sm">
                        <div class="col-xs-12 col-lg-4" v-if="!searchResults"
                        v-for="member in members" :key="member.id">
                            <div class="list-item box r m-b">
                                <ul class="nav pull-right">
                                    <span class="label blue" v-if="(member.isNew && member.deceased == null && member.left == null)">New</span>
                                    <span class="label red" v-if="(member.deceased != null)">Deceased</span>
                                    <span class="label warning" v-if="(member.left)">Left</span>
                                    <li class="nav-item inline dropdown" v-if="isHomecellManager || isClerk || isAdmin">
                                        <a class="nav-link" data-toggle="dropdown" aria-expanded="false">
                                            <i class="material-icons md-18"></i>
                                        </a>
                                        <div class="dropdown-menu pull-right">
                                            <a class="dropdown-item" data-toggle="modal" data-target="#m" @click="editMember(member)">Edit</a>
                                            <a class="dropdown-item" href="">Transfer</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#committee" @click="selectMember(member.id)">Committee member</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#leave" @click="leave(member)">Left</a>
                                        </div>
                                    </li>
                                </ul>
                            <a herf="" class="list-left">
                                <span class="w-40 circle teal"><i class="material-icons md-24">&#xe7fd;</i></span>
                            </a>
                            <div class="list-body">
                                <router-link class="nav-link" :to="{name:'member profile', params:{id: member.id }}">
                                <div class="text-ellipsis"><a href=""> {{member.first_name}} {{member.last_name}}</a></div>
                                <small class="text-muted text-ellipsis">Envelop number: {{member.envelop_no}}

                                </small>
                                </router-link>
                            </div>
                            </div>
                        </div>
                        <!-- search results -->
                        <div  v-if="searchResults">
                            <div class="col-xs-12 col-lg-4" v-for="member in searchResults.data" :key="member.id">
                                <div class="list-item box r m-b">
                                    <ul class="nav pull-right">
                                        <span class="label blue" v-if="(member.isNew && member.deceased == null)">New</span>
                                        <span class="label red" v-if="(member.deceased != null)">Deceased</span>
                                        <span class="label warning" v-if="(member.left != null)">Left</span>
                                        <li class="nav-item inline dropdown" v-if="isHomecellManager || isClerk">
                                            <a class="nav-link" data-toggle="dropdown" aria-expanded="false">
                                                <i class="material-icons md-18"></i>
                                            </a>
                                            <div class="dropdown-menu pull-right">
                                                <a class="dropdown-item" data-toggle="modal" data-target="#m" @click="editMember(member)">Edit</a>>
                                                <a class="dropdown-item" href="">Transfer</a>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#leave" @click="leave(member)">Left</a>
                                            </div>
                                        </li>
                                    </ul>
                                    <a herf="" class="list-left">
                                        <span class="w-40 circle teal"><i class="material-icons md-24">&#xe7fd;</i></span>
                                    </a>
                                    <div class="list-body">
                                        <div class="text-ellipsis"><a href=""> {{member.first_name}} {{member.last_name}}</a></div>
                                        <small class="text-muted text-ellipsis">Envelop number: {{member.envelop_no}}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane p-v-sm" id="families-list">
                <div class="m-t">
                    <div class="row row-sm">
                        <div class="col-xs-12 col-lg-4" v-for="family in families" :key="family.id">
                            <div class="list-item box r m-b">
                                <ul class="nav pull-right">
                                    <li class="nav-item inline dropdown" v-if="isHomecellManager || isClerk">
                                        <a class="nav-link" data-toggle="dropdown" aria-expanded="false">
                                            <i class="material-icons md-18"></i>
                                        </a>
                                        <div class="dropdown-menu pull-right">
                                            <a class="dropdown-item" @click="addToFamily(family)" data-toggle="modal" data-target="#m">Add member</a>
                                        </div>
                                    </li>
                                </ul>
                            <a herf="" class="list-left m-r">
                                <span class="w-40 circle light-blue"><i class="material-icons md-24">&#xe63d;</i></span>
                            </a>
                            <div class="list-body">
                                <div class="text-ellipsis"><a href=""> {{family.family_name}} </a></div>
                                <small class="text-muted text-ellipsis">Designer, Blogger</small>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- .modal -->
        <div id="m" class="modal fade" data-backdrop="true" v-if="isHomecellManager || isClerk">
            <div class="modal-dialog modal_lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Member</h5>
                    </div>
                    <div class="modal-body text-center p-lg">
                        <form role="form" @submit.prevent="onSubmit">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname" class="pull-left">Firstname</label>
                                        <input class="form-control form-control-sm" v-model="first_name" id="firstname" placeholder="John" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="pull-left">Last name</label>
                                        <input class="form-control form-control-sm" v-model="last_name" id="last_name" placeholder="Doe" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dob" class="pull-left">DOB</label>
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" v-model="dob" type="date">
                                            <span class="input-group-addon form-control-sm">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Cel" class="pull-left">Cel</label>
                                        <input class="form-control form-control-sm" v-model="phone" id="Cel" placeholder="0999123456" type="tel">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="pull-left">Email</label>
                                        <input class="form-control form-control-sm" v-model="email" id="email" placeholder="john@example.com" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sex">Sex</label><br/>
                                        <label class="radio">
                                            <input name="sex" v-model="sex" id="sex" value="m" class="has-value" type="radio"> Male
                                        </label>
                                        <label class="radio">
                                            <input name="sex" v-model="sex" id="sex" value="f" class="has-value" type="radio"> Female
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="marital-status">Marital status</label><br/>
                                        <label class="radio">
                                            <input name="marital-status" v-model="marital_status" id="marital-status" value="single" class="has-value" type="radio"> Single
                                        </label>
                                        <label class="radio">
                                            <input name="marital-status" v-model="marital_status" id="marital-status" value="married" class="has-value" type="radio"> Married
                                        </label>
                                        <label class="radio">
                                            <input name="marital-status" v-model="marital_status" id="marital-status" value="divorced" class="has-value" type="radio"> Divorced
                                        </label>
                                        <label class="radio">
                                            <input name="marital-status" v-model="marital_status" id="marital-status" value="widowed" class="has-value" type="radio"> Widowed
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address" class="pull-left">Address</label>
                                        <input type="text" v-model="address" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="occupation" class="pull-left">occupation</label>
                                        <input type="text" v-model="occupation" class="form-control form-control-sm" placeholder="Doctor">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="occupation" class="pull-left">Previous Church</label>
                                        <input type="text" v-model="previous_church" class="form-control form-control-sm" placeholder="Doctor">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="member_type">Member Type</label><br/>
                                        <label class="radio">
                                            <input v-model="member_type" value="visitor" class="has-value" type="radio"> Visitor
                                        </label>
                                        <label class="radio">
                                            <input v-model="member_type" value="full member" class="has-value" type="radio"> Full member
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="zone_id" class="fonrm-control">Zone</label>
                                        <select name="zone_id" v-model="zone_id" @change="filterHomecells()" class="form-control form-control-sm">
                                            <option v-for="zone in zones" :value="zone.id" :key="zone.id"> {{zone.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="homecell_id" class="fonrm-control">Homecell</label>
                                        <select name="homecell_id" v-model="homecell_id" class="form-control form-control-sm">
                                            <option v-for="homecell in homecells" :value="homecell.id" :key="homecell.id"> {{homecell.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="joining_means" class="fonrm-control">Form of joining</label>
                                        <select name="homecell_id" v-model="joining_means" class="form-control form-control-sm">
                                            <option value="birth"> Birth </option>
                                            <option value="mariage"> Mariage </option>
                                            <option value="crusade"> Crusade </option>
                                            <option value="conversion"> conversion </option>
                                            <option value="evangelism"> Evangelism </option>
                                            <option value="new arrival"> New arrival </option>
                                        </select>
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

        <!-- .modal leave-->
        <div id="leave" class="modal fade" data-backdrop="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Member leaving</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <form role="form" @submit.prevent="memberLeft()">
                        <div class="form-group">
                            <label for="left" class="pull-left">Date left</label>
                            <input class="form-control" id="left" v-model="leave_date" placeholder="Optional" type="date">
                        </div>
                        <div class="btn-groups">
                            <button type="button" class="btn danger p-x-md" data-dismiss="modal" @click="resetForm">Cancel</button>
                            <button type="submit" class="btn primary m-b" data-dismiss="modal" @click="memberLeft">Submit</button>
                        </div>
                    </form>
                </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
        <!-- / .modal -->

        <!-- .modal committee-->
        <div id="committee" class="modal fade" data-backdrop="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Committee member</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <form role="form" @submit.prevent="memberCommittee()">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department_id" class="fonrm-control">Department</label>
                                        <select name="department_id" v-model="committeeData.department.department_id" class="form-control form-control-sm">
                                            <option v-for="department in departments" :value="department.id" :key="department.id"> {{department.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="committee_type_id" class="fonrm-control">committee Position</label>
                                        <select name="committee_type_id" v-model="committeeData.department.position_id" class="form-control form-control-sm">
                                            <option v-for="committee_type in committee_types" :value="committee_type.id" :key="committee_type.id"> {{committee_type.name}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ministry_id" class="fonrm-control">Ministry</label>
                                        <select name="ministry_id" v-model="committeeData.ministry.ministry_id" class="form-control form-control-sm">
                                            <option v-for="ministry in ministries" :value="ministry.id" :key="ministry.id"> {{ministry.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="committee_type_id" class="fonrm-control">committee Position</label>
                                        <select name="committee_type_id" v-model="committeeData.ministry.position_id" class="form-control form-control-sm">
                                            <option v-for="committee_type in committee_types" :value="committee_type.id" :key="committee_type.id"> {{committee_type.name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-groups">
                            <button type="button" class="btn danger p-x-md" data-dismiss="modal" @click="resetForm">Cancel</button>
                            <button type="submit" class="btn primary m-b" data-dismiss="modal" @click="memberCommittee">Submit</button>
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
    data:function(){
        return{
            id: null,
            family_id: null,
            first_name: '',
            last_name: '',
            sex: null,
            dob: null,
            marital_status: null,
            address: '',
            phone: '',
            email: '',
            member_type: null,
            occupation: '',
            previous_church: '',
            joining_means: '',
            join_date: '',
            leave_date: null,
            deceased: null,
            envelop_no: null,
            members: null,
            families: null,
            homecells: null,
            homecellsArray: null,
            homecell_id: null,
            has_family: false,
            zones: null,
            zone_id: null,
            committee_types: null,
            committee_type_id: null,
            departments: null,
            department_id: null,
            ministries: null,
            ministry_id: null,
            update: false,
            searchResults: null,
            keyword: '',
            committeeData: {"department":{}, "ministry":{}}
        }
    },
    created(){
    },
    computed:{
        ...mapState(['isClerk', 'isHomecellManager', 'isAdmin']),
    },
    mounted(){
        let store = this.$store.state;
        if(store.isClerk || store.isHomecellManager){
            this.getHomecells();
            this.getZones();
            this.getCommitteeTypes();
            this.getDepartments();
            this.getMinistries();
        }
        this.getMembers();
        this.getFamilies();
    },
    methods:{
        getMembers(){
            axios.get('/member')
            .then(res =>{
                this.members = res.data;
            })
            .catch(err => {
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        getZones(){
            axios.get('/zones')
            .then(res => {
                this.zones = res.data.data;
            })
            .catch(err => {
                this.$notify({group: 'fool', title: 'error', text: 'Failed to get zones data!', type:'warn'});
            });
        },
        getCommitteeTypes(){
            axios.get('/committee_types')
            .then(res => {
                this.committee_types = res.data.data;
            })
            .catch(err => {
                this.$notify({group: 'fool', title: 'error', text: 'Failed to get committee types data!', type:'warn'});
            });
        },
        getDepartments(){
            axios.get('/departments')
            .then(res => {
                this.departments = res.data.data;
            })
            .catch(err => {
                this.$notify({group: 'fool', title: 'error', text: 'Failed to get departments data!', type:'warn'});
            });
        },
        getMinistries(){
            axios.get('/ministries')
            .then(res => {
                this.ministries = res.data.data;
            })
            .catch(err => {
                this.$notify({group: 'fool', title: 'error', text: 'Failed to get ministries data!', type:'warn'});
            });
        },
        getFamilies(){
            axios.get('/families')
            .then(res =>{ this.families = res.data.data; })
            .catch(err => {
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        getHomecells(){
            axios.get('/homecells').then(res =>{ this.homecellsArray = res.data.data; })
            .catch(err => {
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        filterHomecells(){
            this.homecells = [];
            var ha = this.homecellsArray;
            for(const homecell of ha){
                if(homecell.zone_id !== this.zone_id){
                    this.homecells.push(homecell);
                }
            }
        },
        onSubmit(){
            if(this.update === false){ this.addMember(); }
            else if(this.update === true) { this.updateMember(); }
        },
        addToFamily:function(family){
            this.family_id = family.id;
            this.last_name = family.family_name
            this.has_family = true;
        },
        addMember:function(){
            const { family_id, first_name, last_name, sex, dob, marital_status, phone, email, address,
            member_type, occupation, previous_church, joining_means, homecell_id } = this;

            axios.post('/member', { family_id, first_name, last_name, sex, dob, marital_status, phone, email, address,
            member_type, occupation, previous_church, joining_means, homecell_id }).then(res => {
                this.getMembers();
                this.getFamilies();
                this.resetForm();
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        selectMember: function(id){
            this.id = id;
        },
        memberCommittee(){
            let data = [{"committee_type_id": this.committeeData.department.position_id, "member_id": this.id, "department_id": this.committeeData.department.department_id, "ministry_id":null},
                    {"committee_type_id": this.committeeData.ministry.position_id, "member_id": this.id, "department_id":null, "ministry_id": this.committeeData.ministry.ministry_id},
            ];
            // department_id = this.committeeData.department.department_id);
            // department_position_ = this.committeeData.department.position_id);
            // this.committeeData.ministry.ministry_id);
            // this.committeeData.ministry.position_id);
            axios.post('/member_committee', data)
            .then( res => {
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
                console.log(res.data);
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        editMember(member){
            this.id = member.id;
            this.family_id = member.family_id;
            this.first_name = member.first_name;
            this.last_name = member.last_name;
            this.dob = member.dob;
            this.sex = member.sex;
            this.marital_status = member.marital_status;
            this.address = member.address;
            this.phone = member.phone;
            this.email = member.email;
            this.member_type = member.member_type,
            this.occupation = member.occupation;
            this.envelop_no = member.envelop_no;
            this.previous_church = member.previous_church;
            this.joining_means = member.joining_means;
            this.join_date = member.join_date;
            this.homecell_id = member.homecell_id;
            this.deceased = member.deceased;
            this.update = true;
        },
        updateMember(){
            const { id, family_id, first_name, last_name, sex, dob, marital_status, phone, email, address,
            member_type, occupation, envelop_no, previous_church, joining_means, homecell_id,
            join_date} = this;
            axios.put('/member/'+this.id, { id, family_id, first_name, last_name, sex, dob, marital_status, phone,
            email, address, member_type, occupation, envelop_no, previous_church, joining_means, homecell_id,
            join_date }).then(res => {
                this.getMembers();
                this.getFamilies();
                this.resetForm();
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
            })
            .catch(err =>{
                this.resetForm();
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        searchMember(){
            this.searchResults = null;
            const { keyword } = this;
            axios.post('/member/search', { keyword }).then(res => {
                this.searchResults = res.data.data;
            })
            .catch(err =>{
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        leave(member){
            this.id = member.id;
        },
        memberLeft(){
            axios.put('/member/left/'+this.id+'/'+this.leave_date)
            .then(res =>{
                this.getMembers();
                this.getFamilies();
                this.$notify({group: 'foo',title: 'Success',text: res.data.message,type: 'success'});
            })
            .catch(err =>{
                this.resetForm();
                this.$notify({group: 'foo',title: 'Error',text: err.response.data.error,type: 'warn'});
            });
        },
        resetForm(){
            this.id = null;
            this.family_id = null;
            this.first_name = '';
            this.last_name = '';
            this.dob = null;
            this.sex = null;
            this.marital_status = null;
            this.address = '';
            this.phone = '';
            this.email = '';
            this.member_type = null,
            this.occupation = '';
            this.envelop_no = null;
            this.previous_church = '';
            this.joining_means = '';
            this.join_date = null;
            this.homecell_id = null;
            this.leave_date = null,
            this.deceased = null,
            this.has_family = false;
            this.update = null;
            this.searchResults = null;
            this.keyword = '';
        }
    }
}
</script>
