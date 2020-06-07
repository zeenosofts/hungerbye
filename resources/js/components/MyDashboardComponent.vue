<template>
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Control panel</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>
        <section class="content" >
            <!-- Info boxes -->
            <div v-if="this.$parent.role == 'superadmin' || this.$parent.role == 'editor'">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12" v-if="dashboard_data.stripe === null">
                        <div  class="callout callout-danger">
                            <h5>Please add your <router-link to="/admin/bank-details" > stripe account API KEYS</router-link> to accept and donate donations.</h5>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12" v-if="dashboard_data.card === null">
                        <div  class="callout callout-warning">
                            <h5>Please <router-link to="/admin/card_details" >add Card</router-link> for making donations.</h5>
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Partners</span>
                                <span class="info-box-number">{{dashboard_data.partners}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="fa fa-heart"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Donors</span>
                                <span class="info-box-number">{{dashboard_data.donors}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix visible-sm-block"></div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-user-secret"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Employee</span>
                                <span class="info-box-number">{{dashboard_data.staff}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Users</span>
                                <span class="info-box-number">{{dashboard_data.users}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{dashboard_data.all_requests}}</h3>

                                <p>Total Request Posted</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-smile-beam"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>{{dashboard_data.completed_requests}}</h3>

                                <p>Total Request Completed</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-grin-hearts"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3>{{dashboard_data.pending_requests}}</h3>

                                <p>Total Request Pending</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-sad-tear"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>{{dashboard_data.pending24.length}}</h3>

                                <p>Total Request Pending over 24hrs</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-money-bill-alt"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6" v-if="this.$parent.role == 'superadmin' || this.$parent.role == 'editor' || this.$parent.role != 'partner'">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>${{parseFloat(dashboard_data.donation).toFixed(2)}}</h3>

                                <p>Donations</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-money-bill-alt"></i>
                            </div>
                            <router-link class="small-box-footer" to="/requests/contributed-requests" >More info <i class="fa fa-arrow-circle-right"></i></router-link>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-primary">
                            <div class="box-header">
                                <i class="fa fa-male"></i>

                                <h3 class="box-title">New Partners Pending Requests</h3>

                                <div class="box-tools pull-right">

                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div id="table" class="col-xs-12 table-responsive">
                                    <div class="row">
                                        <div class="col-xs-12 form-inline margin-bottom-1 ">
                                            <div class="form-group float-right">
                                                <label for="filter" class="sr-only">Filter</label>
                                                <input type="text" class="form-control myInput" id="myInput" v-model="filter" @keyup="filtter()"  placeholder="Filter">
                                            </div>
                                        </div>
                                    </div>
                                    <datatable id="attendanceTable" :columns="columns" :data="all_employees" class="table-bordered">

                                        <template slot-scope="{ row }">
                                            <tr>
                                                <td>{{row.first_name}}</td>
                                                <td>{{row.last_name}}</td>
                                                <td>{{row.email}}</td>
                                                <td>{{row.business_name}}</td>
                                                <td>{{row.business_address}}</td>
                                                <td>{{row.number}}</td>
                                                <td>{{row.role_name}}</td>

                                                <td>

                                                    <toggle-button   @change="onChangeStatus(row.user_id,row.status)" :value="row.status"   v-model="row.status"  :labels="{checked: 'ON', unchecked: 'OFF'}" style="margin-left: 20px" />
                                                    <button v-if="row.user_id != 1" class="btn btn-danger btn-sm btn-flat" @click="deleteEmployee(row.user_id)"><i class="fa fa-trash-o"></i></button>
                                                    <button class="btn btn-success btn-sm btn-flat" @click="viewProfile(row.user_id)"><i class="fa fa-user-circle-o"></i></button>
                                                    <button v-if="row.type != 'google'" class="btn btn-warning btn-sm btn-flat" @click="editEmployee(row.user_id)"><i class="fa fa-edit"></i></button>
                                                </td>
                                            </tr>
                                        </template>
                                    </datatable>
                                    <div class="row">
                                        <div class="col-xs-12 form-inline float-right">
                                            <datatable-pager  v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- /.box-body -->
                            <div class="box-footer clearfix no-border">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="this.$parent.role == 'partner' || this.$parent.role == 'staff' || this.$parent.role == 'manager'">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12" v-if="dashboard_data.stripe === 'error'">
                        <div  class="callout callout-danger">
                            <h5>Please connect your <router-link to="/partners/add-stripe" >stripe account</router-link> to accept donations.</h5>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12" v-if="dashboard_data.card === null">
                        <div  class="callout callout-warning">
                            <h5>Please <router-link to="/admin/card_details" >add Card</router-link> for making donations.</h5>
                        </div>
                    </div>

                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{dashboard_data.all_requests}}</h3>

                                <p>Total Request Posted</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-smile-beam"></i>
                            </div>
                            <router-link class="small-box-footer" to="/partner/posted-items" >More info <i class="fa fa-arrow-circle-right"></i></router-link>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>{{dashboard_data.completed_requests}}</h3>

                                <p>Total Request Completed</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-grin-hearts"></i>
                            </div>
                            <router-link class="small-box-footer" to="/partner/posted-items" >More info <i class="fa fa-arrow-circle-right"></i></router-link>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3>{{dashboard_data.pending_requests}}</h3>

                                <p>Total Request Pending</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-sad-tear"></i>
                            </div>
                            <router-link class="small-box-footer" to="/partner/posted-items" >More info <i class="fa fa-arrow-circle-right"></i></router-link>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>${{parseFloat(dashboard_data.donation).toFixed(2)}}</h3>

                                <p>Donations</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-money-bill-alt"></i>
                            </div>
                            <router-link class="small-box-footer" to="/requests/contributed-requests" >More info <i class="fa fa-arrow-circle-right"></i></router-link>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="this.$parent.role === 'donor'">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12" v-if="this.$parent.logined_user.card_token === null">
                        <div  class="callout callout-warning">
                            <h5>Please <router-link to="/admin/card_details" >add Card</router-link> for making donations.</h5>
                        </div>
                    </div>

                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>${{parseFloat(dashboard_data.donation).toFixed(2)}}</h3>

                                <p>Donations</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-money-bill-alt"></i>
                            </div>
                            <router-link class="small-box-footer" to="/requests/contributed-requests" >More info <i class="fa fa-arrow-circle-right"></i></router-link>

                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3>{{dashboard_data.pending_requests}}</h3>

                                <p>Total Open Requests</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-sad-tear"></i>
                            </div>
                            <router-link to="/requests/opened-requests" class="small-box-footer">
                                <i class="fa fa-arrow-circle-right"></i> <span>Open Requests</span>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->


        </section>
    </div>

</template>

<script>
    import main from './script/main'
    export default {
      //  el:'#table',
        data()
        {
            return{
             error: '',
                dashboard_data:[],
                emp:[],
                all_leaves:[],
                schedule:{},
                all_employees: [],
                columns: [
                    // { label: 'ID',  filterable: true, field: 'emp.name' },
                    { label: 'First Name', field: 'emp.today_date',sortable:false },
                    { label: 'Last Name', field: 'emp.name',sortable:false },
                    { label: 'Email', field: 'emp.name',sortable:false },
                    { label: 'Business Name', field: 'emp.name',sortable:false },
                    { label: 'Address', field: 'emp.name',sortable:false },
                    { label: 'Number', field: 'emp.name',sortable:false },
                    { label: 'Role', field: 'emp.name',sortable:false },
                    { label: 'Action', field: 'emp.name',sortable:false },

                ],
                rows: window.rows,
                page: 1,
                per_page: 10,
                filter: '',

            }
        },
        mounted() {
            let self=this;
            self.get_dashboard_for_admin();
            self.getAllUsers();
        },
        methods:{
            get_dashboard_for_admin(){
                let self=this;
                axios.post('get_my_dashboard').then(function (response) {
                    self.dashboard_data=response.data;
                }).catch(function (error) {

                });
            },
            filtter()
            {
                var value = this.filter.toLowerCase();
                $("#attendanceTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            },
            getAllUsers() {
                let self = this;
                Promise.resolve(main.sendPOSTRequest('getAllPartnersWithStatus',{'key' :"nun"})).then(function (response) {
                    self.all_employees = response.data;
                });
            },
            onChangeStatus(user_id, status) {
                var data={
                    'user_id' :user_id,
                    'status' : status
                };
                Promise.resolve(main.sendPOSTRequest('change_status',data)).then(function (response) {
                    //self.all_employees.stat = response.data;
                });
            },
            editEmployee(user_id) {
                this.$router.push({'name': 'admin-edit-user', params: {id: user_id}});
            },
            viewProfile(id)
            {
                console.log("ASdsa");
                this.$router.push({'name':'user-view',params:{'id':id}});
            },
            deleteEmployee(id)
            {
                let self=this;
                Vue.dialog
                    .confirm('Please confirm to continue')
                    .then(function(dialog) {
                        axios.post('delete_employee', {id}).then(function (response) {

                            if(response.data == "deleted"){
                                Vue.$toast.success("User Deleted!");
                                self.getAllUsers();
                            }

                        }).catch(function (error) {
                            Vue.$toast.error(error);

                        });
                    })
                    .catch(function() {
                        console.log('Clicked on cancel');
                    });
            }
        }
    }
</script>
