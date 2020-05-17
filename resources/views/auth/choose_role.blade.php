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
            <form action="{{route('update_role_profile')}}" method="post">
                @csrf
            <p class="login-box-msg">Please complete your profile first</p>
                <div class="form-group" align="center">
                    <label class="control-label"> Register as</label>
                    <br>
                    <label for="default" class="btn btn-success btn-flat">Donor <input value="3" required name="role_id" type="radio" id="default" class="badgebox"><span class="badge">&check;</span></label>
                    <label for="primary" class="btn btn-primary btn-flat">Partner <input value="4" required name="role_id" type="radio" id="primary" class="badgebox"><span class="badge">&check;</span></label>
                    @error('role_id')
                    <span class="invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>

            <div class="mt-5">
              <button class="btn btn-flat btn-block btn-warning">Continue</button>
            </div>
            </form>
        </div>
    </div>


@endsection
