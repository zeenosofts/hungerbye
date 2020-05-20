
<template>
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="/" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><img src="public/images/fav.png"/></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><img src='public/images/hungerByeMain.png' width="150"/></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning notifications-count">{{notifications[0]['count']}}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header notifications-bar-count">You have {{notifications[0]['count']}}  notifications <button type="button" @click="markAsRead" class="btn  btn-primary btn-xs">Mark as read</button></li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu notification">
                                        <li v-for="n in notifications" v-if="n.body != null"><router-link :to="n.class[2] != null ? n.class[2]: '#'" :key="n.id">
                                            <i :class="n.class[0]"></i> {{n.body}}
                                        </router-link></li>

                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="public/images/avatar.jpg" class="user-image" alt="User Image" >
                                <span class="hidden-xs">{{logined_user.first_name}}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="public/images/avatar.jpg" class="img-circle" alt="User Image">

                                    <p>
                                        {{logined_user.first_name}} - {{logined_user.display_name}}
                                    </p>
                                </li>
                                <!-- Menu Body -->

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <button v-if="logined_user.type != 'google'" @click='open_profile()' class="btn btn-default btn-flat">Profile</button>
                                    </div>
                                    <div class="pull-right">
                                        <form @submit="logoutFunction">

                                            <button type="submit" class="btn btn-default btn-flat">Sign out</button>
                                        </form>

                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->

                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="public/images/avatar.jpg" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{logined_user.first_name}}</p>
                        <small>{{logined_user.business_name}}</small><br>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <!-- search form -->

                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header"><i class="fa fa-dashboard"></i> MAIN NAVIGATION</li>
                    <li class="active">
                        <router-link to="/" :key="1">
                            <i class="fa fa-th"></i> <span>Dashboard</span>
                        </router-link>
                    </li>
                    <li class="header" v-if="role == 'superadmin' || role == 'partner' || role == 'manager'" ><i class="fa fa-user"></i> EMPLOYEES</li>
                    <li v-if="role == 'superadmin' || role == 'partner' || role == 'manager'" >
                        <router-link to="/admin/add-user" :key="2">
                            <i class="fa fa-user-plus"></i> <span>{{role == 'partner' || role == 'manager' ? "Add Staff" : "Add New"}}</span>
                        </router-link>
                    </li>
                    <li v-if="role == 'superadmin' || role == 'partner' || role == 'manager'" >
                        <router-link to="/admin/manage-user" :key="3">
                            <i class="fa fa-users"></i> <span>{{role == 'partner' || role == 'manager' ? "Manage Staff" : "Manage Existing"}}</span>
                        </router-link>
                    </li>

                    <li v-if="role == 'superadmin' || role == 'donor' || role == 'partner' || role == 'manager'" class="header"><i class="fa fa-cc-stripe"></i> Payments</li>
                    <li v-if="role == 'superadmin'" >
                        <router-link to="/admin/bank-details" :key="3">
                            <i class="fa fa-bank"></i> <span>Add Bank Account <small>Stripe</small></span>
                        </router-link>
                    </li>
                    <li v-if="role == 'partner'">
                        <router-link to="/partners/add-stripe" :key="3">
                            <i class="fa fa-cc-stripe"></i> <span>Manage Stripe</span>
                        </router-link>
                    </li>
                    <li v-if="role == 'superadmin' || role == 'donor' || role == 'partner' || role == 'manager'" >
                        <router-link to="/admin/card_details" >
                            <i class="fa fa-bank"></i> <span>Manage Payment Methods</span>
                        </router-link>
                    </li>
                    <li v-if="role == 'superadmin' || role == 'editor'"  class="header"><i class="fa fa-dashboard"></i> MANAGE USERS</li>
                    <li v-if="role == 'superadmin' || role == 'editor'" >
                        <router-link to="/admin/manage-donors" :key="3">
                            <i class="fa fa-heart"></i> <span>Manage Donors</span>
                        </router-link>
                    </li>
                    <li v-if="role == 'superadmin' || role == 'editor'" >
                        <router-link to="/admin/manage-partners" :key="3">
                            <i class="fa fa-male"></i> <span>Manage Partners</span>
                        </router-link>
                    </li>
                    <li class="header" v-if="role == 'partner' || role == 'manager' || role == 'staff'"><i class="fa fa-shopping-cart"></i> PRODUCTS</li>
                    <li v-if="role == 'partner' || role == 'manager' || role == 'staff'">
                        <router-link to="/partners/add-items" :key="3">
                            <i class="fa fa-cart-plus"></i> <span>Manage Products</span>
                        </router-link>
                    </li>

                    <li class="header"><i class="fa fa-reorder"></i> REQUESTS</li>
                    <li v-if="role == 'partner' || role == 'manager' || role == 'staff'">
                        <router-link to="/partner/post-item" :key="3">
                            <i class="fa  fa-check-square-o"></i> <span>Make New Request</span>
                        </router-link>
                    </li>
                    <li v-if="role == 'partner' || role == 'manager' || role == 'staff' ">
                        <router-link to="/partner/posted-items" :key="3">
                            <i class="fa fa-th-list"></i> <span>My Requests</span>
                        </router-link>
                    </li>
                    <li v-if="role == 'partner' || role == 'manager' || role == 'staff' ">
                        <router-link to="/requests/my-open24-requests" :key="3">
                            <i class="fa fa-th-list"></i> <span>My Requests Open Over 24hrs </span>
                        </router-link>
                    </li>

                    <li v-if="role == 'superadmin' || role == 'editor'">
                        <router-link to="/requests/posted-requests" :key="3">
                            <i class="fa fa-reorder"></i> <span>All Requests</span>
                        </router-link>
                    </li>
                    <li >
                        <router-link to="/requests/opened-requests" :key="3">
                            <i class="fa fa-reorder"></i> <span>Open Requests</span>
                        </router-link>
                    </li>
                    <li v-if="role == 'donor'">
                        <router-link to="/requests/contributed-requests" :key="3">
                            <i class="fa fa-reorder"></i> <span>Contributed to Requests</span>
                        </router-link>
                    </li>
                    <li v-if="role == 'superadmin' || role == 'editor'">
                        <router-link to="/requests/open24-requests" :key="3">
                            <i class="fa fa-reorder"></i> <span>Requests Open Over 24hrs</span>
                        </router-link>
                    </li>
                    <li class="header"><i class="far fa-cocktail"></i> Offers</li>
                    <li v-if="role == 'partner' || role == 'manager' || role == 'staff'">
                        <router-link to="/partners/add-offers" :key="3">
                            <i class="far fa-cocktail"></i> <span>Manage Offers</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/offers/all" :key="3">
                            <i class="fas fa-coffee"></i> <span>Offers</span>
                        </router-link>
                    </li>
                    <li class="header"><i class="fas fa-hands-helping"></i> Partners</li>
                    <li>
                        <router-link to="/partners/all" :key="3">
                            <i class="far fa-handshake"></i> <span>Our Partners</span>
                        </router-link>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div id="app">
                <!--<button type="button" @click="selectedComponent = 'ExampleComponentOther'">Open</button>-->
             <!--<component :is='selectedComponent' :da="da"></component>-->
                <router-view :key="$route.fullPath"></router-view>
            </div>



        </div>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 1.0
            </div>
            <strong>Copyright &copy; {{new Date() | moment('YYYY')}} <a href="https://cybermeteors.com">Cyber Meteors</a>.</strong> All rights
            reserved.
        </footer>

        <!-- Control Sidebar -->

        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
