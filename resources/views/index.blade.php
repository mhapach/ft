@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Online Booking for Hotels and Apartments. Forthtour Establishment
                </div>

                <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    <!-- Search engine -->
                    @include('search.form')
                </div>
            </div>
        </div>
    </div>
@endsection

