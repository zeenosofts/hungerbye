@extends('layouts.app')
@section('style')
    html{
    }
    body{
    margin: 0;
    padding: 0;
    background: #e7ecf0;
    font-family: Arial, Helvetica, sans-serif;
    }
    *{
    margin: 0;
    padding: 0;
    }
    p{
    font-size: 12px;
    color: #373737;
    font-family: Arial, Helvetica, sans-serif;
    line-height: 18px;
    }
    p a{
    color: #218bdc;
    font-size: 12px;
    text-decoration: none;
    }
    a{
    outline: none;
    }
    .f-left{
    float:left;
    }
    .f-right{
    float:right;
    }
    .clear{
    clear: both;
    overflow: hidden;
    }
    #block_error{
    width: 845px;
    height: 384px;
    border: 1px solid #cccccc;
    margin: 72px auto 0;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    background: #fff url(http://www.ebpaidrev.com/systemerror/block.gif) no-repeat 0 51px;
    }
    #block_error div{
    padding: 100px 40px 0 186px;
    }
    #block_error div h2{
    color: #218bdc;
    font-size: 24px;
    display: block;
    padding: 0 0 14px 0;
    border-bottom: 1px solid #cccccc;
    margin-bottom: 12px;
    font-weight: normal;
    }
    @endsection
@section('content')
    <div id="block_error">
        <div>
            <h2>“Thank you for registering as a Partner.“</h2>
            <p><b>What can I do while I wait?</b></p>

            <p><b>Set up Stripe -</b> In order to transfer the donation we received from our Donors to our Partners securely we use Stripe. Please sign up and set up your account with Stripe so that once your HungerBye account is approved you can connect it to start receiving payments. <a href="https://stripe.com/">Go to Stripe</a></p>
            <p><b>See what's new -</b> If you have a Stripe then checkout our homepage to see what others are upto. <a href="https://hungerbye.com/">Go to HungerBye</a></p>
        </div>
    </div>
@endsection
