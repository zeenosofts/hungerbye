<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{URL::asset('/public/images/fav.png')}}" type="image/png" sizes="16x16">
    <title>HungerBye</title>
    <script >window.csrfToken = "{{csrf_token()}}"</script>
    <link rel="manifest" href="/manifest.json"/>
    <link rel="stylesheet" href="{{URL::asset('public/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{URL::asset('public/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{URL::asset('public/bower_components/Ionicons/css/ionicons.min.css')}}">

    <link rel="stylesheet" href="{{URL::asset('public/dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{URL::asset('public/dist/css/skins/_all-skins.min.css')}}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{URL::asset('public/bower_components/morris.js/morris.css')}}">

    <!-- jvectormap -->
    <link rel="stylesheet" href="{{URL::asset('public/bower_components/jvectormap/jquery-jvectormap.css')}}">
    <link rel="stylesheet" href="{{URL::asset('public/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{URL::asset('public/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{URL::asset('public/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{URL::asset('public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('public/plugins/iCheck/all.css')}}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        let stripe = Stripe("{{env("STRIPE_PUBLIC_KEY")}}"),
            elements = stripe.elements(),
            card = undefined;
    </script>



</head>
<style>
    /* Credit card css */
    .card {
        position: relative;;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        margin-left:25px;
        width: 95.60mm;
        height: 53.98mm;
        color: #fff;
        font: 22px/1 'Iceland', monospace;
        background: #23189a;
        border: 1px solid #1e1584;
        -webkit-border-radius: 10px;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 10px;
        -moz-background-clip: padding;
        border-radius: 10px;
        background-clip: padding-box;
        overflow: hidden;
    }
    .bank-name {
        float: right;
        margin-top: 15px;
        margin-right: 30px;
        font: 800 28px 'Open Sans', Arial, sans-serif;
    }
    .chip {
        position: relative;
        z-index: 1000;
        width: 50px;
        height: 40px;
        margin-top: 50px;
        margin-bottom: 10px;
        margin-left: 30px;
        background: #fffcb1;
        /* Old browsers */
        background: -moz-linear-gradient(-45deg, #fffcb1 0%, #b4a365 100%);
        /* FF3.6+ */
        background: -webkit-gradient(linear, left top, right bottom, color-stop(0%, #fffcb1), color-stop(100%, #b4a365));
        /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(-45deg, #fffcb1 0%, #b4a365 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(-45deg, #fffcb1 0%, #b4a365 100%);
        /* Opera 11.10+ */
        background: -ms-linear-gradient(-45deg, #fffcb1 0%, #b4a365 100%);
        /* IE10+ */
        background: linear-gradient(135deg, #fffcb1 0%, #b4a365 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#fffcb1", endColorstr="#b4a365", GradientType=1);
        /* IE6-9 fallback on horizontal gradient */
        border: 1px solid #322d28;
        -webkit-border-radius: 10px;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 10px;
        -moz-background-clip: padding;
        border-radius: 10px;
        background-clip: padding-box;
        -webkit-box-shadow: 0 1px 2px #322d28, 0 0 5px 0 0 5px rgba(144, 133, 87, 0.25) inset;
        -moz-box-shadow: 0 1px 2px #322d28, 0 0 5px 0 0 5px rgba(144, 133, 87, 0.25) inset;
        box-shadow: 0 1px 2px #322d28, 0 0 5px 0 0 5px rgba(144, 133, 87, 0.25) inset;
        overflow: hidden;
    }
    .chip .side {
        position: absolute;
        top: 8px;
        width: 12px;
        height: 24px;
        border: 1px solid #322d28;
        -webkit-box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1), 0 0 4px rgba(0, 0, 0, 0.1) inset;
        -moz-box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1), 0 0 4px rgba(0, 0, 0, 0.1) inset;
        box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1), 0 0 4px rgba(0, 0, 0, 0.1) inset;
    }
    .chip .side.left {
        left: 0;
        border-left: none;
        -webkit-border-radius: 0 2px 2px 0;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 0 2px 2px 0;
        -moz-background-clip: padding;
        border-radius: 0 2px 2px 0;
        background-clip: padding-box;
    }
    .chip .side.right {
        right: 0;
        border-right: none;
        -webkit-border-radius: 2px 0 0 2px;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 2px 0 0 2px;
        -moz-background-clip: padding;
        border-radius: 2px 0 0 2px;
        background-clip: padding-box;
    }
    .chip .side:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        display: inline-block;
        width: 100%;
        height: 0px;
        margin: auto;
        border-top: 1px solid #322d28;
        -webkit-box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1);
        -moz-box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1);
        box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1);
    }
    .chip .vertical {
        position: absolute;
        left: 0;
        right: 0;
        margin: 0 auto;
        width: 8.66666667px;
        height: 12px;
        border: 1px solid #322d28;
        -webkit-box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1), 0 0 4px rgba(0, 0, 0, 0.1) inset;
        -moz-box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1), 0 0 4px rgba(0, 0, 0, 0.1) inset;
        box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1), 0 0 4px rgba(0, 0, 0, 0.1) inset;
    }
    .chip .vertical.top {
        top: 0;
        border-top: none;
    }
    .chip .vertical.top:after {
        top: 12px;
        width: 17.33333333px;
    }
    .chip .vertical.bottom {
        bottom: 0;
        border-bottom: none;
    }
    .chip .vertical.bottom:after {
        bottom: 12px;
    }
    .chip .vertical:after {
        content: '';
        position: absolute;
        left: -8.66666667px;
        display: inline-block;
        width: 26px;
        height: 0px;
        margin: 0;
        border-top: 1px solid #322d28;
        -webkit-box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1);
        -moz-box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1);
        box-shadow: 0 0 5px rgba(144, 133, 87, 0.25) inset, 0 0 5px rgba(144, 133, 87, 0.25), 0 0 4px rgba(0, 0, 0, 0.1);
    }
    .data {
        position: relative;
        z-index: 100;
        margin-left: 30px;
        text-transform: uppercase;
    }
    .data .pan,
    .data .month,
    .data .year,
    .data .year:before,
    .data .name-on-card,
    .data .date {
        position: relative;
        z-index: 20;
        letter-spacing: 1px;
        text-shadow: 1px 1px 1px #000;
    }
    .data .pan:before,
    .data .month:before,
    .data .year:before,
    .data .year:before:before,
    .data .name-on-card:before,
    .data .date:before,
    .data .pan:after,
    .data .month:after,
    .data .year:after,
    .data .year:before:after,
    .data .name-on-card:after,
    .data .date:after {
        position: absolute;
        z-index: -10;
        content: attr(title);
        color: rgba(0, 0, 0, 0.2);
        text-shadow: none;
    }
    .data .pan:before,
    .data .month:before,
    .data .year:before,
    .data .year:before:before,
    .data .name-on-card:before,
    .data .date:before {
        top: -1px;
        left: -1px;
        color: rgba(0, 0, 0, 0.1);
    }
    .data .pan:after,
    .data .month:after,
    .data .year:after,
    .data .year:before:after,
    .data .name-on-card:after,
    .data .date:after {
        top: 1px;
        left: 1px;
        z-index: 10;
    }
    .data .pan {
        position: relative;
        z-index: 50;
        margin: 0;
        letter-spacing: 1px;
        font-size: 26px;
    }
    .data .first-digits {
        margin: 0;
        font: 400 10px/1 'Open Sans', Arial, sans-serif;
    }
    .data .exp-date-wrapper {
        margin-top: 5px;
        margin-left: 64px;
        line-height: 1;
        *zoom: 1;
    }
    .data .exp-date-wrapper:before,
    .data .exp-date-wrapper:after {
        content: " ";
        display: table;
    }
    .data .exp-date-wrapper:after {
        clear: both;
    }
    .data .exp-date-wrapper .left-label {
        float: left;
        display: inline-block;
        width: 40px;
        font: 400 7px/8px 'Open Sans', Arial, sans-serif;
        letter-spacing: 0.5px;
    }
    .data .exp-date-wrapper .exp-date {
        display: inline-block;
        float: left;
        margin-top: -10px;
        margin-left: 10px;
        text-align: center;
    }
    .data .exp-date-wrapper .exp-date .upper-labels {
        font: 400 7px/1 'Open Sans', Arial, sans-serif;
    }
    .data .exp-date-wrapper .exp-date .year:before {
        content: '/';
    }
    .data .name-on-card {
        margin-top: 10px;
    }
    .lines-down:before {
        content: '';
        position: absolute;
        top: 80px;
        left: -200px;
        z-index: 10;
        width: 550px;
        height: 400px;
        border-top: 2px solid #392db2;
        -webkit-border-radius: 40% 60% 0 0;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 40% 60% 0 0;
        -moz-background-clip: padding;
        border-radius: 40% 60% 0 0;
        background-clip: padding-box;
        box-shadow: 1px 1px 100px #4035b2;
        background: #2d21a6;
        /* Old browsers */
        background: -moz-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 100%, #2d21a6 100%);
        /* FF3.6+ */
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(45, 33, 166, 0)), color-stop(100%, #2d21a6));
        /* Chrome,Safari4+ */
        background: -webkit-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 100%, #2d21a6 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 44%, #2d21a6 100%);
        /* Opera 12+ */
        background: -ms-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 44%, #2d21a6 100%);
        /* IE10+ */
        background: radial-gradient(ellipse at center, rgba(45, 33, 166, 0) 44%, #2d21a6 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="rgba(45, 33, 166, 0)", endColorstr="#2d21a6", GradientType=1);
        /* IE6-9 fallback on horizontal gradient */
    }
    .lines-down:after {
        content: '';
        position: absolute;
        top: 20px;
        left: -100px;
        z-index: 10;
        width: 350px;
        height: 400px;
        border-top: 2px solid #392db2;
        -webkit-border-radius: 20% 80% 0 0;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 20% 80% 0 0;
        -moz-background-clip: padding;
        border-radius: 20% 80% 0 0;
        background-clip: padding-box;
        box-shadow: inset -1px -1px 44px #4035b2;
        background: #2d21a6;
        /* Old browsers */
        background: -moz-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 100%, #2d21a6 100%);
        /* FF3.6+ */
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(45, 33, 166, 0)), color-stop(100%, #2d21a6));
        /* Chrome,Safari4+ */
        background: -webkit-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 100%, #2d21a6 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 44%, #2d21a6 100%);
        /* Opera 12+ */
        background: -ms-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 44%, #2d21a6 100%);
        /* IE10+ */
        background: radial-gradient(ellipse at center, rgba(45, 33, 166, 0) 44%, #2d21a6 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="rgba(45, 33, 166, 0)", endColorstr="#2d21a6", GradientType=1);
        /* IE6-9 fallback on horizontal gradient */
    }
    .lines-up:before {
        content: '';
        position: absolute;
        top: -110px;
        left: -70px;
        z-index: 2;
        width: 480px;
        height: 300px;
        border-bottom: 2px solid #392db2;
        -webkit-border-radius: 0 0 60% 90%;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 0 0 60% 90%;
        -moz-background-clip: padding;
        border-radius: 0 0 60% 90%;
        background-clip: padding-box;
        box-shadow: inset 1px 1px 44px #4035b2;
        background: #4031b2;
        /* Old browsers */
        background: -moz-radial-gradient(center, ellipse cover, rgba(64, 49, 178, 0) 100%, #23189a 100%);
        /* FF3.6+ */
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(64, 49, 178, 0)), color-stop(100%, #23189a));
        /* Chrome,Safari4+ */
        background: -webkit-radial-gradient(center, ellipse cover, rgba(64, 49, 178, 0) 100%, #23189a 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-radial-gradient(center, ellipse cover, rgba(64, 49, 178, 0) 44%, #23189a 100%);
        /* Opera 12+ */
        background: -ms-radial-gradient(center, ellipse cover, rgba(64, 49, 178, 0) 44%, #23189a 100%);
        /* IE10+ */
        background: radial-gradient(ellipse at center, rgba(64, 49, 178, 0) 44%, #23189a 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="rgba(64, 49, 178, 0)", endColorstr="#23189a", GradientType=1);
        /* IE6-9 fallback on horizontal gradient */
    }
    .lines-up:after {
        content: '';
        position: absolute;
        top: -180px;
        left: -200px;
        z-index: 1;
        width: 530px;
        height: 420px;
        border-bottom: 2px solid #4035b2;
        -webkit-border-radius: 0 40% 50% 50%;
        -webkit-background-clip: padding-box;
        -moz-border-radius: 0 40% 50% 50%;
        -moz-background-clip: padding;
        border-radius: 0 40% 50% 50%;
        background-clip: padding-box;
        box-shadow: inset 1px 1px 44px #4035b2;
        background: #2d21a6;
        /* Old browsers */
        background: -moz-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 100%, #2d21a6 100%);
        /* FF3.6+ */
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(45, 33, 166, 0)), color-stop(100%, #2d21a6));
        /* Chrome,Safari4+ */
        background: -webkit-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 100%, #2d21a6 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 44%, #2d21a6 100%);
        /* Opera 12+ */
        background: -ms-radial-gradient(center, ellipse cover, rgba(45, 33, 166, 0) 44%, #2d21a6 100%);
        /* IE10+ */
        background: radial-gradient(ellipse at center, rgba(45, 33, 166, 0) 44%, #2d21a6 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="rgba(45, 33, 166, 0)", endColorstr="#2d21a6", GradientType=1);
        /* IE6-9 fallback on horizontal gradient */
    }

    .StripeElement {
        box-sizing: border-box;

        height: 40px;

        padding: 10px 12px;

        border: 1px solid transparent;
        border-radius: 4px;
        background-color: white;

        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
        border: 1px solid #4035b2;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
    .app_footer {
        text-align: center!important;
        margin-top: 45px;
        font-family: "Lato", helvetica, Arial, Sans-serif;
    }

    .effect8
    {
        font-family: "Lato", helvetica, Arial, Sans-serif;
        /*border: 1px solid #E0E1E2;*/
        border-radius: 5px;
        padding: 15px 15px 10px 15px;
        background: white;
        position: relative;
        padding: 5px !important;
        box-shadow: 0px 3px 4px 0px rgba(0, 0, 0, 0.06) !important;
    }
    .info-block{
        width: 150px !important;
    }
    .mr-1{
        margin: 3px !important;
        padding: 5px !important;
    }
    .margin-bottom-1{
        margin-bottom: 4px;
    }
    .float-right{
        float: right;
    }
    .user-panel>.image>img {
        width: 100%;
        max-width: 45px !important;
        height: 45px !important;
    }
    label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: 400 !important;
    }
    .color-green{
        color: #00a65a !important;
    }
    .form-control.has-error, .form-control.has-error {
        border-color: #dd4b39;
        box-shadow: none;
    }
    .help-block
    {
        color: #dd4b39;
    }
    .multiselect__tags
    {
        border-radius: 0 !important;
    }

    .vdatetime-input{
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        /* font-size: 14px; */
        /* line-height: 1.42857143; */
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 0px;

    }
    .skin-green-light .main-header .navbar {
        background-color: #1034A6 !important;
    }
    .skin-green-light .main-header .navbar {
        background-color: white;
        color: #1034A6;
    }
    .skin-green-light .main-header .logo {
        background-color: #FFFFFF;
        color: #fff;
        border-bottom: 0 solid transparent;
    }
    .skin-green-light .main-header li.user-header {
        background-color: #1034A6;
    }
    .help-block{
        color: red !important;
    }
    .logo:hover{
        background: white !important;
        color: #1034A6 !important;
    }
    .sidebar-toggle:hover{
        background: white !important;
        color: #1034A6 !important;
    }
    .vdatetime-popup__header {
        background: #00a65a !important;
    }

    .vdatetime-year-picker__item--selected,
    .vdatetime-time-picker__item--selected,
    .vdatetime-popup__actions__button {
        color: #00a65a !important;
    }
    .profile-user-img
    {
        width:100px;
        height: 100px;
    }
    .navbar-nav>.notifications-menu>.dropdown-menu>li .menu>li>a, .navbar-nav>.messages-menu>.dropdown-menu>li .menu>li>a, .navbar-nav>.tasks-menu>.dropdown-menu>li .menu>li>a {
        display: block !important;
        white-space: normal !important;
        border-bottom: 1px solid #f4f4f4;
    }
    @yield('style')
</style>

<body class="hold-transition skin-green-light sidebar-mini">
<div id="app">
    <header-component ></header-component>
</div>
<script src="{{asset('public/js/app.js')}}"></script>
<script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/237f4cac36.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://unpkg.com/popper.js"></script>
<script src="https://unpkg.com/v-tooltip@2.0.2"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{URL::asset('public/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- Bootstrap 3.3.7 -->
<script src="{{URL::asset('public/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>

<!-- Morris.js charts -->

<!-- Sparkline -->
<script src="{{URL::asset('public/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<!-- jQuery Knob Chart -->
<script src="{{URL::asset('public/bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{URL::asset('public/bower_components/moment/min/moment.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{URL::asset('public/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{URL::asset('public/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{URL::asset('public/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{URL::asset('public/dist/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{URL::asset('public/dist/js/demo.js')}}"></script>
<script src="{{URL::asset('public/plugins/iCheck/icheck.min.js')}}"></script>
<script src="https://www.gstatic.com/firebasejs/6.3.3/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.3.3/firebase-messaging.js"></script>
<script>
    var user_id = {{Auth::user()->id}};
</script>
<script src="{{URL::asset('public/dist/js/notifications.js')}}"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        //$('.select2').select2();
        $.widget.bridge('uibutton', $.ui.button);
    });
    //Red color scheme for iCheck
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass   : 'iradio_flat-green'
    });
</script>


<script>
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    //this is PAIRED KEY GENERATED IN CLoud MESSAGING TAB IN WEB PUSH CERTIFICATES
    messaging.usePublicVapidKey('BEkEEmKzZeLJBLlcJyKmsnU9JBSuuhMVhAQKiU10jWTWPjcS2zzG2x5p8QdNh70OYdzrCw-T52p9u6pfTO7qvHU');
    //this is for checking eprmissions of the site
    Notification.requestPermission().then((permission) => {
        if (permission === 'granted') {
            console.log('Notification permission granted.');
            // TODO(developer): Retrieve an Instance ID token for use with FCM.
            // ...
//            if(isTokenSentToServer())
//            {
//                console.log("Token Already Saved");
//            }
//            else
//            {
                getRegToken();
            //}
            //getRegToken();

        } else {
            console.log('Unable to get permission to notify.');
        }
    });
    //this is for generating tokens
    function getRegToken() {
        messaging.getToken().then((currentToken) => {
            if (currentToken) {
                saveTokenToServer(currentToken);
                //  updateUIForPushEnabled(currentToken);

                console.log(currentToken);
                setTokenSentToServer(true);
            } else {
                // Show permission request.
                console.log('No Instance ID token available. Request permission to generate one.');
                // Show permission UI.
                setTokenSentToServer(false);
            }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);
            // showToken('Error retrieving Instance ID token. ', err);
            setTokenSentToServer(false);
        });
    }
    //this is for sending token to Google Server of Firebase that is used to send notifications
    function setTokenSentToServer(sent) {
        window.localStorage.setItem('sentToServer', sent ? '1' : '0');
    }
    //this is for checking is token already sent
    function isTokenSentToServer() {
        return window.localStorage.getItem('sentToServer') === '1';
    }
    function saveTokenToServer(currentToken) {
        $.ajax({
            url: "{{route('saveToken')}}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            cache: false,
            method:'POST',
            data:{data:true,token:currentToken,type:'web'},
            success: function(html){
                console.log('Token Saved inDB');
            }
        });
    }
    //this is for recieveing messages or notification from FCM
    messaging.onMessage(function(payload){
        console.log("Message", payload);
        var type=$.parseJSON(payload.data.type);
        console.log(type[0]);
        //Couting Notifications
        var barcount=parseInt($('.notifications-bar-count').text());
        var notif_count=parseInt($('.notifications-count').text());

        $('.notifications-bar-count').text("You have "+barcount+1+" notifications");
        $('.notifications-count').text(notif_count+1);

        ///Posting request for donation
        if(type[1] == "donationPosted" ){
            $('.notification').append('<li>\n' +
                '                                            <a href="#">\n' +
                '                                                <i class="'+type[0]+'" style="margin-right: 5px"></i>'+   payload.data.body+'\n' +
                '                                            </a>\n' +
                '                                        </li>');
        }
        //Donated someone Notification
                @if(Auth::user()->roles()->first()->name == "partner")
        var user_id="{{\Illuminate\Support\Facades\Auth::user()->roles()->first()->name == "partner" ? \Illuminate\Support\Facades\Auth::user()->id : \Illuminate\Support\Facades\DB::table('partner_to_staff')->where('staff_id','=',\Illuminate\Support\Facades\Auth::user()->id)->first()->partner_id}}";
        if(type[1] == user_id ){
            $('.notification').append('<li>\n' +
                '                                            <a href="#">\n' +
                '                                                <i class="'+type[0]+'" style="margin-right: 5px"></i>'+   payload.data.body+'\n' +
                '                                            </a>\n' +
                '                                        </li>');
        }
                @else
        var user_id="{{\Illuminate\Support\Facades\Auth::user()->id}}";
        if(type[1] == user_id ){
            $('.notification').append('<li>\n' +
                '                                            <a href="#" class="bg-red">\n' +
                '                                                <i class="'+type[0]+'" style="margin-right: 5px"></i>'+   payload.data.body+'\n' +
                '                                            </a>\n' +
                '                                        </li>');
        }
        @endif

            notificationTitle=payload.data.title;
        notificationOptions={
            body : payload.data.body,
            icon : payload.data.icon,
            click_action: payload.data.click_action, // To handle notification click when notification is moved to notification tray
            data: {
                click_action: payload.data.click_action
            }
        };
        var notification=new Notification(notificationTitle,notificationOptions);
    });
</script>

</body>
</html>
