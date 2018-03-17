            <!-- Search Form -->
            <form action="{{ url('search') }}" method="GET" class="form-horizontal" id="searchForm">

                <!-- Countries -->
                <div class="form-group">
                    <label for="task-name" class="col-sm-3 control-label">Countries</label>

                    <div class="col-sm-6">
                        <select id="country_id" name="country_id" class="form-control input-lg select2-single" >
                            {{--<option></option>--}}
                        </select>
                    </div>
                </div>

                <!-- Cities -->
                <div class="form-group">
                    <label for="task-name" class="col-sm-3 control-label" >Cities</label>

                    <div class="col-sm-6">
                        <select id="city_id" name="city_id" class="form-control input-lg select2-single"   disabled>
                            <option></option>
                        </select>
                    </div>
                </div>

                <!-- Resorts -->
                <div class="form-group" style="display:none">
                    <label for="task-name" class="col-sm-3 control-label">Resorts</label>

                    <div class="col-sm-6">
                        <select id="resort_id" name="resort_id" class="form-control input-lg select2-single"  disabled>
                            <option></option>
                        </select>
                    </div>
                </div>

                <!-- Dates period - arrival and departure-->
                <div class="form-group">
                    <label for="task-name" class="col-sm-3 control-label">Arrival-Departure</label>

                    <div class="col-sm-6">
                        <input type="text" name="dates_period" id="dates_period" class="form-control" value="{{ old('dates_period') }}">
                    </div>
                </div>

                <!-- Hotels -->
                <div class="form-group">
                    <label for="task-name" class="col-sm-3 control-label">Hotels</label>

                    <div class="col-sm-6">
                        <select id="hotel_id" name="hotel_id" class="form-control input-lg select2-single"  disabled>
                            <option></option>
                        </select>
                    </div>
                </div>

                <!-- Rooms -->
                <div class="form-group">
                    <label for="task-name" class="col-sm-3 control-label">Choose pax in room</label>

                    <div class="col-sm-6">

                        <table class="roomsTable" cellspacing="0" cellpadding="0" border="0" class="table">
                            <tr>
                                <td><img class="roomImg" src="/img/1.gif"><input type="Radio" size="1" name="room_id" value="1"></td>
                                <td><img class="roomImg" src="/img/2.gif"><input type="Radio" size="1" name="room_id" value="2"></td>
                                <td><img class="roomImg" src="/img/3.gif"><input type="Radio"  size="1" name="room_id" value="3"></td>
                                {{--<td><img class="roomImg" src="/img/4.gif"><input type="Radio"  size="1" name="room_id" value="4"></td>--}}
                                {{--<td><img class="roomImg" src="/img/1_1.gif"><input type="Radio" size="1" name="room_id" value="11"></td>--}}
                                <td><img class="roomImg" src="/img/2_1.gif"><input type="Radio" size="1" name="room_id" value="21"></td>
                                {{--<td><img class="roomImg" src="/img/2_2.gif"><input type="Radio" size="1" name="room_id" value="22"></td>--}}
                                {{--<td><img class="roomImg" src="/img/3_1.gif"><input type="Radio" size="1" name="room_id"  value="31"></td>--}}
                            </tr>
                        </table>

                    </div>
                </div>

                <!-- Meal plan -->
                <div class="form-group">
                    <label for="task-name" class="col-sm-3 control-label">Meal Plan</label>

                    <div class="col-sm-6">

                        <select id="pansion_id" name="pansion_id" class="form-control input-lg select2-single" disabled>
                            <option></option>
                        </select>

                    </div>
                </div>

                <!-- Stars -->
                <div class="form-group">
                    <label for="task-name" class="col-sm-3 control-label">Category</label>

                    <div class="col-sm-6">

                        <select id="stars_id" name="stars_id" class="form-control input-lg select2-single" disabled>
                            <option></option>
                        </select>

                    </div>
                </div>




                <!-- Add Task Button -->
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="submit" class="btn btn-default">
                            <i class="fa fa-btn fa-plus"></i>Search
                        </button>
                    </div>
                </div>
            </form>

            <!-- JavaScripts -->
            <script type="text/javascript" src="/js/src/search.js"></script>
            <script type="text/javascript" src="/js/vendor/select2/select2.full.min.js"></script>
            <script type="text/javascript" src="/js/vendor/bootstrap-daterangepicker/moment.min.js"></script>
            <script type="text/javascript" src="/js/vendor/bootstrap-daterangepicker/daterangepicker.js"></script>

            <!-- CSS -->
            <link href="/js/vendor/select2/css/select2.min.css" rel="stylesheet">
            <link href="/js/vendor/select2/css/select2-bootstrap.min.css" rel="stylesheet">
            <link href="/js/vendor/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" media="all" />

            <style>
                .roomsTable td{
                    padding-right: 10px;
                    white-space: nowrap;
                }

                .roomImg{
                    padding-right: 3px;
                }

            </style>