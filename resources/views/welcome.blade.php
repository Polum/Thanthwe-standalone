<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Thanthwe</title>
  <meta name="description" content="Church monitoring system that keeps track of members and all activities,
  church accounting on tithes, sunday basket and other offerings including other expenses and payment voucher processing.
  Generate church reports that are also downloadable in different formats." />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  {{--  <!-- for ios 7 style, multi-resolution icon of 152x152 -->  --}}
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  {{--  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->  --}}
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" sizes="196x196" href="{{ asset('./images/ico.png') }}">

  {{--  <!-- style -->  --}}
  <link rel="stylesheet" href="{{ asset('css/animate.css/animate.min.css') }}" type="text/css" />

  <link rel="stylesheet" href="{{ asset('css/glyphicons/glyphicons.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('css/material-design-icons/material-design-icons.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('css/ionicons/css/ionicons.min.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('css/simple-line-icons/css/simple-line-icons.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" type="text/css" />

  {{--  <!-- build:css css/styles/app.min.css -->  --}}
  <link rel="stylesheet" href="{{ asset('css/styles/app.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('css/styles/style.css') }}" type="text/css" />
  {{--  <!-- endbuild -->  --}}
  <link rel="stylesheet" href="{{ asset('css/styles/font.css') }}" type="text/css" />
</head>
<body>
    <div class="app" id="app">
    </div>

   <script src="{{ asset('./js/app.js') }}"></script>
   <script src="scripts/scripts.js"></script>
   {{-- <!-- build:js scripts/app.min.js --> --}}
   {{-- <!-- jQuery --> --}}
     <script src="libs/jquery/dist/jquery.js"></script>
   {{-- <!-- Bootstrap --> --}}
     <script src="libs/tether/dist/js/tether.min.js"></script>
     <script src="libs/bootstrap/dist/js/bootstrap.js"></script>
   {{-- <!-- core --> --}}
     <script src="libs/jQuery-Storage-API/jquery.storageapi.min.js"></script>
     <script src="libs/PACE/pace.min.js"></script>
     <script src="libs/jquery-pjax/jquery.pjax.js"></script>
     <script src="libs/blockUI/jquery.blockUI.js"></script>
     <script src="libs/jscroll/jquery.jscroll.min.js"></script>

     <script src="scripts/config.lazyload.js"></script>
     <script src="scripts/ui-load.js"></script>
     <script src="scripts/ui-jp.js"></script>
     <script src="scripts/ui-include.js"></script>
     <script src="scripts/ui-device.js"></script>
     <script src="scripts/ui-form.js"></script>
     <script src="scripts/ui-modal.js"></script>
     <script src="scripts/ui-nav.js"></script>
     <script src="scripts/ui-list.js"></script>
     <script src="scripts/ui-screenfull.js"></script>
     <script src="scripts/ui-scroll-to.js"></script>
     <script src="scripts/ui-toggle-class.js"></script>
     <script src="scripts/ui-taburl.js"></script>
     <script src="scripts/app.js"></script>
     <script src="scripts/ajax.js"></script>
   {{-- <!-- endbuild --> --}}
</body>
</html>
