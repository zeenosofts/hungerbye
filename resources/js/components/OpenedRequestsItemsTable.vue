<template>
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Donation > Open Requests</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-reorder"></i> Donation</a></li>
                <li class="active">Open Requests</li>
            </ol>
        </section>
        <section class="content" >


            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="fa fa-reorder"></i>

                            <h3 class="box-title">Open Requests</h3>

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
                                <datatable id="attendanceTable" :columns="columns" :data="items" class="table-bordered">

                                    <template slot-scope="{ row }">
                                        <tr>
                                            <td>{{row.partner_name}}</td>
                                            <td>{{row.customer_id}}<br><span class="label label-success" v-if="row.already_donated == 'yes'">Contributed</span></td>
                                            <td>{{row.item_name}}</td>
                                            <td>{{row.item_description}}</td>
                                            <td>${{parseFloat(row.item_price).toFixed(2)}}</td>
                                            <td>${{parseFloat(row.sum_donated_amount).toFixed(2)}}</td>
                                            <td>${{parseFloat(row.item_price - row.sum_donated_amount).toFixed(2)}}</td>
                                            <td><label :class="row.status == 1 ? 'label bg-yellow' : row.status == 2 ? 'label bg-blue' : row.status == 0 ? 'label bg-green' : 'label bg-yellow' ">{{row.status == 1 ? "Pending" : row.status == 2 ? 'Ongoing' : row.status == 0 ? 'Completed' : 'Pending' }}</label></td>
                                            <td><button @click="donate(row.did)" class="btn btn-flat btn-sm btn-success" v-if="row.status == '0'">Completed/View</button><button v-if="row.status > 0" class="btn btn-flat btn-primary btn-sm" @click="donate(row.did)">Donate</button></td>
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
                items: [],
                columns: [
                    // { label: 'ID',  filterable: true, field: 'emp.name' },
                    { label: 'Partner name', field: 'emp.today_date',sortable:false },
                    { label: 'Customer name', field: 'emp.today_date',sortable:false },
                    { label: 'Item', field: 'emp.name',sortable:false },
                    { label: 'Description', field: 'emp.name',sortable:false },
                    { label: 'Goal', field: 'emp.name',sortable:false },
                    { label: 'Received', field: 'emp.name',sortable:false },
                    { label: 'Remaining', field: 'emp.name',sortable:false },
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
            this.getItemsForDonor();

        },
        methods: {
            filtter()
            {
                var value = this.filter.toLowerCase();
                $("#attendanceTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            },
            getItemsForDonor() {
                let self = this;
                axios.get('get_requests_to_show_opened').then(function (response) {
                    self.items = response.data;

                }).catch(function (error) {

                });
            },
            donate(did){
                this.$router.push({'name':'donate',params:{'id':did}});
            }


        }
    }
</script>
