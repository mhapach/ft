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
                                <h3 style="text-align: center">VOUCHER</h3>
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
                                        <td align="left"> <b>Accomodation voucher</b><!--b>Booking confirmation date: <TMPL_VAR NAME=CONFIRMATION_DATE></b--></td>
                                        <td align="right">Booking ref. <b>{{$oDogovor->DG_CODE}}</b><br>
                                        </td>
                                    </tr>
                                </table>
                                <hr>

                                <table border=0>
                                    <tr valign="top">
                                        <td>
                                            <b>User contact:</b>
                                        </td>
                                        <td>
                                           {{$oDogovor->DG_MAINMEN}}
                                        </td>
                                        <td width="20"></td>
                                        <td align="right"><b>Accommodation:</b></td>
                                        <td>
                                            {{ $oMainHotel->HD_NAME }} ({{ $oMainHotel->stars->COH_NameLat }}),
                                            {{ trim($oMainHotel->HD_ADDRESS) }}
                                            <b>phone:</b> {{ trim($oMainHotel->HD_PHONE) }}
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                <table border=0>
                                    <tr valign="top">
                                        <td align="left"><b>From:</b></td>
                                        <td align="left">{{ $oDogovor->DG_TURDATE->format($oDogovor::DATE_FORMAT) }}</td>
                                        <td width="20"></td>
                                        <td align="left"><b>To:</b></td>
                                        <td align="left">{{ $oDogovor->DG_TURDATE->addDay($oDogovor->DG_NDAY-1)->format($oDogovor::DATE_FORMAT) }}</td>
                                        <td width="20"></td>
                                        <td align="left"><b>Nights:</b></td>
                                        <td align="left">{{$oDogovor->DG_NDAY-1}}</td>
                                    </tr>
                                </table>
                                <table border=0>
                                    <tr valign="top">
                                        <td align="left"><b>Total pax:</b></td>
                                        <td align="left">{{$oDogovor->DG_NMEN}}</td>
                                        <td width="20"></td>
                                        <td align="left"><b>Distination:</b></td>
                                        <td align="left">{{$oDogovor->city->CT_NAMELAT}}</td> <!--<TMPL_VAR NAME=HOTEL_COUNTRY>-->
                                    </tr>
                                </table>

                                <table width="100%" cellspacing="0" cellpadding="3" style="border: 1px solid; border-color: #0868B0;">
                                    <tr bgcolor="#EEEEFF">
                                        <td><b>Room type</b></td>
                                        <td><b>Guest name / Birthday</b></td>
                                        <td align="center"><b>Pax</b></td>
                                        <td align="center"><b>Adult</b></td>
                                        <td align="center"><b>Children</b></td>
                                        <td align="center"><b>Meal Plan</b></td>
                                    </tr>
                                    <?php
                                    /** @var \App\Models\DogovorService $oService */
                                    $i=0;
                                    ?>
                                    <tr>
                                        <td>{{$sRoom}} / {{$sRoomCategory}} </td>
                                        <td>
                                            <?php
                                                /** @var \App\Models\DogovorTourist $oTourist */
                                            ?>
                                            @foreach($oDogovor->tourists as $oTourist)
                                                {{$oTourist->TU_NAMELAT}}, {{$oTourist->TU_FNAMELAT}} / {{$oTourist->TU_BIRTHDAY->format($oTourist::DATE_FORMAT)}}<br>
                                            @endforeach
                                        </td>
                                        <td align="center"><img src="/img/{{$nPic}}.gif"></td>
                                        <td align="center">{{ $nAdult  }}</td>
                                        <td align="center">{{ $nChild }}</td>
                                        <td align="center">{{ $sPansionCode }}</td>
                                    </tr>
                                </table>

                                <br>
                                @if( !empty($sTransfer) )
                                    <table width="70%" style="border: 1px solid; border-color: #0868B0;" cellpadding="3" cellspacing="0">
                                        <tr bgcolor="#EEEEFF">
                                            <td><b>Return Transfer</b></td>
                                            <td><b>Transfer Type</b></td>
                                            <!--td><b>Price</b></td-->
                                        </tr>

                                        <tr>
                                            <td>{{ $sTransfer }}</td>
                                            <td>{{ $sTransport }}</td>
                                            <!--td><TMPL_VAR NAME=transfer_price> <TMPL_VAR NAME=currency_code></td-->
                                        </tr>
                                    </table>
                                @endif

                                @if(!empty($oDogovor->DG_NOTES))
                                    <hr>
                                    <b>Remarks</b><br>
                                    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid; border-color: #0868B0;font-family: Arial; font-size: 8pt; font-weight: normal;">
                                        <tr bgcolor="#EEEEFF">
                                            <td>
                                                {{$oDogovor->DG_NOTES}}
                                            </td>
                                        </tr>
                                    </table>
                                @endif


                                <!--table width="100%">
                                  <tr align="right">
                                    <td><h3><b>Total: <TMPL_VAR NAME=TOTAL_PRICE> <TMPL_VAR NAME=CURRENCY_CODE></b></h3></td>
                                  </tr>
                                </table>
                            <br>
                                <table width="100%" cellspacing="0" cellpadding="3" style="border: 1px solid; border-color: #0868B0;">
                                     <tr bgcolor="#EEEEFF">
                                        <td><b>Cancelation fee</b></td>
                                        <td></td>
                                        <td><b>Pax</b></td>
                                        <td><b>Period</b></td>
                                        <td align="right"><b>Price</b></td>
                                     </tr>
                                </table>
                             <hr>
                               <TMPL_VAR NAME=OUR_COMPANY_BANK_ACCOUNT><br-->

                            </td>
                        </tr>
                    </table>


                </div>
            </div>
@endsection

