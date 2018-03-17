            <!-- Search Form -->
            <?php /** @var $oHotelPrice \App\Models\api\services\prices\HotelPrice */ ?>

            <table id="resTable" class="table table-hover">
                <thead>
                    <th>ID</th>
                    <th>Hotel</th>
                    <th>Stars</th>
                    <th>City</th>
                    <th>Room</th>
                    <th>Room category</th>
                    <th>Meal plan</th>
                    <th>Price</th>
                    <th></th>
                </thead>
                <tbody>
                <?php
                    /** @var \App\Models\Api\Services\prices\HotelPrice $oHotelPrice */
                ?>
                @foreach ($aSearchResults as $oHotelPrice)
                    <tr>
                        <td> {{$oHotelPrice->nCode}} </td>
                        <td> {{$oHotelPrice->hotel->HD_NAME}} </td>
                        <td nowrap> {{$oHotelPrice->hotel->stars->COH_Name}} </td>
                        <td nowrap> {{$oHotelPrice->hotel->city->CT_NAME}} </td>
                        <td nowrap> {{$oHotelPrice->room->RM_NAME}} </td>
                        <td nowrap> {{$oHotelPrice->room_category->RC_NAME}} </td>
                        <td nowrap> {{$oHotelPrice->pansion->PN_NAME}} </td>
                        <td nowrap>
                            <a href="/booking/{{$oHotelPrice->nCode}}/{{$oHotelPrice->nCode1}}/{{$oHotelPrice->nCode2}}/{{(int)$oHotelPrice->nExtraBedCode1}}/{{{ Request::Get('dates_period') }}}/{{Request::Get('room_id')}}">
                                {{(int)$oHotelPrice->nPrice}}
                            </a>
                        </td>
                        <td>
                            <a class="btn btn-warning btn-xs morePrices" href="#" role="button" id="hotel_{{$oHotelPrice->nCode}}">
                                More prices
                            </a>
                        </td>
                    </tr>
                    <tr class="addPricesTr">
                        <td colspan="9">
                            <div class="col-md-6 addPricesDiv pull-right" style="display: none;"></div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <style>
                #resTable tr.addPricesTr {
                    display: none;
                }
            </style>
            <script>
                $('.morePrices').on('click', function(event){
                    var aButtonIdAttr = $(this).attr('id').split('_');
                    var nHotelId = aButtonIdAttr[1];

                    var oTr = $(this).parent().parent().next();

                    var sUrl = '/searchDetailed?' + $('#searchForm').serialize() + "&show_min=0&hotel_id="+nHotelId;
                    jQuery.ajax({
                        url:sUrl
                    }).done(function(data){
                        oTr.css('display', function(i,v){
                            return this.style.display === 'table-row' ? 'none' : 'table-row';
                        });
                        oTr.find('.addPricesDiv').html(data);
                        oTr.find('.addPricesDiv').slideToggle('slow');
                    });

                    event.preventDefault();
                });
            </script>