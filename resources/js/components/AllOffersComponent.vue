<template >
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Offers > All Offers</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Offers</a></li>
                <li class="active">All Offers</li>
            </ol>
        </section>
        <section class="content" >
        <div class="row">
            <div class="col-md-3" v-for="o in offers">
                <!-- Box Comment -->
                <div class="box box-widget">
                    <div class="box-header with-border">
                        <div class="user-block">
                            <img class="img-circle" src="public/images/avatar.jpg" alt="User Image">
                            <span class="username"><router-link :to="'/partner-profile/'+o.user_id">{{o.business_name}}</router-link></span>
                            <span class="description">Shared publicly - {{o.offer_time}}</span>
                        </div>
                        <!-- /.user-block -->
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- post text -->
                        <h5>{{o.item_name}}</h5>
                        <p>Offered Price: <b>${{parseFloat(o.item_price).toFixed(2)}}</b></p>
                        <p>{{o.item_description}}</p>
                        <p><i class="fa fa-map-marker"></i> {{o.business_address}}</p>

                        <!-- Attachment -->
                        <!-- /.attachment-block -->

                        <!-- Social sharing buttons -->
                    </div>
                    <!-- /.box-body -->

                    <!-- /.box-footer -->
                </div>
                <!-- /.box -->
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
                offers:[],
            }
        },
        mounted() {
            let self=this;
            self.getOffers();
        },
        methods: {
            getOffers(){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('getAllOffersWithNames',{key:"nun"})).then(function (response) {
                    self.offers=response.data;
                });
            },

        }
    }
</script>
