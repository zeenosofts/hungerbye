<template>
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Admin > Partners</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-male"></i> Admin</a></li>
                <li class="active"> Partners</li>
            </ol>
        </section>
        <section class="content" >


            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="fa fa-male"></i>

                            <h3 class="box-title">All Partners</h3>

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
                                            <td><router-link :to="'/partner-profile/'+row.user_id" :key="row.user_id">{{row.business_name}}</router-link></td>
                                            <td>{{row.business_address}}</td>
                                            <td>{{row.served}}</td>
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
    import main from './script/main'
    export default {
        data() {
            return {
                all_employees: [],
                columns: [
                    // { label: 'ID',  filterable: true, field: 'emp.name' },
                    { label: 'Partner Name', field: 'emp.today_date',sortable:false },
                    { label: 'Address', field: 'emp.name',sortable:false },
                    { label: 'Request Served', field: 'emp.name',sortable:false },

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
                Promise.resolve(main.sendPOSTRequest('getAllPartnersWithRequest',{'key' :"nun"})).then(function (response) {
                    self.all_employees = response.data;
                });
            },
            openPartnerView(user_id) {
                this.$router.push({'name': 'partner-profile', params: {id: user_id}});
            },


        }
    }
</script>
