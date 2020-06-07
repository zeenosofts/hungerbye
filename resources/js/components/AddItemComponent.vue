<template >
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Items > Add Item</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Item</a></li>
                <li class="active">Add Item</li>
            </ol>
        </section>
        <section class="content" >
        <div class="row">
            <div class="col-lg-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fas fa-shopping-cart"></i>

                        <h3 class="box-title">Add Item</h3>

                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label"> Item Name</label>
                            <input type="text" class="form-control" v-model="item.item_name" placeholder="e.g Apple Pie">
                            <span class="help-block" v-show="error.item_name">Item Name can't be empty!</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> Item Description</label>
                            <input type="text" class="form-control" v-model="item.item_description" placeholder="e.g Classic Homemade Pie">
                            <span class="help-block" v-show="error.item_description">Item Description can't be empty!</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> Item Price</label>
                            <input type="number" class="form-control" v-model="item.item_price"  placeholder="$0.00">
                            <span class="help-block" v-show="error.item_price">Item Price can't be empty!</span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                        <button type="button" class="btn btn-primary btn-flat pull-right" @click="saveItem()"><i class="fa fa-plus"></i> Add Item</button>
                    </div>
                    <div  v-if="this.$parent.overlay" class="overlay">
                        <i class="fa fa-refresh fa-spin color-green"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="fa fa-cart-arrow-down"></i>

                            <h3 class="box-title">Manage Items</h3>

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
                                            <td>{{row.item_name}}</td>
                                            <td>{{row.item_description}}</td>
                                            <td>{{row.item_price}}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm btn-flat" @click="editItem(row.id)"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger btn-sm btn-flat" @click="deleteItem(row.id)"><i class="fa fa-trash"></i></button>
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
        </section>
    </div>


</template>

<script>
    import main from "./script/main"
    export default {
        data()
        {
            return{
                error: {'item_name':false,'item_description':false,
                    'item_price':false,'customer_name':false},
                item:{'item_name':'','item_description':'',
                    'item_price':''},
                items:[],
                columns: [
                    // { label: 'ID',  filterable: true, field: 'emp.name' },
                    { label: 'Item Name', field: 'emp.today_date',sortable:false },
                    { label: 'Item Description', field: 'emp.today_date',sortable:false },
                    { label: 'Item Price', field: 'emp.today_date',sortable:false },
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
            self.getAllItems();
        },
        methods: {
            getAllItems(){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('getAllItems',{id:"nun"})).then(function (response) {
                    self.items=response.data
                });
            },
            editItem(id){
                this.$router.push({'name':'edit-items',params:{'id':id}});
            },
            deleteItem(id){
                let self=this;
                Vue.dialog
                    .confirm('Please confirm to continue')
                    .then(function(dialog) {
                        Promise.resolve(main.sendPOSTRequest('deleteItem',{id:id})).then(function (response) {
                            if(response.data == "success"){
                                Vue.$toast.success("Item deleted successfully");
                            }else if(response.data == "error") {
                                Vue.$toast.error("Unknown error occurred");
                            }
                            self.getAllItems();
                        });
                    })
                    .catch(function() {
                        console.log('Clicked on cancel');
                    });
            },
            saveItem(){
                let self=this;
                if(self.item.item_name.length != 0)
                {
                    self.error.item_name=false
                }
                if(self.item.item_description.trim().length != 0)
                {
                    self.error.item_description=false;
                }
                if(self.item.item_price.trim().length != 0)
                {
                    self.error.item_price=false;
                }
                ////Checking Condition Making True Error
                if(self.item.item_name.length == 0)
                {
                    self.error.item_name=true;
                }
                else if(self.item.item_description.trim().length == 0)
                {
                    self.error.item_description=true;
                }
                else if(self.item.item_price.trim().length == 0)
                {
                    self.error.item_price=true;
                }
                else{
                    Promise.resolve(main.sendPOSTRequest('saveItem',self.item)).then(function (response) {
                        if(response.data == "duplicate"){
                            Vue.$toast.warning("Item with this name already present");
                        }else if(response.data == "success"){
                            self.getAllItems();
                            self.item_name='';
                            self.item_description='';
                            self.item_price='';
                            Vue.$toast.success("Item saved successfully");
                        }else if(response.data == "error"){
                            Vue.$toast.error("Unknown Error Occurred");
                        }
                    });
                }
            }
        }
    }
</script>
