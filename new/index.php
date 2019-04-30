<style>

html,
body {
  height: 98%;
}
.wrapper {
  display: table;
  height: 100%;
}
.content {
  display: table-row;
  height: 100%;
}
</style>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
</head>
<div class='wrapper'>
<div class='content'>
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<?php
session_start();

$LOG_LEVEL = 0;
# you can use logging via PHP function lg(<severity>, <message>)
# where severity may be 1 - Error, 0 -Info.
# or you can use https://url?message=<your message>&severity=<severity> with every page redirect. 
include('logging.php');
include('db.php');
include('user_login.php');
include('user_register.php');
include('library.php');


?>
</div>
  <div class='footer'>
    <?php include('upload.php');?>
  </div>
</div>
<iframe name='player' id="iframe" src="simplePlayer/index.html" style="border:none;width:100%;height:500px;"></iframe>
  <script>
      iframe.contentWindow.postMessage('123', '*');
  </script>

