<?php
$inp = file_get_contents('mods.json');
$library = json_decode($inp);
echo json_encode($library);
?>
