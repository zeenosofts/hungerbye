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
                <div class="col-md-4" v-if="card != 'none'" v-for="c in card">
                    <div class="box box-success box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{c.card_name}}</h3>

                            <div class="box-tools pull-right" v-if="$parent.role !== 'manager'">
                                <button type="button" @click="deleteCard(c.id)" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            Your card is saved you can delete by clicking x button.
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        <div class="row">

            <div class="col-lg-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fab fa-cc-visa"></i>

                        <h3 class="box-title">Add Card</h3>

                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <label>Card Name / Description</label>
                        <input type="text" name="card_name" class="form-control" v-model="card_name"/>
                        <span class="help-block" v-show="error.card_name">Card name can't be empty!</span>
                        <div>
                            <card class='stripe-card'
                                  :class='{ complete }'
                                  :stripe="stripe"
                                  @change='complete = $event.complete'
                            />
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                        <button type="button" @click="pay" :disabled='!complete' class="btn btn-primary pay-with-stripe btn-flat pull-right"><i class="fa fa-plus"></i> Save</button>
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
        props:['stripe'],
        data()
        {
            return{
                error: {'card_name':false},
                bank:{'stripe_key':'','stripe_secret':'','stripe_destination' : ''},
                complete: false,
                stripeOptions: {
                    // see https://stripe.com/docs/stripe.js#element-options for details
                },
                source:[],
                card:[],
                card_name:'',
                number: false,
                expiry: false,
                cvc: false,
                stripe:'',
            }
        },
        components: { Card},
        mounted() {
            let self=this;
            self.getCardDetails();
            self.getAPiKey();
        },
        methods: {
            pay () {
                let self=this;
                if(self.card_name.trim() == null){
                    self.error.card_name = true;
                }else {
                    createToken().then(function (response) {
                        axios.post('save_bank', {source: response.token,card_name:self.card_name}).then(function (response) {
                            if (response.data == "success") {
                                self.getCardDetails();
                                Vue.$toast.success("Your Bank Saved Successfully");
                            } else {
                                Vue.$toast.error("Unknown Error Occurred");
                            }

                        }).catch(function (error) {
                            Vue.$toast.error("Unknown Error Occurred");
                        });
                    });
                }

            },
            getCardDetails(){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('getCardDetails',{key:"nun"})).then(function (response) {
                    self.card=response.data;
                });
            },
            deleteCard(id){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('deleteCard',{id:id})).then(function (response) {
                    if(response.data == "deleted") {
                        self.getCardDetails();
                        Vue.$toast.warning("Card Deleted Successfully!");
                    }else{
                        Vue.$toast.error("Unknown Error Occurred!");
                    }
                });
            },
            getAPiKey(){
                let self=this;
                //self.stripe="pk_test_CuJotvmeGq97J1dFuxfLz9Ik00x8r6riIb";
                Promise.resolve(main.sendPOSTRequest('getPublicKey',{key:"nun"})).then(function (response) {
                    self.stripe=response.data;
                });
            },

        },
    }
</script>
