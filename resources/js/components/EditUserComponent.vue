<template >
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Employees > Edit User</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-user"></i> Employees</a></li>
                <li class="active">Edit User</li>
            </ol>
        </section>
        <section class="content" >
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-pencil"></i>

                        <h3 class="box-title">Edit User</h3>

                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label"> First Name</label>
                            <input type="text" class="form-control" v-model="employee_data.first_name" placeholder="First Name">
                            <span class="help-block" v-show="error.first_name">First Name can't be empty!</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> Last Name</label>
                            <input type="text" class="form-control" v-model="employee_data.last_name" placeholder="Last Name">
                            <span class="help-block" v-show="error.last_name">Last Name can't be empty!</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> Email</label>
                            <input type="text" class="form-control" v-model="employee_data.email"  placeholder="Email">
                            <span class="help-block" v-show="error.email">Email can't be empty!</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> Password</label>
                            <input type="text" class="form-control" v-model="employee_data.password" placeholder="Password">
                            <span class="help-block" v-show="error.password">Password can't be empty!</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> Role</label>
                            <select v-if="this.$route.name == 'admin-edit-user'" class="form-control" v-model="employee_data.role_id">
                                <option value="0">--Select--</option>
                                <option v-for="r in roles" :value="r.id">{{r.name.toUpperCase()}}</option>
                            </select>
                            <select v-if="this.$route.name == 'user-edit-profile'" class="form-control" v-model="employee_data.role_id">
                                <option value="0">--Select--</option>
                                <option :value="employee_data.role_id">{{employee_data.name.toUpperCase()}}</option>
                            </select>
                            <span class="help-block" v-show="error.role">Role can't be empty!</span>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                        <button type="button" class="btn btn-warning btn-flat pull-right" @click="updateUser()"><i class="fa fa-plus"></i> Update User</button>
                        <button type="button" class="btn btn-primary btn-flat pull-right" @click="back()">Back</button>
                    </div>
                    <div  v-if="this.$parent.overlay" class="overlay">
                        <i class="fa fa-refresh fa-spin color-green"></i>
                    </div>
                </div>
            </div>

        </div>
        </section>
    </div>


</template>

<script>
    import main from "./script/main"
    export default {
        data()
        {
            return{
                error: {'first_name':false,'last_name':false,
                    'email':false,'password':false,'role':false},
                employee_data:{},
                roles:[],
            }
        },
        mounted() {
            let self=this;
            self.getRoles();
            self.getUsersWithThisID();
        },
        methods: {
            validEmail(email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            getRoles(){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('getRolesForEdit',{key:self.$route.name})).then(function (response) {
                    self.roles=response.data;
                });
            },
            getUsersWithThisID(){
                let self=this;
                var md5 = require('md5');
                if(md5(self.$route.params.id) != self.$route.params.hash){
                    self.$router.push({'name':'dashboard'});
                    console.log('asfsafsafsafsa');
                }
                    Promise.resolve(main.sendPOSTRequest('getUsersWithThisID',{'id':self.$route.params.id})).then(function (response) {
                        self.employee_data=response.data;
                    });
                },
            updateUser(){
                let self=this;
                if(self.employee_data.first_name.trim().length != 0)
                {
                    self.error.first_name=false
                }
                if(self.employee_data.last_name.trim().length != 0)
                {
                    self.error.last_name=false;
                }
                if(self.employee_data.email.trim().length != 0 || self.validEmail(self.employee_data.email))
                {
                    self.error.email=false;
                }
                if(self.employee_data.role != "0")
                {
                    self.error.role=false;
                }
                ////Checking Condition Making True Error
                if(self.employee_data.first_name.trim().length == 0)
                {
                    self.error.first_name=true;
                }
                else if(self.employee_data.last_name.length == 0)
                {
                    self.error.last_name=true;
                }
                else if(self.employee_data.email.trim().length == 0 || !self.validEmail(self.employee_data.email))
                {
                    self.error.email=true;
                }
                else if(self.employee_data.role == "0")
                {
                    self.error.role=true;
                }
                else{
                    Promise.resolve(main.sendPOSTRequest('updateUser',self.employee_data)).then(function (response) {
                        if(response.data == "duplicate"){
                            Vue.$toast.warning("User with this email already present");
                        }else if(response.data == "success"){
                            Vue.$toast.success("User Updated successfully");
                        }else if(response.data == "error"){
                            Vue.$toast.error("Unknown Error Occurred");
                        }
                    });
                }
            },
            back(){
              this.$router.go(-1);
            }

        }
    }
</script>
