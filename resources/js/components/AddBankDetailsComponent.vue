<template >
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Bank Details > Create Bank Details</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-bank"></i> Bank Details</a></li>
                <li class="active">Add Bank</li>
            </ol>
        </section>
        <section class="content" >
        <div class="row">
            <div class="col-lg-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-bank"></i>

                        <h3 class="box-title">Bank Details</h3>

                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label"> STRIPE KEY</label>
                            <input type="text" class="form-control" v-model="bank.stripe_key" placeholder="Stripe Key">
                            <span class="help-block" v-show="error.stripe_key">Stripe key can't be empty!</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> STRIPE SECRET</label>
                            <input type="text" class="form-control" v-model="bank.stripe_secret" placeholder="Stripe Secret Key">
                            <span class="help-block" v-show="error.stripe_secret">Stripe secret can't be empty!</span>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                        <button type="button" class="btn btn-primary btn-flat pull-right" @click="saveUser()"><i class="fa fa-plus"></i> Add Details</button>
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
    import main from "./script/main";
    import { Card, createToken } from 'vue-stripe-elements-plus';
    export default {
        data()
        {
            return{
                error: {'stripe_key':false,'stripe_secret':false,'stripe_destination':false},
                bank:{'stripe_key':'','stripe_secret':'','stripe_destination' : ''},
                complete: false,
                stripeOptions: {
                    // see https://stripe.com/docs/stripe.js#element-options for details
                },
                source:[],
                card:{}
            }
        },
        components: { Card },
        mounted() {
            let self=this;
            self.getRoles();
        },
        methods: {
            getRoles(){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('getAdminBankDetails',{key:"nun"})).then(function (response) {
                    self.bank=response.data;
                });
            },
            saveUser(){
                let self=this;
                if(self.bank.stripe_key.trim().length != 0)
                {
                    self.error.stripe_key=false
                }
                if(self.bank.stripe_secret.trim().length != 0)
                {
                    self.error.stripe_secret=false;
                }
                ////Checking Condition Making True Error
                if(self.bank.stripe_key.trim().length == 0)
                {
                    self.error.stripe_key=true;
                }
                else if(self.bank.stripe_secret.trim().length == 0)
                {
                    self.error.stripe_secret=true;
                }
                else{
                    Promise.resolve(main.sendPOSTRequest('save_bank_details',self.bank)).then(function (response) {
                        if(response.data == "warning"){
                            Vue.$toast.warning("Unauthorized Access");
                        }else if(response.data == "success"){
                            Vue.$toast.success("Details saved successfully");
                        }else if(response.data == "error"){
                            Vue.$toast.error("Unknown Error Occurred");
                        }
                    });
                }
            }
        }
    }
</script>
