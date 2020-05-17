/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
//First Install Axios then Vue router then use these thing
require('./bootstrap');

window.Vue = require('vue');
window.axios=require('axios');

Vue.use(require('vuejs-datatable'));
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/AddCompanyComponent.vue -> <example-component></example-component>
 */
import VuejsDialog from 'vuejs-dialog';

// include the default style
import 'vuejs-dialog/dist/vuejs-dialog.min.css';

// Tell Vue to install the plugin.
Vue.use(VuejsDialog);
// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
import VueRouter from 'vue-router'
import axios from 'axios'
axios.defaults.headers.common={
    'X-Requested-With' : 'XMLHttpRequest,' , 'X-CSRF-TOKEN':window.csrfToken
};


Vue.use(VueRouter);
window.Event = new Vue();
import ToggleButton from 'vue-js-toggle-button'
Vue.use(ToggleButton);
// vue_Toast
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/index.css';
import VTooltip from 'v-tooltip';

Vue.use(VTooltip);
Vue.use(VueToast, {
    // One of options
    position: 'top-right'
});
//Vue.config.productionTip = false

//Vue.use();
import Multiselect from 'vue-multiselect';
Vue.use(require('vue-moment'));
// register globally
Vue.component('multiselect', Multiselect);
import Datetime from 'vue-datetime';
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css';
Vue.use(Datetime);
// import draggable from 'vuedraggable';
// Vue.use(draggable);
import CKEditor from '@ckeditor/ckeditor5-vue';

Vue.use( CKEditor );
/*
1 = superadmin
2 = editor
"3" = donor
"4" = partner
"5" = staff
"6" = manager
 */
