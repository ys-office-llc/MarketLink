<!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8' />
  <!-- スマートデバイス対応 -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- 最初に読み込まないと他の機能が全く働かなくなることがある。
       deferステートメントを付けてもだめ -->
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

  <script
    src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
    integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
    crossorigin="anonymous">
  </script>

  <link rel="shortcut icon" href="<?php print $base_url; ?>/favicon.ico" />

  <link rel="stylesheet" href="<?php print $base_url; ?>/css/bootstrap-submenu.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.bootstrap.min.css">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css" >
  <link href="<?php print $base_url; ?>/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
  <link href="<?php print $base_url; ?>/css/page-top.css" media="all" rel="stylesheet" type="text/css" />

  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js" defer></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" defer></script>
  <script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" defer></script>
  <script src="https://cdn.datatables.net/1.10.9/js/dataTables.bootstrap.min.js" defer></script>
  <script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" defer></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>

  <script src="<?php print $base_url; ?>/js/bootstrap-submenu.min.js" defer></script>
  <script src="<?php print $base_url; ?>/js/bootstrap-hover-dropdown.min.js" defer></script>
  <script src="<?php print $base_url; ?>/js/assets/docs.js" defer></script>

  <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload.
       This must be loaded before fileinput.min.js -->
  <script src="<?php print $base_url; ?>/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
  <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
       This must be loaded before fileinput.min.js -->
  <script src="<?php print $base_url; ?>/js/plugins/sortable.min.js" type="text/javascript"></script>
  <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files.
       This must be loaded before fileinput.min.js -->
  <script src="<?php print $base_url; ?>/js/plugins/purify.min.js" type="text/javascript"></script>
  <!-- the main fileinput plugin file -->
  <script src="<?php print $base_url; ?>/js/fileinput.min.js"></script>
  <!-- optionally if you need translation for your language then include 
      locale file as mentioned below -->
  <script src="<?php print $base_url; ?>/js/ja.js"></script>

  <script src="<?php print $base_url; ?>/js/jquery.sticky.js"></script>

  <!-- ここもdeferステートメント付けると動作不能に・・・わからん・・・ -->
  <script src="<?php print $base_url; ?>/js/tweyes.js"></script>
  <script src="<?php print $base_url; ?>/js/bid_price_limit_calculation.js"></script>
  <script src="<?php print $base_url; ?>/js/page-top.js"></script>

  <title>

    <?php if (isset($title)): print $this->escape($title).' - '; endif; ?>
    MarketLink for CAMERA

  </title>

</head>

<body>
  <!-- 固定幅のバージョン -->
  <div class="container bg-default">
  <!-- 可変幅のバージョン -->
  <!-- <div class="container-fluid bg-info"> -->
    <?php print $_content; ?>

    <center>
      <footer>

        <p><small>&copy; Copyright 2017 YS office LLC.</small></p>

      </footer>
    </center>
  </div>

  <p id="page-top">
    <a href="#">PAGE TOP</a>
  </p>


</body>
</html>
