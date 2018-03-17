@extends('layouts.repo')

@section('content')
<?php
/**
 * @var $oDogovor \App\Models\Dogovor
 * @var $oMainService \App\Models\DogovorService
 * @var $oMainHotel \App\Models\Hotel
 *
 */
?>
    <div class="container">

                <div class="panel-body">
                    <!-- Display Errors -->
                    @include('common.errors')

                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <h3 style="text-align: center">INVOICE</h3>
                                <table width="100%">
                                    <tr valign="top">
                                        <td align="left">
                                            Support: <b> (+960)3329141 </b>
                                        </td>
                                        <td align="right">
                                            {{ (new DateTime())->format($oDogovor::DATE_FORMAT) }}
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                <img src="/img/ftlogo.gif">
                                <table width="100%">
                                    <tr valign="top">
                                        <td align="left"></td>
                                        <td align="right"></td>
                                    </tr>
                                </table>
                                <hr>
                                <table width="100%">
                                    <tr valign="top">
                                        <td align="left"> <b>Booking confirmation date:</b>
                                            @if( $oDogovor->DG_ConfirmedDate )
                                                {{$oDogovor->DG_ConfirmedDate->format($oDogovor::DATE_FORMAT)}}
                                            @endif
                                        </td>
                                        <td align="right">Booking ref. <b>{{$oDogovor->DG_CODE}}</b><br>
                                        </td>
                                    </tr>
                                </table>
                                <hr>

                                <table border=0 width="100%">
                                    <tr valign="top">
                                        <td align="left"><b>Accommodation:</b>
                                            {{ $oMainHotel->country->CN_NAMELAT }},{{ $oMainHotel->city->CT_NAMELAT }} {{ $oMainHotel->HD_NAME }} ({{ $oMainHotel->stars->COH_NameLat }})<br>
                                            Arrival: {{ $oDogovor->DG_TURDATE->format($oDogovor::DATE_FORMAT) }} Departure: {{ $oDogovor->DG_TURDATE->addDay($oDogovor->DG_NDAY-1)->format($oDogovor::DATE_FORMAT) }}
                                        </td>
                                        <td align="right">
                                            {{ $sRoom }} / {{ $sRoomCategory }}, MEAL PLAN - {{$sPansionCode}} <br>
                                            1 x <img src="/img/{{$nPic}}.gif">
                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" cellspacing="0" cellpadding="3" style="border: 1px solid; border-color: #0868B0; margin-top: 5px">
                                    <tr bgcolor="#EEEEFF">
                                        <td><b>Room type</b></td>
                                        <td><b>From</b></td>
                                        <td><b>Till</b></td>
                                        <td align="center"><b>Pax</b></td>
                                        <td align="center"><b>Adult</b></td>
                                        <td align="center"><b>Children</b></td>
                                        <td align="center"><b>Meal Plan</b></td>
                                        <td align="center"><b>Price</b></td>
                                    </tr>
                                    <tr>
                                        <td>{{$sRoom}} / {{$sRoomCategory}} </td>
                                        <td>{{ $oMainService->DL_DATEBEG->format($oDogovor::DATE_FORMAT) }}</td>
                                        <td>{{ $oMainService->DL_DATEEND->addDay(1)->format($oDogovor::DATE_FORMAT) }}</td>
                                        <td align="center">{{$nAdult + $nChild}}</td>
                                        <td align="center">{{ $nAdult  }}</td>
                                        <td align="center">{{ $nChild }}</td>
                                        <td align="center">{{ $sPansionCode }}</td>
                                        <td align="center">{{ $nHotelPrice }} USD</td>
                                    </tr>
                                </table>

                                <br>
                                @if( !empty($sTransfer) )
                                    <table width="70%" style="border: 1px solid; border-color: #0868B0;" cellpadding="3" cellspacing="0">
                                        <tr bgcolor="#EEEEFF">
                                            <td><b>Return Transfer</b></td>
                                            <td><b>Transfer Type</b></td>
                                            <td><b>Price</b></td>
                                        </tr>

                                        <tr>
                                            <td>{{ $sTransfer }}</td>
                                            <td>{{ $sTransport }}</td>
                                            <td>{{$nTransferPrice}} USD</td>
                                        </tr>
                                    </table>
                                @endif
                                <br>
                                <?php
                                /** @var \App\Models\DogovorTourist $oTourist */
                                ?>
                                <table width="75%" style="border: 1px solid; border-color: #0868B0;" cellspacing="0" cellpadding="0" border="0">
                                    <tr bgcolor="#EEEEFF" >
                                        <td width="40%"><b>LAST NAME</b></td>
                                        <td><b>FIRST NAME </b></td>
                                        <td><b>Passport No:</b></td>
                                        <td><b>DOB</b></td>
                                    </tr>
                                    @foreach($oDogovor->tourists as $oTourist)
                                    <tr>
                                        <td>{{$oTourist->TU_FNAMELAT}}
                                        <td>{{$oTourist->TU_NAMELAT}}</td>
                                        <td>{{$oTourist->TU_PASPORTNUM}}</td>
                                        <td>{{$oTourist->TU_BIRTHDAY->format($oTourist::DATE_FORMAT)}}</td>
                                    </tr>
                                    @endforeach
                                </table>

                                <table width="100%">
                                  <tr align="right">
                                    <td><b>Total: {{(int)$oDogovor->DG_PRICE}} USD</b></td>
                                  </tr>
                                </table>
                                <br>

                                <b>Cancelation fee</b>
                                <table width="250" cellspacing="0" cellpadding="3" style="border: 1px solid; border-color: #0868B0;">
                                        <tr>
                                            <td><b>Less 19 prior to arrival</b></td>
                                            <td align="right"><b>{{(int)($oDogovor->DG_PRICE * 0.3)}}</b> USD</td>
                                        </tr>
                                        <tr>
                                            <td><b>Less 10 prior to arrival</b></td>
                                            <td align="right"><b>{{(int)($oDogovor->DG_PRICE * 0.9)}}</b> USD</td>
                                        </tr>
                                    </TMPL_LOOP>
                                </table>

                             <hr>

                                Fourthtour Establishment (beneficiary)<br>
                                State Bank of India<br>
                                Male, Republic of Maldives<br>
                                Swift:SBINMVMV<br>
                                Through: J P MORGAN Chase -New York<br>
                                Swift: CHASUS33<br>
                                Account  09030 034355<br>
                                <br>

                            </td>
                        </tr>
                    </table>


                </div>
            </div>
@endsection

