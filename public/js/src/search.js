/**
 * Created by maga on 08.01.2017.
 */

var searchForm = function(){
    this.country_id = parseInt($.url('?country_id')) ? $.url('?country_id') : 0 ;
    this.city_id = parseInt($.url('?city_id')) ? $.url('?city_id') : 0 ;
    this.resort_id = parseInt($.url('?resort_id')) ? $.url('?resort_id') : 0 ;
    this.hotel_id = parseInt($.url('?hotel_id')) ? $.url('?hotel_id') : 0 ;
    this.pansion_id = parseInt($.url('?pansion_id')) ? $.url('?pansion_id') : 0 ;
    this.stars_id = parseInt($.url('?stars_id')) ? $.url('?stars_id') : 0 ;
    this.room_id = parseInt($.url('?room_id')) ? $.url('?room_id') : 2 ;
    /**
     * Страны
     */
    this.countries = function(callback){
        var self = this;
        $.ajax({
            url: '/api/countries/',
            method: "GET",
            dataType: "json"
        }).
        done(function(data){
            $('#country_id').select2({
                tags: "true",
                theme: "bootstrap",
                placeholder: "Select country",
                allowClear: true,
                width: "200px",
                data: $.map(data.aCountries, function (item) {
                    return {
                        id: item.CN_KEY,
                        text: item.CN_NAMELAT,
                        selected: (self.country_id == item.CN_KEY) ? 'selected' : null
                    }
                })
            });

            if( typeof(callback) === 'function' ){
                callback();
            }
        });
    };

    /**
     * Города
     */
    this.cities = function(callback){
        var self = this;
        // Инициализируем города
        $.ajax({
            url: '/api/cities/'+this.country_id,
            method: "GET",
            dataType: "json"
        }).
        done(function(data){
            $('#city_id').select2({
                tags: "true",
                theme: "bootstrap",
                placeholder: "Select city",
                allowClear: true,
                disabled: false,
                width: "200px",
                data: $.map(data.aCities, function (item) {
                    return {
                        id: item.CT_KEY,
                        text: item.CT_NAMELAT,
                        selected: (self.city_id == item.CT_KEY) ? 'selected' : null
                    }
                })
            });

            if( !self.city_id )
                $("#city_id").val(null).trigger("change");

            if( typeof(callback) === 'function' ){
                callback();
            }
        });
    };

    /**
     * Курорты
     */
    this.resorts = function(callback){
        var self = this;
        $.ajax({
            url: '/api/resorts/'+this.country_id,
            method: "GET",
            dataType: "json"
        }).
        done(function(data){
            $('#resort_id').select2({
                tags: "true",
                theme: "bootstrap",
                placeholder: "Select resorts",
                allowClear: true,
                disabled: false,
                width: "200px",
                data: $.map(data.aResorts, function (item) {
                    return {
                        id: item.RS_KEY,
                        text: item.RS_NAMELAT,
                        selected: (self.resort_id == item.RS_KEY) ? 'selected' : null
                    }
                })
            });

            if( !self.resort_id )
                $("#resort_id").val(null).trigger("change");

            if( typeof(callback) === 'function' ){
                callback();
            }
        });
    };

    /**
     * Отели
     */
    this.hotels = function (callback) {
        var self = this;
        // Инициализируем отели
        $.ajax({
            url: '/api/hotels/' + this.country_id + '/' + this.city_id,
            method: "GET",
            dataType: "json"
        }).
        done(function(data){
            $('#hotel_id').select2({
                tags: "true",
                theme: "bootstrap",
                placeholder: "Select hotel",
                allowClear: true,
                disabled: false,
                width: "auto",
                data: $.map(data.aHotels, function (item) {
                    return {
                        id: item.HD_KEY,
                        text: item.HD_NAME,
                        selected: (self.hotel_id == item.HD_KEY) ? 'selected' : null
                    }
                })
            });

            if( !self.hotel_id )
                $("#hotel_id").val(null).trigger("change");

        });
    };

    /**
     * Питание
     */
    this.pansion = function (callback) {
        var self = this;
        // Инициализируем отели
        console.log('URL', '/api/pansion/' + this.country_id + '/' + this.city_id+ '/' + this.resort_id+ '/' + this.hotel_id);
        $.ajax({
            url: '/api/pansion/' + this.country_id + '/' + this.city_id+ '/' + this.resort_id+ '/' + this.hotel_id,
            method: "GET",
            dataType: "json"
        }).
        done(function(data){
            $('#pansion_id').select2({
                tags: "true",
                theme: "bootstrap",
                placeholder: "Select meal plan",
                allowClear: true,
                disabled: false,
                width: "auto",
                data: $.map(data.aPansion, function (item) {
                    return {
                        id: item.PN_KEY,
                        text: item.PN_NAME,
                        selected: (self.pansion_id == item.PN_KEY) ? 'selected' : null
                    }
                })
            });

            if( !self.pansion_id )
                $("#pansion_id").val(null).trigger("change");
        });
    };

    /**
     * Звезды
     */
    this.stars = function (callback) {
        var self = this;
        // Инициализируем отели
        console.log('URL', '/api/stars/' + this.country_id + '/' + this.city_id+ '/' + this.resort_id+ '/' + this.hotel_id);
        $.ajax({
            url: '/api/stars/' + this.country_id + '/' + this.city_id+ '/' + this.resort_id+ '/' + this.hotel_id,
            method: "GET",
            dataType: "json"
        }).
        done(function(data){
            $('#stars_id').select2({
                tags: "true",
                theme: "bootstrap",
                allowClear: true,
                placeholder: "Select hotel category",
                disabled: false,
                width: "auto",
                data:   $.map(data.aStars, function (item) {
                            return {
                                id: item.COH_Id,
                                text: item.COH_Name,
                                selected: (self.stars_id == item.COH_Id) ? 'selected' : null
                            }
                        })
            });

            if( !self.stars_id )
                $("#stars_id").val(null).trigger("change");
        });
    };

    /**
     * размещение
     */
    this.rooms = function (callback) {
        var self = this;
        if (self.room_id)
            $('input:radio[name="room_id"][value="'+self.room_id+'"]').prop("checked", true);
    };

    /**
     * даты
     */
    this.dates_period = function (callback) {
        if ($.url('?dates_period')){
            $('#dates_period').val($.url('?dates_period'));
            $('#dates_period').daterangepicker({locale: {format: 'DD.MM.YYYY'}});
            return;
        }

        var beginDate = new Date();
        beginDate.setDate(beginDate.getDate() + 2);
        var endDate = new Date();
        endDate.setDate(endDate.getDate() + 7);
        $('#dates_period').daterangepicker(
            {
                locale: {
                    format: 'DD.MM.YYYY'
                },
                startDate: beginDate,
                endDate: endDate
            },
            function (start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            }
        );
    };


};
//--------------------------
$(document).ready(function () {
    var oSearchForm = new searchForm();

    $('#country_id, #resort_id, #city_id, #hotel_id, #pansion_id, #stars_id').select2({theme: "bootstrap"});


    oSearchForm.dates_period();
    oSearchForm.countries();
    oSearchForm.cities();
    oSearchForm.resorts();
    oSearchForm.hotels();
    oSearchForm.pansion();
    oSearchForm.stars();
    oSearchForm.rooms();

    //При изменении страны
    $('#country_id').on('change', function(){
        if (!$(this).val())
            return;
        oSearchForm.country_id = $(this).val();

        $('#city_id, #resort_id, #hotel_id, #pansion_id, #stars_id').prop("disabled", true);
        $('#city_id, #resort_id, #hotel_id, #pansion_id, #stars_id').empty();

        oSearchForm.cities();
        oSearchForm.resorts();
        oSearchForm.hotels();
        oSearchForm.pansion();
        oSearchForm.stars();
    });

    $('#city_id').on('change', function(){
        if (!$(this).val())
            return;
        oSearchForm.city_id = $(this).val();

        $('#hotel_id, #pansion_id, #stars_id').prop("disabled", true);
        $('#hotel_id, #pansion_id, #stars_id').empty();
        oSearchForm.hotels();
        oSearchForm.pansion();
        oSearchForm.stars();
    });

    $('#resort_id').on('change', function(){
        if (!$(this).val())
            return;
        oSearchForm.resort_id = $(this).val();

        $('#hotel_id, #pansion_id, #stars_id').prop("disabled", true);
        $('#hotel_id, #pansion_id, #stars_id').empty();
        oSearchForm.hotels();
        oSearchForm.pansion();
        oSearchForm.stars();
    });

    $('#hotel_id').on('change', function(){
        if (!$(this).val())
            return;
        oSearchForm.hotel_id = $(this).val();

        $('#pansion_id, #stars_id').prop("disabled", true);
        $('#pansion_id, #stars_id').empty();
        oSearchForm.pansion();
        oSearchForm.stars();
    });

    $('.page_button').on('click', function(){
        sUrl = '/search?' + $('#searchForm').serialize() + "&page="+$(this).attr("num");
        //console.log(sUrl);
        window.location.href = sUrl;
    });
});

