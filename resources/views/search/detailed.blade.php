            <!-- Search Form -->
            <?php /** @var $oHotelPrice \App\Models\api\services\prices\HotelPrice */ ?>

            <table id="resTable" class="table table-hover">
                <thead>
                    <th>Room</th>
                    <th>Room category</th>
                    <th>Meal plan</th>
                    <th>Price</th>
                </thead>
                <tbody>
                <?php
                    /** @var \App\Models\Api\Services\prices\HotelPrice $oHotelPrice */
                ?>
                @foreach ($aSearchResults as $oHotelPrice)
                    <tr>
                        <td nowrap> {{$oHotelPrice->room->RM_NAME}} </td>
                        <td nowrap title="{{$oHotelPrice->nCode}}"> {{$oHotelPrice->room_category->RC_NAME}} </td>
                        <td nowrap> {{$oHotelPrice->pansion->PN_NAME}} </td>
                        <td nowrap>
                            <a href="/booking/{{$oHotelPrice->nCode}}/{{$oHotelPrice->nCode1}}/{{$oHotelPrice->nCode2}}/{{(int)$oHotelPrice->nExtraBedCode1}}/{{{ Request::Get('dates_period') }}}/{{Request::Get('room_id')}}">
                                {{(int)$oHotelPrice->nPrice}}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

