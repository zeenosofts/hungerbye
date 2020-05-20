<template >
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Employees > Add User</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-user"></i> Employees</a></li>
                <li class="active">Add User</li>
            </ol>
        </section>
        <section class="content" >
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-user-plus"></i>

                        <h3 class="box-title">Add User</h3>

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
                            <label class="control-label"> Email </label>
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
                            <select class="form-control" v-model="employee_data.role">
                                <option value="0">--Select--</option>
                                <option v-for="r in roles" :value="r.id">{{r.name.toUpperCase()}}</option>
                            </select>
                            <span class="help-block" v-show="error.role">Role can't be empty!</span>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                        <button type="button" class="btn btn-primary btn-flat pull-right" @click="saveUser()"><i class="fa fa-plus"></i> Add User</button>
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
                employee_data:{'first_name':'','last_name':'',
                    'email':'','password':'',role:'0'},
                roles:[],
                role:''
            }
        },
        mounted() {
            let self=this;
            self.getRoles();
        },
        methods: {
            validEmail(email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            getRoles(){
                let self=this;
                    Promise.resolve(main.sendPOSTRequest("getRoles",{key:self.$route.name})).then(function (response) {
                        self.roles=response.data;
                    });
            },
            saveUser(){
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
                if(self.employee_data.password.trim().length != 0)
                {
                    self.error.password=false;
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
                else if(self.employee_data.last_name.trim().length == 0)
                {
                    self.error.last_name=true;
                }
                else if(self.employee_data.email.trim().length == 0 || !self.validEmail(self.employee_data.email))
                {
                    self.error.email=true;
                }
                else if(self.employee_data.password.trim().length == 0)
                {
                    self.error.password=true;
                }
                else if(self.employee_data.role == "0")
                {
                    self.error.role=true;
                }
                else{
                    Promise.resolve(main.sendPOSTRequest('saveUser',self.employee_data)).then(function (response) {
                        if(response.data == "duplicate"){
                            Vue.$toast.warning("User with this email already present");
                        }else if(response.data == "success"){
                            Vue.$toast.success("User saved successfully");
                            self.$router.push({'name':'admin-manage-user'});
                        }else if(response.data == "error"){
                            Vue.$toast.error("Unknown Error Occurred");
                        }
                    });
                }
            }
        }
    }
</script>
