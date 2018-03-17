@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Display Validation Errors -->
    @include('common.errors')

    <div class="panel panel-default">
        <div class="panel-heading">
            Orders
        </div>
            <table class="table table-hover">
                <thead>
                <th>Name</th>
                <th>pax</th>
                <th>Price</th>
                <th>Payed</th>
                <th>Arrival date</th>
                <th>Duration</th>
                <th>Status</th>
                </thead>
                <tbody>
                <?php
                /** @var \App\Models\Dogovor $oDogovor*/
                ?>
                @foreach( $aDogovors as $oDogovor )
                    <tr>
                        <td><a href="/order_info/{{$oDogovor->DG_Key}}"> {{$oDogovor->DG_CODE}}</a> </td>
                        <td> {{(int)$oDogovor->DG_NMEN}} </td>
                        <td class="price"> {{(int)$oDogovor->DG_PRICE}} {{$oDogovor->DG_RATE}}  </td>
                        <td> {{(int)$oDogovor->DG_PAYED}} </td>
                        <td> {{$oDogovor->DG_TURDATE->format($oDogovor::DATE_FORMAT)}} </td>
                        <td> {{(int)$oDogovor->DG_NDAY}} </td>
                        <td> {{$oDogovor->status->OS_NameLat}} </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </div>
</div>
@endsection
