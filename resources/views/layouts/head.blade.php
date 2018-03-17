<!-- Styles -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
{{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<!-- JavaScripts -->
<script type="text/javascript" src="/js/vendor/js-url/url.min.js"></script>
<script type="text/javascript" src="/js/vendor/pnotify/pnotify.min.js"></script>
<script type="text/javascript" src="/js/src/search.js"></script>
<script type="text/javascript" src="/js/vendor/select2/select2.full.min.js"></script>
<script type="text/javascript" src="/js/vendor/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- CSS -->
<link href="/js/vendor/select2/css/select2.min.css" rel="stylesheet">
<link href="/js/vendor/select2/css/select2-bootstrap.min.css" rel="stylesheet">
<link href="/js/vendor/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" media="all" />
<link href="/js/vendor/pnotify/pnotify.min.css" rel="stylesheet" type="text/css" media="all" />

<style>

    .fa-btn {
        margin-right: 6px;
    }

    .navbar-brand{
        background-image: url('/img/ftlogo.gif');
        background-repeat: no-repeat;
        background-size: 180px 50px;
        width:180px;
    }

</style>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
</script>