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
                <div class="col-md-4" style="margin-bottom: 5px" v-if="card != 'none'" v-for="c in card">
                    <div class="card">
                        <button type="button"  @click="deleteCard(c.id)"  class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        <div class="bank-name ng-binding" title="visa" >{{c.brand}}</div>
                        <div class="chip">
                            <div class="side left"></div>
                            <div class="side right"></div>
                            <div class="vertical top"></div>
                            <div class="vertical bottom"></div>
                        </div>
                        <div class="data">
                            <div class="pan ng-binding" title="**** **** **** 1381">**** **** **** {{c.last4}}</div>
                            <div class="first-digits ng-binding"></div>
                            <div class="exp-date-wrapper">
                                <div class="left-label">EXPIRES END</div>
                                <div class="exp-date">
                                    <div class="upper-labels">MONTH/YEAR</div>
                                    <div class="date ng-binding" title="January/2020">{{c.month}}/{{c.year}}</div>
                                </div>
                            </div>
                            <div class="name-on-card ng-binding" title="John Doe"></div>
                        </div>
                    </div>
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
                            <br>
                            <div ref="card"></div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix no-border">
                            <button type="button" @click="pay"  class="btn btn-primary pay-with-stripe btn-flat pull-right"><i class="fa fa-plus"></i> Save</button>
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
    let style = {
        base: {
            border: '1px solid #D8D8D8',
            borderRadius: '4px',
            color: "#000",
        },

        invalid: {
            // All of the error styles go inside of here.
        }

    };
    export default {
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
                stripes:"",
            }
        },
        components: { Card},
        mounted() {
            let self=this;
            self.getCardDetails();
            card = elements.create('card',style);

            card.mount(self.$refs.card);
        },
        beforeDestroy() {
            let self=this;
            card.destroy(self.$refs.card);
        },
        methods: {
            pay () {
                let self=this;
                if(self.card_name.trim() == null){
                    self.error.card_name = true;
                }else {
                    stripe.createToken(card).then(function (response) {
                        if (response.error) {
                            self.hasCardErrors = true;
                            self.$forceUpdate(); // Forcing the DOM to update so the Stripe Element can update.
                            return;
                        }else {
                            axios.post('save_bank', {
                                source: response.token,
                                card_name: self.card_name
                            }).then(function (response) {
                                if (response.data == "success") {
                                    //self.getCardDetails();
                                    //self.$router.go();
                                    Vue.$toast.success("Your Bank Saved Successfully");
                                } else {
                                    Vue.$toast.error("Unknown Error Occurred");
                                }

                            }).catch(function (error) {
                                Vue.$toast.error("Unknown Error Occurred");
                            });
                        }
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
                Vue.dialog
                    .confirm('Please confirm to continue')
                    .then(function(dialog) {
                        Promise.resolve(main.sendPOSTRequest('deleteCard',{id:id})).then(function (response) {
                            if(response.data == "deleted") {
                                self.getCardDetails();
                                dialog.close();
                                self.$router.go();
                                Vue.$toast.warning("Card Deleted Successfully!");
                            }else{
                                Vue.$toast.error("Unknown Error Occurred!");
                            }
                        });
                    })
                    .catch(function() {
                        console.log('Clicked on cancel');
                    });

            },

        },
    }
</script>
