<div class="log" <?php 
if($LOG_LEVEL == 0){
    echo "style='display:none'";
}else{
    echo "style='position:absolute;right: 100px;'";
    }?>>
    <b>Log console</b>
</div>
<?php
function lg($level, $message){
    switch ($level) {
        case 0:
            echo "<script>$('.log').append('<pre>INFO: $message</pre>');</script>";
            break;
        case 1:
            echo "<script>$('.log').append('<pre>ERROR: $message</pre>');</script>";
            break;
    }
}
if (isset($_GET['message'])&&isset($_GET['severity'])){
  lg($_GET['severity'], $_GET['message']);
}
?>
