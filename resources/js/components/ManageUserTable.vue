<template>
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Employees > Manage Employees</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Employees</a></li>
                <li class="active">Manage Employees</li>
            </ol>
        </section>
        <section class="content" >


            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="fa fa-users"></i>

                            <h3 class="box-title">Manage Employee</h3>

                            <div class="box-tools pull-right">

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="table" class="col-xs-12 table-responsive">
                                <div class="row">
                                    <div class="col-xs-6 form-inline margin-bottom-1 ">
                                        <div class="form-group float-left">
                                            <label for="filter" class="sr-only">Filter</label>
                                            <input type="text" class="form-control myInput" id="myInput" v-model="filter" @keyup="filtter()"  placeholder="Filter">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 form-inline margin-bottom-1 ">
                                        <div class="form-group float-right">
                                        <router-link to="/admin/add-user" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> {{this.$parent.role == 'partner' || this.$parent.role == 'manager' ? "Add Staff" : "Add New"}}</router-link>
                                        </div>
                                    </div>
                                </div>
                                <datatable id="attendanceTable" :columns="columns" :data="all_employees" class="table-bordered">

                                    <template slot-scope="{ row }">
                                        <tr>
                                            <td>{{row.first_name}}</td>
                                            <td>{{row.last_name}}</td>
                                            <td>{{row.email}}</td>
                                            <td>{{row.role_name}}</td>

                                            <td>

                                                <toggle-button v-if="$parent.role != 'manager'" @change="onChangeStatus(row.user_id,row.status)" :value="row.status"   v-model="row.status"   :labels="{checked: 'ON', unchecked: 'OFF'}" style="margin-left: 20px" />
                                                <button v-if="row.user_id != 1" class="btn btn-danger btn-sm btn-flat" @click="deleteEmployee(row.user_id)" ><i class="fa fa-trash-o"></i></button>
                                                <button class="btn btn-success btn-sm btn-flat" @click="viewProfile(row.user_id)"><i class="fa fa-user-circle-o"></i></button>
                                                <button class="btn btn-warning btn-sm btn-flat" @click="editEmployee(row.user_id)"><i class="fa fa-edit"></i></button>
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
        </section>
    </div>

</template>

<script>
    import main from './script/main'
    export default {
        data() {
            return {
                all_employees: [],
                columns: [
                    // { label: 'ID',  filterable: true, field: 'emp.name' },
                    { label: 'First Name', field: 'emp.today_date',sortable:false },
                    { label: 'Last Name', field: 'emp.name',sortable:false },
                    { label: 'Email', field: 'emp.name',sortable:false },
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
            this.getAllUsers();

        },
        methods: {
            filtter()
            {
                var value = this.filter.toLowerCase();
                $("#attendanceTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            },
            getAllUsers() {
                let self = this;
                Promise.resolve(main.sendPOSTRequest('getAllUsers',{'key' :"nun"})).then(function (response) {
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
                var md5=require('md5');
                this.$router.push({'name': 'admin-edit-user', params: {id: user_id,hash:md5(user_id)}});
            },
            viewProfile(id)
            {
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
