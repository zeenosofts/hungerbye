<template >
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Items > Post Item</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-check-square-o"></i> Item</a></li>
                <li class="active">Post Item</li>
            </ol>
        </section>
        <section class="content" >
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-check-square-o"></i>

                        <h3 class="box-title">Choose Item to Post</h3>

                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label"> Choose item</label>
                            <multiselect v-model="item.item_name" :options="items"  placeholder="Select" label="item_name" track-by="id" ><span slot="noResult">No item found.Please add an item first by going to <router-link to="/partners/add-items">Manage Products</router-link>.</span></multiselect>
                            <span class="help-block" v-show="error.item_name">Item Name can't be empty!</span>
                        </div>
                        <!--<div class="form-group">-->
                            <!--<label class="control-label"> Item Description</label>-->
                            <!--<input type="text" class="form-control" v-model="item.item_description" placeholder="Item Description">-->
                            <!--<span class="help-block" v-show="error.item_description">Item Description can't be empty!</span>-->
                        <!--</div>-->
                        <!--<div class="form-group">-->
                            <!--<label class="control-label"> Item Price</label>-->
                            <!--<input type="text" class="form-control" v-model="item.item_price"  placeholder="Item Price" >-->
                            <!--<span class="help-block" v-show="error.item_price">Item Price can't be empty!</span>-->
                        <!--</div>-->
                        <div class="form-group">
                            <label class="control-label">Enter Customer Name</label>
                            <input type="text" class="form-control" v-model="item.customer_name"  placeholder="Customer ">
                            <span class="help-block" v-show="error.customer_name">Customer Name can't be empty!</span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div v-if="(this.$parent.role =='partner' || this.$parent.role =='manager' || this.$parent.role =='staff') && stripe == 'success'" class="box-footer clearfix no-border">
                    <button  type="button" class="btn btn-primary btn-flat pull-right" @click="saveItem()"><i class="fa fa-plus"></i> Post Request</button>
                    </div>
                    <div v-if="(this.$parent.role =='partner' || this.$parent.role =='manager' || this.$parent.role =='staff') && stripe == 'error'" class="box-footer clearfix no-border">
                        <button  type="button" class="btn btn-danger btn-flat pull-right" ><i class="fa fa-plus"></i> Please connect with stripe first!</button>
                    </div>
                        <!--<div v-if="this.$parent.role =='superadmin' || this.$parent.role =='editor'" class="box-footer clearfix no-border">-->
                        <!--<button v-if="this.$parent.logined_user.admin_SECRET_KEY != null" type="button" class="btn btn-primary btn-flat pull-right" @click="saveItem()"><i class="fa fa-plus"></i> Post Item</button>-->
                        <!--<button v-if="this.$parent.logined_user.admin_SECRET_KEY == null" type="button" class="btn btn-warning btn-flat pull-right">Please add stripe</button>-->
                    <!--</div>-->
                    <!--<div v-if="this.$parent.role =='partner'" class="box-footer clearfix no-border">-->
                        <!--<button v-if="this.$parent.logined_user.admin_SECRET_KEY != null" type="button" class="btn btn-primary btn-flat pull-right" @click="saveItem()"><i class="fa fa-plus"></i> Post Item</button>-->
                        <!--<button v-if="this.$parent.logined_user.admin_SECRET_KEY == null" type="button" class="btn btn-warning btn-flat pull-right">Please add stripe</button>-->
                    <!--</div>-->
                    <!--<div v-if="this.$parent.role =='partner'" class="box-footer clearfix no-border">-->
                        <!--<button v-if="this.$parent.logined_user.user_SECRET_KEY != null" type="button" class="btn btn-primary btn-flat pull-right" @click="saveItem()"><i class="fa fa-plus"></i> Post Item</button>-->
                        <!--<button v-if="this.$parent.logined_user.user_SECRET_KEY == null" type="button" class="btn btn-warning btn-flat pull-right">Please add stripe</button>-->
                    <!--</div>-->
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
                error: {'item_name':false,'item_description':false,
                    'item_price':false,'customer_name':false},
                item:{'item_name':0,'item_description':'',
                    'item_price':'','customer_name':'','item_id':''},
                items:[],
                stripe:'',
            }
        },
        mounted() {
            let self=this;
            self.getAllItems();
            self.getCard();
        },
        methods: {
            getAllItems(){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('getAllItems',{id:"nun"})).then(function (response) {
                    self.items=response.data
                });
            },
            saveItem(){
                let self=this;
                if(self.item.item_name.length != 0)
                {
                    self.error.item_name=false
                }
//                if(self.item.item_description.trim().length != 0)
//                {
//                    self.error.item_description=false;
//                }
//                if(self.item.item_price.trim().length != 0)
//                {
//                    self.error.item_price=false;
//                }
                if(self.item.customer_name.trim().length != 0)
                {
                    self.error.customer_name=false;
                }
                ////Checking Condition Making True Error
                if(self.item.item_name.length == 0)
                {
                    self.error.item_name=true;
                }
//                else if(self.item.item_description.trim().length == 0)
//                {
//                    self.error.item_description=true;
//                }
//                else if(self.item.item_price.trim().length == 0)
//                {
//                    self.error.item_price=true;
//                }
                else{
                    self.item.item_id=self.item.item_name['id'];
                    self.item.item_description=self.item.item_name['item_description'];
                    self.item.item_price=self.item.item_name['item_price'];
                    Promise.resolve(main.sendPOSTRequest('postArequest',self.item)).then(function (response) {
                        if(response.data == "duplicate"){
                            //Vue.$toast.warning("User with this email already present");
                        }else if(response.data == "success"){
                            Vue.$toast.success("Request posted successfully");
                            self.$router.push({'name':'opened-requests'});
                        }else if(response.data == "error"){
                            Vue.$toast.error("Unknown Error Occurred");
                        }
                    });
                }
            },
            getCard(){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('getUserStripe',{key:"nun"})).then(function (response) {
                    self.stripe=response.data;
                });
            },
        }
    }
</script>
