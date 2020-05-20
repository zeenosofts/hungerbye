<template >
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Items > Edit Item</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Item</a></li>
                <li class="active">Edit Item</li>
            </ol>
        </section>
        <section class="content" >
        <div class="row">
            <div class="col-lg-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-suitcase"></i>

                        <h3 class="box-title">Edit Item</h3>

                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label"> Item Name</label>
                            <input type="text" class="form-control" v-model="item.item_name" placeholder="Item Name">
                            <span class="help-block" v-show="error.item_name">Item Name can't be empty!</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> Item Description</label>
                            <input type="text" class="form-control" v-model="item.item_description" placeholder="Item Description">
                            <span class="help-block" v-show="error.item_description">Item Description can't be empty!</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> Item Price</label>
                            <input type="number" class="form-control" v-model="item.item_price"  placeholder="Item Price">
                            <span class="help-block" v-show="error.item_price">Item Price can't be empty!</span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                        <button type="button" class="btn btn-primary btn-flat pull-right" @click="updateItem()"><i class="fa fa-plus"></i> Update Item</button>
                        <button type="button" class="btn btn-primary btn-flat pull-right" @click="back()">Back</button>
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
                error: {'item_name':false,'item_description':false,
                    'item_price':false,'customer_name':false},
                item:{},
            }
        },
        mounted() {
            let self=this;
            self.getItemForthis();
        },
        methods: {
            getItemForthis(){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('getItemForthis',{id:self.$route.params.id})).then(function (response) {
                    self.item=response.data
                });
            },
            updateItem(){
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
                    Promise.resolve(main.sendPOSTRequest('updateItem',self.item)).then(function (response) {
                        if(response.data == "duplicate"){
                            Vue.$toast.warning("Item with this name already present");
                        }else if(response.data == "success"){
                            Vue.$toast.success("Item updated successfully");
                        }else if(response.data == "error"){
                            Vue.$toast.error("Unknown Error Occurred");
                        }
                    });
                }
            },
            back(){
                this.$router.go(-1);
            }
        }
    }
</script>
