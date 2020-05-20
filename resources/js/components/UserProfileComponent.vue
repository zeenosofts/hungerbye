<template >
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Profile > View Profile</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Profile</a></li>
                <li class="active">View Profile</li>
            </ol>
        </section>
        <section class="content" >
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-user-circle"></i>

                        <h3 class="box-title">Profile</h3>

                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <label>First Name</label><br>
                                <label>Last Name</label><br>
                                <label>Email</label><br>
                                <label>Role</label><br>
                                <div v-if="employee_data.name == 'partner'">
                                    <label>Business Name</label><br>
                                    <label>Business Address</label><br>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <label>{{employee_data.first_name}}</label><br>
                                <label>{{employee_data.last_name}}</label><br>
                                <label>{{employee_data.email}}</label><br>
                                <label>{{employee_data.name.toUpperCase()}}</label><br>
                                <div v-if='employee_data.name == "partner"'>
                                    <label>{{employee_data.business_name}}</label><br>
                                    <label>{{employee_data.business_address}}</label><br>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                        <button type="button" class="btn btn-primary btn-flat pull-right" @click="back">Back</button>
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
                Promise.resolve(main.sendPOSTRequest('getRoles',{key:"nun"})).then(function (response) {
                    self.roles=response.data;
                });
            },
            back(){
              this.$router.go(-1);
            },
            getUsersWithThisID(){
                let self=this;

                    Promise.resolve(main.sendPOSTRequest('getUsersWithThisID',{'id':self.$route.params.id})).then(function (response) {
                        self.employee_data=response.data;
                    });
                },

        }
    }
</script>
