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
            <form action="{{route('update_complete_profile')}}" method="post">
                @csrf
            <p class="login-box-msg">Please complete your profile first</p>
            <div class="form-group">
                <label class="control-label"> Business Name</label>
                <input type="text" class="form-control" name="business_name" placeholder="Business Name" required>
                @error('business_name')
                <span class="invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
                <div class="form-group">
                    <label class="control-label"> Contact Number</label>
                    <input type="number" class="form-control" name="number" placeholder="Contact Number" required>
                    @error('number')
                    <span class="invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>
            <div class="form-group">
                <label class="control-label"> Business Address</label>
                <input type="text" class="form-control" id="business_address" name="business_address" placeholder="Business Address" required>
                @error('business_address')
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
@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={{env('GOOGLE_API_KEY')}}"></script>
    <script>
        $(document).ready(function () {
            var autocomplete;
            autocomplete = new google.maps.places.Autocomplete((document.getElementById('business_address')), {
                types: ['geocode'],
            });
//
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var near_place = autocomplete.getPlace();
                console.log(near_place);
                console.log($('#pickup_address_new').val())
            });
        });
    </script>
@endsection
