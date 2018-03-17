@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Display Validation Errors -->
    @include('common.errors')
    @if( !empty($oDogovor) )
        <?php
        /** @var \App\Models\Dogovor $oDogovor */
        ?>
        <div class="row text-center">
            <strong>Order:</strong> {{$oDogovor->DG_CODE}}
        </div>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Country:</strong> {{$oDogovor->country->CN_NAMELAT}}</p>
                <p><strong>City:</strong> {{$oDogovor->city->CT_NAMELAT}}</p>
                <p><strong>Order status:</strong> {{ $oDogovor->status->OS_NameLat }}</p>
            </div>
            <div class="col-md-6 text-right">
                <p>
                    <strong>From</strong> {{ $oDogovor->DG_TURDATE->format($oDogovor::DATE_FORMAT) }}
                    <strong>to</strong> {{ $oDogovor->DG_TURDATE->addDay($oDogovor->DG_NDAY-1)->format($oDogovor::DATE_FORMAT) }}
                    <strong>days</strong> {{ $oDogovor->DG_NDAY}}
                </p>
                <p>
                    <strong>Creation date </strong> {{ $oDogovor->DG_CRDATE->format($oDogovor::DATE_FORMAT) }}
                </p>
                <p>
                    <strong>Payments</strong> {{ (int)$oDogovor->DG_PAYED }}
                </p>

            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Order info
            </div>

            <table class="table table-hover">
                <thead>
                <th>Name</th>
                <th>pax</th>
                <th>Price</th>
                </thead>
                <tbody>
                <?php
                /** @var \App\Models\DogovorService $oService */
                ?>
                @foreach( $oDogovor->services as $oService )
                    <tr>
                        <td> {{$oService->DL_NameLat}} </td>
                        <td> {{(int)$oService->DL_NMEN}} </td>
                        <td class="price"> {{(int)$oService->DL_BRUTTO}} </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

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
                    <p>
                        Total price: <strong>{{ (int)$oDogovor->DG_PRICE }} {{$oDogovor->DG_RATE}}</strong>
                    </p>
                </div>
            </div>
        @endif

        <div class="panel panel-default" style="margin-top: 10px">
            <div class="panel-heading">
                Tourists
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
                /** @var \App\Models\DogovorTourist $oTourist */
                $i=0;
                ?>
                @foreach( $oDogovor->tourists as $oTourist )
                    <tr>
                        <td>{{$oTourist->TU_NAMELAT}}</td>
                        <td>{{$oTourist->TU_FNAMELAT}}</td>
                        <td>{{$oTourist->TU_PASPORTNUM}}</td>
                        <td>{{$oTourist->TU_BIRTHDAY->format($oDogovor::DATE_FORMAT)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a href="/invoice/{{$oDogovor->DG_Key}}" class="btn btn-warning">
                    <i class="fa fa-btn fa-plus"></i>Proforma-invoice
                </a>
                @if( $oDogovor->DG_PRICE <= $oDogovor->DG_PAYED && $oDogovor->status->OS_CODE == $oDogovor::OK_STATUS )
                    <a href="/voucher/{{$oDogovor->DG_Key}}" class="btn btn-warning">
                        <i class="fa fa-btn fa-plus"></i>Voucher
                    </a>
                @endif
            </div>
        </div>

        <div class="panel panel-default" style="margin-top: 10px">
            <div class="panel-heading">
                Message
            </div>
            <div class="panel-body">
                <form method="post">
                    <div class="form-group">
                        <textarea class="form-control" name="message" rows="5">{{ old('remark') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit message</button>
                    {{csrf_field()}}
                </form>
            </div>
        </div>

        <div class="panel panel-default" style="margin-top: 10px">
            <div class="panel-heading">
                Message history
            </div>
            <div class="panel-body">
                <?php /** @var \App\Models\History $oHistory */?>
                @foreach($aHistory as $oHistory)
                    @if( $oHistory->HI_TEXT )
                        <strong>@if ($oHistory->HI_MOD == 'WWW') You @else Operator @endif</strong> <small>({{$oHistory->HI_DATE->toDateTimeString()}})</small>
                        <article><p>{{$oHistory->HI_TEXT}}</p></article>
                    @endif
                @endforeach
            </div>
        </div>

    @endif
</div>
@endsection
