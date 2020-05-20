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

                                <label><b>{{employee_data.business_name}}</b></label><br>
                                    <label><i class="fa fa-map-marker"></i> {{employee_data.business_address}}</label><br>
                                <label><i class="fa fa-phone"></i> {{employee_data.number}}</label><br>
                                <label><i class="fa fa-user-plus"></i> Request Served: {{employee_data.served}}</label><br>

                            </div>
                            <div class="col-lg-12">
                                <h2>#HungerBye Appreciation Offers</h2>
                                <p>Check out what our partners have to offer this week to our donors to show appreciation for all of their contribution. Enjoy exclusive deals and discounts for being a HungerBye user</p>

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
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-cart-arrow-down"></i>

                        <h3 class="box-title">Offers by this Partner</h3>

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
                            <datatable id="attendanceTable" :columns="columns" :data="roles" class="table-bordered">

                                <template slot-scope="{ row }">
                                    <tr>
                                        <td>{{row.item_name}}</td>
                                        <td>{{row.item_description}}</td>
                                        <td>${{row.item_price}}</td>
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
    import main from "./script/main"
    export default {
        data()
        {
            return{
                error: {'first_name':false,'last_name':false,
                    'email':false,'password':false,'role':false},
                employee_data:{},
                roles:[],
                columns: [
                    // { label: 'ID',  filterable: true, field: 'emp.name' },
                    { label: 'Offer Name', field: 'emp.today_date',sortable:false },
                    { label: 'Offer Description', field: 'emp.today_date',sortable:false },
                    { label: 'Offer Price', field: 'emp.today_date',sortable:false },

                ],
                rows: window.rows,
                page: 1,
                per_page: 10,
                filter: '',
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
                Promise.resolve(main.sendPOSTRequest('getAllOffersForThisID',{id:self.$route.params.id})).then(function (response) {
                    self.roles=response.data;
                });
            },
            back(){
              this.$router.go(-1);
            },
            getUsersWithThisID(){
                let self=this;

                    Promise.resolve(main.sendPOSTRequest('getPartnerWithServedRequests',{id:self.$route.params.id})).then(function (response) {
                        self.employee_data=response.data;
                    });
                },

        }
    }
</script>