</template>
<script>
    import main from './script/main';
    export default {
           data() {
              return {

                  selectedComponent: '',
                  messages:"",
                  overlay:false,
                  da:"fasssss",
                  logined_user:{},
                  role:'',
                  notifications:[],
                  stripePubKey:''
              }
          },
        mounted()
        {
            let self=this;

            self.getInformationOfLoginUser();
            self.getRoleIdOnly();
            self.getNotifications();

            self.$nextTick(function () {
                window.setInterval(() => {
                    self.getNotifications();
                },30000);
            })
//            Vue.nextTick(function () {
//                $('[data-toggle="tooltip"]').tooltip()
//            });
            //this.$router.push({'name':'my-dashboard'});
        },
        methods:
            {
//@click="selectedComponent = 'ExampleComponentOther'"
            openDashBoardForCompany()
                {
                    //this.$router.push({'name':'admin-dashboard'});
                },
            openDashBoardForMe()
            {
                this.$router.push({'name':'my-dashboard'});
            },
                logoutFunction(e) {

                    e.preventDefault();

                    let currentObj = this;

                    axios.post('logoutNow', {

                    })
                        .then(function (response) {

                           if(response.data == "logout"){
                               window.localStorage.setItem('role','');
                               location.reload();
                           }

                        })

                        .catch(function (error) {

                            currentObj.output = error;

                        });


                },
            isNumber(evt)
            {

                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();

                } else {
                    return true;
                }
            },
            isAlphabet(e)
            {
                var regex = new RegExp("^[a-zA-Z]+$");
                var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
                if (regex.test(str)) {
                    return true;
                }
                else {
                    e.preventDefault();
                    alert('Please Enter Alphabate');
                    return false;
                }
            },
            getInformationOfLoginUser()
            {
                let self=this;
                axios.get('get_logined_user_information').then(function (response) {
                    self.logined_user=response.data;
                    self.role=response.data.role_name;
                }).catch(function (error) {

                });
            },
            getNotifications()
            {
                let self=this;
                axios.get('getNotifications').then(function (response) {
                    self.notifications=response.data;
                }).catch(function (error) {

                });
            },
            open_profile()
            {
                let self=this;
                console.log("ghjghj"+self.logined_user.user_id);
                var md5 = require('md5');
                this.$router.push({'name':'user-edit-profile',params:{'id':self.logined_user.user_id,'hash':md5(self.logined_user.user_id)}});
            },
            getAPiKey(){
                let self=this;
                //self.stripe="pk_test_CuJotvmeGq97J1dFuxfLz9Ik00x8r6riIb";
                Promise.resolve(main.sendPOSTRequest('getPublicKey',{key:"nun"})).then(function (response) {
                    self.stripePubKey=response.data;
                });
            },
            markAsRead(){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('markAsRead',{key:"nun"})).then(function (response) {
                    self.getNotifications();
                });
            },
            markThisComplete(id){
                let self=this;
                Promise.resolve(main.sendPOSTRequest('markAsReadThisNotif',{id:id})).then(function (response) {
                    self.getNotifications();
                });
            },
            getRoleIdOnly(){
                axios.post('getRole', {
                    data: 'a',
                }).then(function (response) {
                    window.localStorage.setItem('role',response.data);
                }).catch(function (error) {
                    Vue.$toast.error(" Error Occurred");
                });


            }
            },
        created() {
            axios.interceptors.request.use((config) => {

                if(config.url != "getNotifications"){
                    this.overlay=true ;
                }

                return config;

            }, (error) => {
                this.overlay=false ;
                return Promise.reject(error);
            });

            axios.interceptors.response.use((response) => {
                this.overlay=false ;
                //

                return response;
            }, (error) => {
                this.overlay=false ;

                return Promise.reject(error);
            });
        },
    }
</script>
