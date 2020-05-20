<template >
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Quick Links > Create Company</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Quick Links</a></li>
                <li class="active">Create Company</li>
            </ol>
        </section>
        <section class="content" >
        <div class="row">
            <div class="col-lg-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-suitcase"></i>

                        <h3 class="box-title">Create Company</h3>

                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label"> Enter Company Name</label>
                            <!--onkeypress="return (event.charCode > 64 &&-->
                            <!--event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"-->
                            <input type="text" :class="{'has-error':error}" class=" form-control" id="company_name"  v-model="company.company_name" placeholder="Company Name">
                            <span class="help-block" v-show="error">Company name cannot be empty!</span>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                        <button type="button" class="btn btn-primary btn-flat pull-right" @click="add_company"><i class="fa fa-plus"></i> Add Company</button>
                    </div>
                    <div  v-if="this.$parent.overlay" class="overlay">
                        <i class="fa fa-refresh fa-spin color-green"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="ion ion-clipboard"></i>

                        <h3 class="box-title">Company List</h3>

                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(companyy,index) in companies" :key="companyy.id">
                                <td>{{companyy.company_name}}</td>
                                <td><button class="btn btn-warning btn-sm btn-flat"  @click="getDataToEditCompany(companyy.id)"><i class="fa fa-pencil"></i> Edit</button></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
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
    export default {
        data()
        {
            return{
            companies:[],
             error: false,
             overlay:false,
             company:{'company_name':''},

            }

        },
        created() {
            this.getAllCompanies();

        },
        methods:{
            add_company(){
                if(this.company.company_name.trim().length != 0)
                {
                    this.error=false;
                }
                if(this.company.company_name.trim().length == 0 )
                {
                    this.error=true;
                }
                else {
                    let self=this;
                    axios.post('add_company', this.company).then(function (response) {
                        if (response.data == "duplicate") {
                           // alert("sadsadsadsa");
                            Vue.$toast.warning("Company name already present!");
                        }
                        else if (response.data == "saved") {
                            self.getAllCompanies();
                            Vue.$toast.success("Company saved successfully!");
                        }
                        else if (response.data == "notsaved") {
                            Vue.$toast.error("Unsuccessful Action!");
                        }
                    }).catch(function (error) {

                    });
                }
            },
            /*test()
            {
                axios.post('test_notification').then(function (response) {
                }).catch(function (error) {

                });
            },*/
            getAllCompanies(){
                let self=this;
                axios.get('get_company').then(function (response) {
                    self.companies=response.data;
                }).catch(function (error) {

                });
            },
            getDataToEditCompany(company_id)
            {
                this.$router.push({name:'edit_company',params:{id:company_id}})
               // this.companies.splice(table_index, 1);
            },



        }
    }
</script>
