@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lname') ? ' has-error' : '' }}">
                            <label for="lname" class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                                <input id="lname" type="text" class="form-control" name="lname" value="{{ old('lname') }}" required autofocus>

                                @if ($errors->has('lname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('birth_date') ? ' has-error' : '' }}">
                            <label for="birth_date" class="col-md-4 control-label">Dob</label>

                            <div class="col-md-6">
                                <input id="birth_date" type="text" class="form-control datepicker" name="birth_date" required value="{{ old('birth_date') }}">

                                @if ($errors->has('birth_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('birth_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">Address (<small>including the country code, city, street, home-building-apartment, to specify that such information may be required, if necessary a refund</small>)</label>

                            <div class="col-md-6">
                                <textarea id="address" class="form-control" name="address" rows="5" required>{{ old('address') }}</textarea>

                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone" required value="{{ old('phone') }}">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="passport" class="col-md-4 control-label">Passport Num (<small>may be required if the customer is a tourist</small>)</label>

                            <div class="col-md-6">
                                <input id="passport" type="text" class="form-control" name="passport" value="{{ old('passport') }}">

                                @if ($errors->has('passport'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('passport') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('passport_issue') ? ' has-error' : '' }}">
                            <label for="passport_issue" class="col-md-4 control-label">Passport issue date</label>

                            <div class="col-md-6">
                                <input id="passport_issue" type="text" class="form-control datepicker" name="passport_issue" value="{{ old('passport_issue') }}">

                                @if ($errors->has('passport_issue'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('passport_issue') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('is_mailing_agree') ? ' has-error' : '' }}">
                            <label for="is_mailing_agree" class="col-md-4 control-label">I agree to receive email newsletter </label>

                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input id="is_mailing_agree" type="checkbox"  name="is_mailing_agree" value="1" value="{{ old('is_mailing_agree') }}">
                                    </label>
                                </div>
                                @if ($errors->has('is_mailing_agree'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('is_mailing_agree') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('document').ready(function(){
        $('input.datepicker').daterangepicker({
            locale: {
                format: 'DD.MM.YYYY'
            },
            minDate: moment().subtract(100, 'years'),
            maxDate: moment().add(50, 'years'),
            //startDate: moment().subtract(100, 'years'),
            //endDate: null,
            singleDatePicker: true,
            showDropdowns: true
        });
    });
</script>

@endsection
