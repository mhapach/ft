@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="col-sm-offset-0 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse">
                    <a data-toggle="collapse" href="#collapseFilter">
                        Search filter
                    </a>
                </div>

                <div id="collapseFilter" class="panel-body panel-collapse collapse in">
                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    <!-- Search engine -->
                    @include('search.form')
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Search results
                </div>

                <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    <!-- Search engine -->
                    @include('search.result')

                    <div style="text-align: center">
                        <ul class="pagination ">
                            @for ($i = 1; $i <= request('page',1); $i++)
                                <li><a href="#" class="page_button" num="{{$i}}">{{$i}}</a></li>
                            @endfor
                            <li><a href="#" class="page_button" num="{{request('page',1)+1}}">Next > </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .panel-heading a:after {
            font-family:'Glyphicons Halflings';
            content:"\e114";
            float: right;
            color: grey;
        }
        .panel-heading a.collapsed:after {
            content:"\e080";
        }
    </style>
@endsection

