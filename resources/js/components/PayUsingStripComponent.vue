<template>
    <div>
        <section class="content-header">
            <h1>
                Requests
                <small>Donation > Donate</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-heart"></i> Donation</a></li>
                <li class="active">Donate</li>
            </ol>
        </section>
        <section class="content" >
            <div class="row">
                <div class="col-lg-4">
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="fa fa-heart-o"></i>

                            <h3 class="box-title">Donate</h3>

                            <div class="box-tools pull-right">

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4>Partner Details</h4>
                                    <label><b>Business Name:</b> {{item_details.business_name}}</label><br>
                                    <label><b>Business Address:</b> {{item_details.business_address}}</label><br>
                                    <h4>Order Details</h4>
                                    <label><b>Contact Number:</b> {{item_details.number}}</label><br>
                                    <label><b>Customer Name:</b> {{item_details.customer_id}}</label><br>
                                    <label><b>Item:</b> {{item_details.item_name}}</label><br>
                                    <label><b>Description:</b> {{item_details.item_description}}</label><br>
                                </div>
                                <br>
                                <div class="col-lg-12" align="center"  v-if="parseFloat(item_details.item_price).toFixed(2) == parseFloat(item_details.sum_donated_amount).toFixed(2)">
                                    <hr>
                                    <label class="btn btn-flat btn-primary">Goal: ${{item_details.item_price}}</label>
                                    <label class="btn btn-flat btn-warning">Remaining: ${{item_details.item_price - item_details.sum_donated_amount}}</label>
                                    <label class="btn btn-flat btn-success">Received: ${{item_details.sum_donated_amount}}</label>
                                    <br>
                                    <br>
                                    <div align="center">
                                        <span class="fa fa-check-circle text-green" style="font-size: 45px"></span>
                                        <h4>Thank You, Request is Completed!</h4>
                                    </div>

                                </div>
                                <div class="col-lg-12" v-if="parseFloat(item_details.item_price).toFixed(2) != parseFloat(item_details.sum_donated_amount).toFixed(2)">
                                    <label class="btn btn-flat btn-primary">Goal: ${{item_details.item_price}}</label>
                                    <label class="btn btn-flat btn-warning">Remaining: ${{item_details.item_price - item_details.sum_donated_amount}}</label>
                                    <label class="btn btn-flat btn-success">Received: ${{item_details.sum_donated_amount}}</label>
                                        <br>
                                    <label><input type="radio" @change="changePaymentAmount" v-tooltip="toltip1"  :disabled='item_details.sum_donated_amount == "0" ? false :true' value="1" v-model="donation"/><b v-tooltip="item_details.sum_donated_amount == '0' ? false :toltip1x">Donate full amount</b> </label><br>
                                    <label><input type="radio" @change="changePaymentAmount" v-tooltip="toltip2" :disabled='item_details.sum_donated_amount == "0" ? true :false'  value="2" v-model="donation"/><b v-tooltip="item_details.sum_donated_amount == '0' ? toltip2x :false">Donate Remaining Amount</b> </label><br>
                                    <label><input type="radio" @change="changePaymentAmount" v-tooltip="toltip2"  value="3" v-model="donation"/><b>Donate $1</b></label><br>
                                <span class="help-block" v-show="error.donation">Please check one option from these or type amount</span>
                                    <span class="help-block" v-show="error.low">Please type amount greater than $0.5</span>
                                    <span class="help-block" v-show="error.high">Please type amount less than ${{item_details.item_price - item_details.sum_donated_amount}}</span>
                                </div>
                            </div>
                            <div v-if=" parseFloat(item_details.item_price).toFixed(2) != parseFloat(item_details.sum_donated_amount).toFixed(2)">
                                <div v-show="description_show" class="box bg-default">
                                    <div class="box-body">
                                        <h4>Summary</h4>
                                        <div align="center">
                                        <p>Amount: ${{parseFloat(description.amount).toFixed(2)}}</p>
                                        <p>Fee: ${{parseFloat(description.new_amount - description.amount).toFixed(2)}} <i class="fa fa-info-circle text-primary" style="margin-left: 5px !important;" v-tooltip="description.type == 'full' ? toltip1 : toltip2"></i></p>
                                        <p>Total: ${{parseFloat(description.new_amount).toFixed(2)}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div >
                                <div v-show="card === 'none' && parseFloat(item_details.item_price).toFixed(2) != parseFloat(item_details.sum_donated_amount).toFixed(2)">
                                    <div ref="card"></div>
                                    <label><input type="checkbox" name="save_Card" v-model="save_card"/>Save your card for future donations?</label>
                                </div>
                            </div>
                            <div v-if="card != 'none' &&  parseFloat(item_details.item_price).toFixed(2) != parseFloat(item_details.sum_donated_amount).toFixed(2)">
                                <select class="form-control" v-model="selectedCard">
                                    <option value="0">--Select Card--</option>
                                    <option v-for="c in card" :value="c.id">{{c.card_name == '' ? 'Card' : c.card_name}}</option>
                                </select>
                                <span class="help-block" v-show="error.selectedCard">Please select a card</span>
                            </div>

                        </div>
                        <!-- /.box-body -->
                        <div v-if=" parseFloat(item_details.item_price).toFixed(2) != parseFloat(item_details.sum_donated_amount).toFixed(2)">
                            <div v-if="card == 'none'" class="box-footer clearfix no-border">
                                <button type="button" @click="pay"  class="btn btn-success pay-with-stripe btn-flat pull-right"><i class="fa fa-money"></i> Donate</button>
                                <button type="button" class="btn btn-primary btn-flat pull-right" @click="back()">Back</button>
                            </div>
                            <div v-if="card != 'none'" class="box-footer clearfix no-border">
                                <button type="button" @click="donate"   class="btn btn-success pay-with-stripe btn-flat pull-right"><i class="fa fa-money"></i> Donate</button>
                                <button type="button" class="btn btn-primary btn-flat pull-right" @click="back()">Back</button>
                            </div>

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
<style>
    .tooltip {
        display: block !important;
        z-index: 10000;
    }

    .tooltip .tooltip-inner {
        background: black;
        color: white;
        border-radius: 16px;
        padding: 5px 10px 4px;
    }

    .tooltip .tooltip-arrow {
        width: 0;
        height: 0;
        border-style: solid;
        position: absolute;
        margin: 5px;
        border-color: black;
        z-index: 1;
    }

    .tooltip[x-placement^="top"] {
        margin-bottom: 5px;
    }

    .tooltip[x-placement^="top"] .tooltip-arrow {
        border-width: 5px 5px 0 5px;
        border-left-color: transparent !important;
        border-right-color: transparent !important;
        border-bottom-color: transparent !important;
        bottom: -5px;
        left: calc(50% - 5px);
        margin-top: 0;
        margin-bottom: 0;
    }

    .tooltip[x-placement^="bottom"] {
        margin-top: 5px;
    }

    .tooltip[x-placement^="bottom"] .tooltip-arrow {
        border-width: 0 5px 5px 5px;
        border-left-color: transparent !important;
        border-right-color: transparent !important;
        border-top-color: transparent !important;
        top: -5px;
        left: calc(50% - 5px);
        margin-top: 0;
        margin-bottom: 0;
    }

    .tooltip[x-placement^="right"] {
        margin-left: 5px;
    }

    .tooltip[x-placement^="right"] .tooltip-arrow {
        border-width: 5px 5px 5px 0;
        border-left-color: transparent !important;
        border-top-color: transparent !important;
        border-bottom-color: transparent !important;
        left: -5px;
        top: calc(50% - 5px);
        margin-left: 0;
        margin-right: 0;
    }

    .tooltip[x-placement^="left"] {
        margin-right: 5px;
    }

    .tooltip[x-placement^="left"] .tooltip-arrow {
        border-width: 5px 0 5px 5px;
        border-top-color: transparent !important;
        border-right-color: transparent !important;
        border-bottom-color: transparent !important;
        right: -5px;
        top: calc(50% - 5px);
        margin-left: 0;
        margin-right: 0;
    }

    .tooltip.popover .popover-inner {
        background: #f9f9f9;
        color: black;
        padding: 24px;
        border-radius: 5px;
        box-shadow: 0 5px 30px rgba(black, .1);
    }

    .tooltip.popover .popover-arrow {
        border-color: #f9f9f9;
    }

    .tooltip[aria-hidden='true'] {
        visibility: hidden;
        opacity: 0;
        transition: opacity .15s, visibility .15s;
    }

    .tooltip[aria-hidden='false'] {
        visibility: visible;
        opacity: 1;
        transition: opacity .15s;
    }
</style>
<script>
    import main from './script/main'
    export default {
        data () {
            return {
                error:{"donation" : false,'low':false,'high':false,'selectedCard':false},
                complete: false,
                stripeOptions: {
                    // see https://stripe.com/docs/stripe.js#element-options for details
                },
                source:[],
                donation:'',
                otherAmount:'',
                item_details:{},
                card:{},
                stripe:'',
                selectedCard:'0',
                save_card:false,
                disable:false,
                toltip1:'No processing fee collected',
                toltip2:'A processing fee of 2.9% + 0.30¢ will be collected',
                toltip1x:'Since this request has received partial donation this option is disabled. Please choose Donate Remaining or Donate $1 to contribute to this request.',
                toltip2x:'Since this request is still in pending the remaining option is disabled. Please choose Donate Full Amount or Donate $1 to contribute to this request',
                description_show:false,
                description:{}
            }
        },
        mounted(){

            let self=this;
            card.destroy(self.$refs.card);
            self.getCardDetails();
            self.getItemDetails();

            self.getAPiKey();
            card = elements.create('card');
            card.mount(self.$refs.card);


        },
        beforeDestroy() {
            let self=this;

            card.destroy(self.$refs.card);
        },
        methods: {
            donate(){
                let self=this;
                var limit=self.item_details.item_price - self.item_details.sum_donated_amount;
                console.log(self.item_details.item_price);
                if(self.selectedCard != '0'){
                    self.error.selectedCard = false;
                }
                if(self.donation != null ){
                    self.error.donation =false;
                }
                if(self.selectedCard == '0'){
                    self.error.selectedCard = true;
                }
                else if(self.donation == null || self.donation == ''){
                    self.error.donation =true;
                    console.log("1s")
                }
                else {
                    axios.post('donate_via_saved', {
                        selectedCard:self.selectedCard,
                        item_data:self.item_details,
                        donatedAmount:self.donation}).then(function (response) {
                        if(response.data == "success"){
                            Vue.$toast.success("Thank you for donation");
                            self.$router.push({'name':'contributed-requests'});
                        }else{
                            Vue.$toast.error("Unknown Error Occurred");
                        }
                })
                }
            },
            pay () {
                let self=this;
                var limit=self.item_details.item_price - self.item_details.sum_donated_amount;
                console.log(limit);
                if(self.donation != null){
                    self.error.donation =false;
                }
                if(self.donation == null || self.donation == ''){
                    self.error.donation =true;
                }
                else {
                    stripe.createToken(card).then(function (response) {
                        if (response.error) {
                            self.hasCardErrors = true;
                            self.$forceUpdate(); // Forcing the DOM to update so the Stripe Element can update.
                            return;
                        }else {
                            axios.post('donate', {
                                source: response.token,
                                item_data: self.item_details,
                                donatedAmount: self.donation,
                                save_card:self.save_card
                            }).then(function (response) {
                                if (response.data == "success") {
                                    Vue.$toast.success("Thank you for donation");
                                    self.$router.push({'name':'contributed-requests'});
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
            getItemDetails(){
                let self = this;
                axios.post('get_item_to_donate',{id:self.$route.params.id}).then(function (response) {
                    self.item_details = response.data;

                }).catch(function (error) {

                });
            },
            getCardDetails(){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('getCardDetails',{key:"nun"})).then(function (response) {
                    self.card=response.data;
                });
            },
            getAPiKey(){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('getPublicKey',{key:"nun"})).then(function (response) {
                    self.stripe=response.data;
                });
            },
            back(){
                this.$router.go(-1);
            },
            changeDonation(){
                console.log("test");
                let self=this;
                if(self.donation == "1" || self.donation == "2"){
                    self.disable=true;
                }else{
                    self.disable=false;
                }
            },
            changePaymentAmount(){
                let self=this;
                self.description_show=true;
                axios.post('showDescription', {
                    item_data: self.item_details,
                    donatedAmount: self.donation,
                }).then(function (response) {
                   self.description=response.data;

                }).catch(function (error) {
                    Vue.$toast.error("Unknown Error Occurred");
                });
            }
        }
    }
</script>