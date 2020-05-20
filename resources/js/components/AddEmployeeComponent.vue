<template>
    <div>
        <section class="content-header">
            <h1>
                Dashboard
                <small>Employees > Add Employees</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Employees</a></li>
                <li class="active">Add Employees</li>
            </ol>
        </section>
        <section class="content" >
            <form @submit="saveEmployee" enctype="multipart/form-data">
        <div class="row">

            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-primary">
                            <div class="box-header">
                                <i class="fa fa-user-circle"></i>

                                <h3 class="box-title">Personal Information</h3>

                                <div class="box-tools pull-right">

                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="control-label"> First Name</label>
                                            <input type="text"  class="form-control abc"  placeholder="First Name" v-model="employee_data.first_name" onkeypress="return (event.charCode > 64 &&
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
                                            <span class="help-block" v-show="error.first_name">First name can't be empty!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="control-label"> Middle Name</label>
                                            <input type="text" class="form-control"  placeholder="Middle Name" v-model="employee_data.middle_name" onkeypress="return (event.charCode > 64 &&
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
                                            <span class="help-block" v-show="error.middle_name">Middle name can't be empty!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> Last Name</label>
                                    <input type="text" class="form-control"  placeholder="Last Name" v-model="employee_data.last_name" onkeypress="return (event.charCode > 64 &&
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
                                    <span class="help-block" v-show="error.last_name">Last name can't be empty!</span>
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <multiselect v-model="employee_data.gender" :options="optionsForGender" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Pick One" label="name" track-by="name" :preselect-first="true">

                                    </multiselect>
                                    <span class="help-block" v-show="error.gender">Please select a gender!</span>
                                </div>
                                <div class="form-group">
                                    <label>Civil Status</label>
                                    <multiselect v-model="employee_data.civil_status" :options="optionsForCivilStatus" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Pick One" label="name" track-by="name" :preselect-first="true">

                                    </multiselect>
                                    <span class="help-block" v-show="error.civil_status">Please select a civil status!</span>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="control-label"> Email Address <small>Personal</small></label>
                                            <input type="text" class="form-control"  placeholder="Personal Email" v-model="employee_data.email_personal" >
                                            <span class="help-block" v-show="error.email_personal">Personal Email can't be empty!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="control-label"> Mobile Number <small>(1xxxxxxxxxxx without <b>+</b>)</small></label>
                                            <input type="text" class="form-control"  placeholder="Mobile Number" v-model="employee_data.mobile_number" @keypress="isNumber($event)">
                                            <span class="help-block" v-show="error.mobile_number">Mobile Number can't be empty!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="control-label"> Date of Birth</label>
                                            <input type="date" class="form-control"  placeholder="Date of Birth" v-model="employee_data.dob">
                                            <span class="help-block" v-show="error.dob">Please choose DOB!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> License Number</label>
                                    <input type="text" class="form-control"  placeholder="License Number" v-model="employee_data.cnic" @keypress="isNumber($event)">
                                    <span class="help-block" v-show="error.cnic">License can't be empty!</span>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="control-label"> Issue Date</label>
                                            <input type="date" class="form-control"  v-model="employee_data.issue_date" >
                                            <span class="help-block" v-show="error.issue_date">Issue date can't be empty!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="control-label"> Expiry Date</label>
                                            <input type="date" class="form-control"   v-model="employee_data.expiry_date">
                                            <span class="help-block" v-show="error.expiry_date">Expiry date can't be empty!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Place of Birth</label>
                                    <input type="text" class="form-control"  placeholder="City,Province,Country" v-model="employee_data.place_of_birth" onkeypress="return (event.charCode > 64 &&
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
                                    <span class="help-block" v-show="error.place_of_birth">Please select place of birth!</span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> Home Address</label>
                                    <input type="text" class="form-control"  placeholder="Home Address" v-model="employee_data.home_address">
                                    <span class="help-block" v-show="error.home_address">Home address can't be empty!</span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> Upload Profile Photo</label>
                                    <input type="file" class="form-control"  placeholder="Profile Image" @change="onImageChange">
                                    <span class="help-block" v-show="error.photo_url">Please choose an image!</span>
                                </div>




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

            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-primary">
                            <div class="box-header">
                                <i class="fa fa-black-tie"></i>

                                <h3 class="box-title">Designation</h3>

                                <div class="box-tools pull-right">

                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">

                                <div class="form-group">
                                    <label>Company</label>
                                    <multiselect  v-model="employee_data.company_id" :options="optionsForCompany" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Pick One" label="company_name" track-by="company_name" :preselect-first="true">

                                    </multiselect>
                                    <span class="help-block" v-show="error.company_id">Please select a company!</span>
                                </div>
                                <div class="form-group">
                                    <label>Department</label>
                                    <multiselect v-model="employee_data.dept_id" :options="optionsForDepartments" @input="getAllJobForThisDepartment" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Pick One" label="department_name" track-by="department_name" :preselect-first="true">

                                    </multiselect>
                                    <span class="help-block" v-show="error.dept_id">Please select a department!</span>
                                </div>
                                <div class="form-group">
                                    <label>Job Title/Position</label>
                                    <multiselect v-model="employee_data.job_id" :options="optionsForJobTitle"  :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Pick One" label="job_title" track-by="job_title" :preselect-first="true">

                                    </multiselect>
                                    <span class="help-block" v-show="error.job_id">Please select a Job Tile!</span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> ID Number</label>
                                    <input type="text" class="form-control"  placeholder="ID Number" v-model="employee_data.id_no">
                                    <span class="help-block" v-show="error.id_no">Please choose an ID Number!</span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> Email Address <small>Company</small></label>
                                    <input type="text" class="form-control"  placeholder="Company Email" v-model="employee_data.email_company">
                                    <span class="help-block" v-show="error.email_company">Company Email can't be empty!</span>
                                </div>
                                <div class="form-group">
                                    <label>Leave Group</label>
                                    <multiselect v-model="employee_data.leave_group_id" :options="leaveGroups" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Pick One" label="leaveGroupName" track-by="leaveGroupName" :preselect-first="true">

                                    </multiselect>
                                    <span class="help-block" v-show="error.leave_group_id">Please select a leave group!</span>
                                </div>

                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix no-border">

                            </div>
                            <div  v-if="this.$parent.overlay" class="overlay">
                                <i class="fa fa-refresh fa-spin color-green"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="box box-primary">
                            <div class="box-header">
                                <i class="fa fa-user"></i>

                                <h3 class="box-title">Employee Information</h3>

                                <div class="box-tools pull-right">

                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Employee Type</label>
                                            <multiselect v-model="employee_data.employee_type" :options="optionsForEmployeeType" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Pick One" label="name" track-by="name" :preselect-first="true">

                                            </multiselect>
                                            <span class="help-block" v-show="error.employee_type">Please select an employee type!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Employee Role</label>
                                            <multiselect v-model="employee_data.employee_role" :options="optionsForEmployeeRole" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Pick One" label="name" track-by="name" :preselect-first="true">

                                            </multiselect>
                                            <span class="help-block" v-show="error.employee_role">Please select an employee role!</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Employee Status</label>
                                    <multiselect v-model="employee_data.employee_status" :options="optionsForStatus" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Pick One" label="name" track-by="name" :preselect-first="true">

                                    </multiselect>
                                    <span class="help-block" v-show="error.employee_status">Please select an employee status!</span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> Official Date Start</label>
                                    <input type="date" class="form-control"  placeholder="Date Start" v-model="employee_data.official_date_start">
                                    <span class="help-block" v-show="error.official_date_start">Please choose a date!</span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> Date Regularized</label>
                                    <input type="date" class="form-control"  placeholder="Regularized Date" v-model="employee_data.official_date_regularized">
                                    <span class="help-block" v-show="error.official_date_regularized">Please choose a date!</span>
                                </div>


                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix no-border">
                                <button type="button" class="btn btn-primary btn-flat pull-right" @click="saveEmployee"><i class="fa fa-plus"></i> Add Employee</button>
                            </div>
                            <div  v-if="this.$parent.overlay" class="overlay">
                                <i class="fa fa-refresh fa-spin color-green"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
            </form>
        </section>
    </div>

</template>

<script>
    export default {
        data()
        {
            return{
             error: {'first_name':false,'last_name':false,
                 'email':false,'password':false,'role':false},
                employee_data:{'first_name':'','last_name':'',
                'email':'','password':'',role:'0'},
                roles:[],
            }
        },
        mounted() {
        },
        methods: {
            validEmail(email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            getRoles(){
                let self=this;
                console.log("dasdsa"+self.$parent.role);
//                if(self.$parent.data.role)
                Promise.resolve(main.sendPOSTRequest('getRoles',{key:"nun"})).then(function (response) {
                    self.roles=response.data;
                });
            }
        }
    }
</script>