const routes = [ ///
    //{ path: '/home', component: require('./components/MainHeader.vue').default },
    { path: '/',name:'dashboard', component: require('./components/MyDashboardComponent.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    } },
    { path: '/admin/manage-user',name:'admin-manage-user', component: require('./components/ManageUserTable.vue').default,meta:{
        requireAuth:true , roles:["1","2","4","6"]
    }  },
    { path: '/admin/add-user',name:'admin-add-user', component: require('./components/AddUserComponent.vue').default,meta:{
        requireAuth:true , roles:["1","2","4","6"]
    }  },
    { path: '/admin/edit-user/:id/:hash',name:'admin-edit-user', component: require('./components/EditUserComponent.vue').default,meta:{
        requireAuth:true , roles:["1","2","4","6"]
    }  },
    { path: '/user/edit-profile/:id/:hash',name:'user-edit-profile', component: require('./components/EditUserComponent.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    }  },
    { path: '/my/dashboard',name:'my-dashboard', component: require('./components/MyDashboardComponent.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    }  },
    { path: '/admin/bank-details',name:'admin-bank-details', component: require('./components/AddBankDetailsComponent.vue').default,meta:{
        requireAuth:true , roles:["1"]
    }  },
    { path: '/admin/card_details',name:'admin-card-details', component: require('./components/AddCardDetailsComponent.vue').default,meta:{
        requireAuth:true , roles:["1","3","4","6"]
    }  },
    { path: '/admin/new_card',name:'admin-new-card-details', component: require('./components/AddCardComponent.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    }  },
    { path: '/user/bank-details',name:'user-bank-details', component: require('./components/AddUserBankDetailsComponent.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    }  },
    { path: '/admin/manage-partners',name:'admin-manage-partners', component: require('./components/ManagePartnersTable.vue').default,meta:{
        requireAuth:true , roles:["1","2"]
    }  },
    { path: '/admin/manage-donors',name:'admin-manage-donors', component: require('./components/ManageDonorsTable.vue').default,meta:{
        requireAuth:true , roles:["1","2"]
    }  },
    { path: '/user/view_user',name:'user-view', component: require('./components/UserProfileComponent.vue').default,meta:{
        requireAuth:true , roles:["1","2","4","6"]
    }  },
    { path: '/partners/add-items',name:'add-items', component: require('./components/AddItemComponent.vue').default,meta:{
        requireAuth:true , roles:["4","5","6"]
    }  },
    { path: '/partners/add-offers',name:'add-offers', component: require('./components/AddOfferComponent.vue').default,meta:{
        requireAuth:true , roles:["4","5","6"]
    }  },
    { path: '/offers/all',name:'all-offers', component: require('./components/AllOffersComponent.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    }  },
    { path: '/partners/edit-items/:id',name:'edit-items', component: require('./components/EditItemComponent.vue').default,meta:{
        requireAuth:true , roles:["4","5","6"]
    }  },
    { path: '/partners/edit-offer/:id',name:'edit-offer', component: require('./components/EditOfferComponent.vue').default,meta:{
        requireAuth:true , roles:["4","5","6"]
    }  },
    { path: '/partner/post-item',name:'post-items', component: require('./components/PostItemComponent.vue').default,meta:{
        requireAuth:true , roles:["4","5","6"]
    }  },
    { path: '/partner/posted-items',name:'posted-items', component: require('./components/ManageItemsTable.vue').default,meta:{
        requireAuth:true , roles:["4","5","6"]
    }  },
    { path: '/requests/posted-requests',name:'posted-requests', component: require('./components/RequestsItemsTable.vue').default,meta:{
        requireAuth:true , roles:["1","2"]
    }  },
    { path: '/requests/opened-requests',name:'opened-requests', component: require('./components/OpenedRequestsItemsTable.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    }  },
    { path: '/requests/open24-requests',name:'open24-requests', component: require('./components/Open24RequestsItemsTable.vue').default,meta:{
        requireAuth:true , roles:["1","2"]
    }  },
    { path: '/requests/my-open24-requests',name:'my-open24-requests', component: require('./components/MyOpen24RequestsItemsTable.vue').default,meta:{
        requireAuth:true , roles:["4","5","6"]
    }  },
    { path: '/requests/contributed-requests',name:'contributed-requests', component: require('./components/ContributedRequestsItemsTable.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    }  },
    { path: '/requests/donate/:id',name:'donate', component: require('./components/PayUsingStripComponent.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    }  },
    { path: '/partners/all',name:'all-partners', component: require('./components/AllPartnersTable.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    }  },
    { path: '/partner-profile/:id',name:'partner-profile', component: require('./components/PartnerProfileComponent.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    }  },
    { path: '/partners/add-stripe',name:'partner-stripe', component: require('./components/AddPartnerStripeComponent.vue').default,meta:{
        requireAuth:true , roles:["4","5","6"]
    }  },{ path: '/error',name:'error', component: require('./components/Error.vue').default,meta:{
        requireAuth:true , roles:["1","2","3","4","5","6"]
    }  }

//

    ///attendance/reports{ path: '/test1/:pid',name:'details', component: require('./components/ExampleComponentOther.vue').default }
]

//Registering Globally Components//
//Vue.component('example-component', require('./components/AddCompanyComponent.vue').default);
Vue.component('header-component',require('./components/MainHeader.vue').default);
//Vue.component('example-component-other', require('./components/ExampleComponentOther.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
/*
const app = new Vue({
    el: '#app',
});
*/
var md5 = require('md5');
Vue.prototype.$roleName="no";
const router = new VueRouter({
   // mode: 'history',
    routes //passing routes in vueRouter
});
router.beforeEach((to, from, next) => {

   let role=window.localStorage.getItem('role');
   console.log("role"+role+to.name);
   let rolesArray=to.meta.roles;
    if(role == ''){
        axios.post('logoutNow', {
        })
            .then(function (response) {
                if(response.data == "logout"){
                    location.reload();
                }
            })

            .catch(function (error) {
                currentObj.output = error;
            });
    }
    console.log('array',rolesArray);
    if(rolesArray.indexOf(role) > -1){
        next();
    }else{
        next({'path':'error'});
    }


});
const app = new Vue({
    router, //passing router of routes in app id
    // watch:{
    //     $route (to, from){
    //         return false;
    //         console.log(to);
    //     }
    // }
}).$mount('#app');