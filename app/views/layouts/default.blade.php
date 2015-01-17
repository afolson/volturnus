@include('includes.header')
<body>
@include('includes.navbar')
<!-- Begin page content -->
<div class="container">
    <div class="page-header">
        <h1>{{ $pageTitle }}</h1>
    </div>
@yield('content')
</div>
@include('includes.footer')
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
