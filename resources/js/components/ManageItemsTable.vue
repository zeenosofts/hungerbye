<template>
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Items > Manage Items</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Items</a></li>
                <li class="active">Manage Items</li>
            </ol>
        </section>
        <section class="content" >


            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="fa fa-th-list"></i>

                            <h3 class="box-title">My Requests</h3>

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
                                            <router-link to="/partner/post-item" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Make new request</router-link>
                                        </div>
                                    </div>
                                </div>
                                <datatable id="attendanceTable" :columns="columns" :data="items" class="table-bordered">

                                    <template slot-scope="{ row }">
                                        <tr>
                                            <td>{{row.partner_name}}</td>
                                            <td>{{row.customer_id}}</td>
                                            <td>{{row.item_name}}</td>
                                            <td>{{row.item_description}}</td>
                                            <td>${{row.item_price}}</td>
                                            <td>${{row.sum_donated_amount}}</td>
                                            <td>${{row.item_price - row.sum_donated_amount}}</td>
                                            <td><button v-if="row.status == 1" class="btn btn-danger btn-sm btn-flat"  @click="deleteItem(row.did)" ><i class="fa fa-trash"></i></button><button @click="donate(row.did)" class="btn btn-flat btn-sm btn-success" v-if="row.status == '0'">Completed/View</button><button v-if="row.status > 0" class="btn btn-flat btn-primary btn-sm" @click="donate(row.did)">Donate</button></td>
                                            <td><label  :class="row.status == 1 ? 'label bg-yellow' : row.status == 2 ? 'label bg-blue' : row.status == 0 ? 'label bg-green' : 'label bg-yellow' ">{{row.status == 1 ? "Pending" : row.status == 2 ? 'Ongoing' : row.status == 0 ? 'Completed' : 'Pending' }}</label></td>
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
    import main from "./script/main";
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
                    { label: 'Action', field: 'emp.name',sortable:false },
                    { label: 'Status', field: 'emp.name',sortable:false },
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
                axios.get('get_items_for_this_donor').then(function (response) {
                    self.items = response.data;

                }).catch(function (error) {

                });
            },
            deleteItem(id){
                let self=this;
                Vue.dialog
                    .confirm('Please confirm to continue')
                    .then(function(dialog) {
                        Promise.resolve(main.sendPOSTRequest('deleteRequest',{id:id})).then(function (response) {
                            if(response.data == "success"){
                                Vue.$toast.success("Request deleted successfully");
                            }else if(response.data == "error") {
                                Vue.$toast.error("Unknown error occurred");
                            }else if(response.data == "notdeleted") {
                                Vue.$toast.warning("This item cannot be deleted as it is part of a request");
                            }
                            self.getItemsForDonor();
                        });
                    })
                    .catch(function() {
                        console.log('Clicked on cancel');
                    });

            },
            donate(did){
                this.$router.push({'name':'donate',params:{'id':did}});
            }


        }
    }
</script>
