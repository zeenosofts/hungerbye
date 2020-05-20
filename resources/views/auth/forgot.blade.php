@extends('layouts.app')
@section('style')
    /* Hiding the checkbox, but allowing it to be focused */
    .badgebox
    {
    opacity: 0;
    }

    .badgebox + .badge
    {
    /* Move the check mark away when unchecked */
    text-indent: -999999px;
    /* Makes the badge's width stay the same checked and unchecked */
    width: 27px;
    }

    .badgebox:focus + .badge
    {
    /* Set something to make the badge looks focused */
    /* This really depends on the application, in my case it was: */

    /* Adding a light border */
    box-shadow: inset 0px 0px 5px;
    /* Taking the difference out of the padding */
    }

    .badgebox:checked + .badge
    {
    /* Move the check mark back when checked */
    text-indent: 0;
    }
    @endsection
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <img src="{{URL::asset('public/images/hungerByeMain.png')}}" width="250">
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            @if (session('error'))
                <div  class="callout callout-{{session('error')[0]}}">
                    <h5>{{session('error')[1]}}</h5>
                </div>
            @endif
            <form action="{{route('send_verification_code')}}" method="post">
                @csrf
            <p class="login-box-msg">Please enter you email address</p>
                <div class="form-group" align="center">
                    <label class="control-label"> Your email</label>
                    <br>
                    <input type="email" required name="email" class="form-control">
                </div>

            <div class="mt-5">
              <button class="btn btn-flat btn-block btn-warning">Send</button>
            </div>
            </form>
        </div>
    </div>


@endsection
