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
                                    <div class="col-xs-12 form-inline margin-bottom-1 ">
                                        <div class="form-group float-left">
                                            <label for="filter" class="sr-only">Filter</label>
                                            <input type="text" class="form-control myInput" id="myInput" v-model="filter" @keyup="filtter()"  placeholder="Filter">
                                        </div>
                                    </div>
                                </div>
                                <datatable id="attendanceTable" :columns="columns" :data="all_employees" class="table-bordered">

                                    <template slot-scope="{ row }">
                                        <tr>
                                            <td><img :src="'public/images/'+ row.photo_url" width="50px" class="align-items-center"></td>
                                            <td>{{row.id_no}}</td>
                                            <td>{{row.first_name+" "+row.last_name}}</td>

                                            <td>{{row.email}}</td>
                                            <td>{{row.mobile_number}}</td>
                                            <td>{{row.job_title}}</td>
                                            <td>{{row.employee_type}}</td>
                                            <td v-if="row.employee_status == 0">Archived</td>
                                            <td v-if="row.employee_status == 1">Active</td>

                                            <td>

                                                <toggle-button   @change="onChangeStatus(row.user_id,row.employee_status)"  v-model="row.employee_status"   :labels="{checked: 'ON', unchecked: 'OFF'}" style="margin-left: 20px" />
                                                <button   class="btn btn-success btn-sm btn-flat" @click="viewProfile(row.user_id)"><i class="fa fa-user-circle-o"></i></button>
                                                <button v-if="row.user_id != 1" class="btn btn-danger btn-sm btn-flat" @click="deleteEmployee(row.user_id)"><i class="fa fa-trash-o"></i></button>
                                                <button class="btn btn-warning btn-sm btn-flat" @click="editEmployee(row.user_id)"><i class="fa fa-edit"></i></button>
                                            </td>
                                        </tr>
                                    </template>
                                </datatable>
                                <div class="row">
                                    <div class="col-xs-12 form-inline float-right">
                                        <datatable-pager  v-model="page" type="short" :per-page="per_page"></datatable-pager>
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
    export default {
        data() {
            return {
                all_employees: [],
                columns: [
                    // { label: 'ID',  filterable: true, field: 'emp.name' },
                    { label: 'Profile', field: 'emp.today_date',sortable:false },
                    { label: 'Employee ID', field: 'emp.name',sortable:false },
                    { label: 'Employee Name', field: 'emp.name',sortable:false },
                    { label: 'Email', field: 'emp.name',sortable:false },
                    { label: 'Phone Number', field: 'emp.name',sortable:false },
                    { label: 'Job Title', field: 'emp.name',sortable:false },
                    { label: 'Type', field: 'emp.name',sortable:false },
                    { label: 'Status', field: 'emp.name',sortable:false },
                    { label: 'Action', field: 'emp.name',sortable:false },

                ],
                rows: window.rows,
                page: 1,
                per_page: 10,
                filter: '',
            }
        },
        mounted() {
            this.getAllEmployees();

        },
        methods: {
            filtter()
            {
                var value = this.filter.toLowerCase();
                $("#attendanceTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            },
            getAllEmployees() {
                let self = this;
                axios.get('get_all_employees').then(function (response) {
                    self.all_employees = response.data;

                }).catch(function (error) {

                });
            },
            onChangeStatus(user_id, status) {
                console.log(user_id + " " + status);
                axios.post('change_status', {user_id, status}).then(function (response) {

                }).catch(function (error) {

                });
            },
            editEmployee(user_id) {
                this.$router.push({'name': 'admin-edit-user', params: {id: user_id}});
            },
            viewProfile(id)
            {
               this.$router.push({'name':'profile',params:{'id':id}});
            },
            deleteEmployee(id)
            {
                let self=this;
                axios.post('delete_employee', {id}).then(function (response) {
                self.getAllEmployees();
                }).catch(function (error) {
                    Vue.$toast.error("User Deleted!");
                });
            }


        }
    }
</script>
