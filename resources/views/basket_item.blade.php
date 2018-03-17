<?php
/** @var \App\Models\BasketItem $oBasketItem */
?>

@extends('layouts.app')

@section('content')
    <!-- Display Validation Errors -->
    @include('common.errors')

    @if( !empty($oMainService) )
        <div class="container">
            <?php
                /** @var \App\Models\Service $oMainService */
            ?>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Country:</strong> {{$oMainService->country->CN_NAMELAT}}</p>
                    <p><strong>City:</strong> {{$oMainService->city->CT_NAMELAT}}</p>
                </div>
                <div class="col-md-6 text-right">
                    <p>
                        {{ $oMainService->subcode1Object->roomCategory->RC_NAMELAT }}
                    </p>
                    <p>
                        <strong>From</strong> @if($oMainService->date_begin) {{ $oMainService->date_begin->format($oMainService::DATE_FORMAT) }} @endif
                        <strong>till</strong> @if($oMainService->date_end) {{ $oMainService->date_end->format($oMainService::DATE_FORMAT) }} @endif
                    </p>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Main services
                </div>

                <table class="table table-hover">
                    <thead>
                        <th>Name</th>
                        <th>Price</th>
                        <th>pax</th>
                    </thead>
                    <tbody>
                    <?php
                    /** @var \App\Models\BasketItemService $oService */
                    ?>
                    @foreach( $requiredServices as $oService )
                        <tr>
                            <td> {{$oService->name}} </td>
                            <td class="price"> {{(int)$oService->brutto}} </td>
                            <td> {{(int)$oService->nmen}} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <form method="post" id="basketItemForm">
                @if( !empty($optionalServices) )
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Optional services
                        </div>

                        <table class="table table-hover">
                            <thead>
                            <th>Name</th>
                            <th>Price</th>
                            <th>pax</th>
                            <th></th>
                            </thead>
                            <tbody>
                            <?php
                            /** @var \App\Models\BasketItemService $oBIService   */
                            ?>
                            @foreach( $optionalServices as $oBIService )
                                <tr>
                                    <td> {{$oBIService->name}} </td>
                                    <td class="price"> {{(int)$oBIService->brutto}} </td>
                                    <td> {{(int)$oBIService->nmen}} </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" class="inc_optional" value="{{$oBIService->id}}" @if(empty($oBIService->is_disabled)) checked @endif> Include
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if( !empty($aPenalties) )
                    <div class="row">
                        <div class="col-md-6">
                            <label>
                                Cancelation fee:
                            </label>
                            <span id="penalties">

                                @foreach ( $aPenalties as $nReleaseDays => $nAbsPenaltyValue )
                                    <div class="row">
                                        <div class="col-md-4">
                                            Less {{$nReleaseDays}} prior to arrival
                                        </div>
                                        <div class="col-md-2">
                                            {{$nAbsPenaltyValue}} $
                                        </div>
                                    </div>
                                @endforeach

                            </span>
                        </div>
                        <div class="col-md-6 text-right">
                            <label>
                                Total price:
                            </label>
                            <span id="total_price"></span> $
                        </div>
                    </div>
                @endif


                <div class="panel panel-default" style="margin-top: 10px">
                    <div class="panel-heading">
                        Tourists (input only latin letters)
                    </div>

                    <table class="table table-hover">
                        <thead>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Passport Num</th>
                        <th>Dob (dd.mm.yyyy)</th>
                        </thead>
                        <tbody>
                        <?php
                        /** @var \App\Models\BasketItemTourist $oTourist */
                        $i=0;
                        ?>
                        @foreach( $oBasketItem->basketItemTourists as $oTourist )
                            <tr>
                                <td><input type="text"  required class="form-control tourist" name="first_name[]" value="{{ old('first_name.'.($i)) }}"> </td>
                                <td><input type="text"   required class="form-control tourist" name="last_name[]" value="{{ old('last_name.'.($i)) }}"></td>
                                <td><input type="text" required class="form-control" name="paspnum[]" value="{{ old('paspnum.'.($i)) }}"></td>
                                <td>
                                    <input type="text" required class="form-control datepicker" name="birthdate[]" value="{{ old('birthdate.'.($i++)) }}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
{{--

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Contacts
                    </div>
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <td><input type="text" required class="form-control" name="contact_name" placeholder="Enter your name" value="{{ old('contact_name') }}"> </td>
                            <td><input type="email" required class="form-control" name="email" placeholder="Enter your email" value="{{ old('email') }}"> </td>
                            <td><input type="text" class="form-control" name="phone" placeholder="Enter your phone" value="{{ old('phone') }}"> </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
--}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Remark
                    </div>

                    <textarea class="form-control" name="remark" rows="5">{{ old('remark') }}</textarea>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input class="btn btn-default" type="submit" value="Submit" id="ft_submit">
                    </div>
                </div>

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>

        </div>

        <script>
            getTotalSum = function(){
                var sum = 0;
                $('.price').each(function() {
                    var oOptionalChecked = $(this).siblings(":last").find('input.inc_optional');
                    if( !oOptionalChecked.length  || (oOptionalChecked.attr('type') == 'checkbox' && oOptionalChecked.is(':checked')) ){
//                        console.log(oOptionalChecked.val());
                        sum += Number($(this).html());
                    }
                });
                return sum;
            };

            $('.inc_optional').on('click', function(e){
                console.log($(this).attr('checked'));
                $.ajax({
                    method:'get',
                    url: '/basket_item/' + $(this).val() + '/' + ($(this).is(':checked') ? 0 : 1)
                }).
                fail(function(msg, txt){
                    console.log(msg, txt);
                });

                $('#total_price').html(
                    getTotalSum()
                );

            });
/*
            $('#basketItemForm').on('submit', function(){
                var oData = $('#basketItemForm').serialize();
                $.ajax({
                    'url': window.location.href,
                    'method' : 'POST',
                    'data': oData
                }).done(function(msg){
                    if(msg.error){
                        new PNotify({
                            title: 'Smth went wrong!',
                            text: msg.error,
                            type: 'error'
                        });
                    }
                    else{
                        new PNotify({
                            title: 'Fine your you order created!',
                            text: 'Now we redirect you on Order information page.',
                            type: 'success'
                        });
                    }
                }).fail(function(oError,textMsg, errorThrown ){
                    new PNotify({
                        title: 'Unknown error',
                        text: 'Possibly your session has expired',
                        type: 'error'
                    });
                });

                return false;
            });
*/

//            $('#basketItemForm').validator().on('submit', function (e) {
//                if (e.isDefaultPrevented()) {
//                    // handle the invalid form...
//                    alert(' not good');
//                } else {
//                    alert('  good');
//                    // everything looks good!
//                }
//            })

            $('document').ready(function(){
                $('input.datepicker').daterangepicker({
                        locale: {
                            format: 'DD.MM.YYYY'
                        },
                        minDate: moment().subtract(100, 'years'),
                        //startDate: moment().subtract(100, 'years'),
//                        endDate: null,
                        singleDatePicker: true,
                        showDropdowns: true
                });
                $('#total_price').html(getTotalSum())

                $('.tourist').bind('keyup', function(){
                    $(this).val($(this).val().replace(/[^a-z ]/i, ""))
                });
            });
        </script>
    @endif
@endsection

