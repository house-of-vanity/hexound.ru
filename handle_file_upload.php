<?php

$inp = file_get_contents('mods.json');
$tempArray = json_decode($inp);
$time = time();
//print_r($tempArray);

//$id = uniqid();


$fileName = $_FILES['files']['name'];
$fileType = $_FILES['files']['type'];
$fileContent = file_get_contents($_FILES['files']['tmp_name']);
$dataUrl = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);
$json = json_encode(array(
  'name' => $fileName,
  'type' => $fileType,
  'dataUrl' => $dataUrl
));
$target = 'mods/'.$_FILES['files']['name'];
if (strpos($fileType, 'application') !== false) {
    echo '{"type":"message","content":"go_to_the_hooy"}';
} else {
    $md5 = md5_file($_FILES['files']['tmp_name']);
    $item = null;
    foreach($tempArray as $struct) {
        if ($md5 == $struct->md5) {
            $item = $struct;
            break;
        }
    }
    if ($item == null){
        move_uploaded_file( $_FILES['files']['tmp_name'], $target);
        $id = $tempArray[count($tempArray)-1]->id + 1;
        $upl_file->filename = $_FILES['files']['name'];
        $upl_file->md5 = $md5;
        $upl_file->id = $id;
        $upl_file->time = $time;
        
        //echo $json.'<br>';
        if ($upl_file->filename != null){
            array_push($tempArray, $upl_file);
            $jsonData = json_encode($tempArray, JSON_PRETTY_PRINT);
            file_put_contents('mods.json', $jsonData);
        }
    }else{
        echo "Warning. File has uploaded already.";
    }
}


//$jsonData = json_encode($tempArray);
//file_put_contents('results.json', $jsonData);
//print_r($tempArray);

?>
