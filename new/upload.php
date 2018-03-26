Share your modules with us! 
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="module" id="module">
    <input type="submit" value="Send" name="submit">
</form>
<?php
include('settings.php');
if ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['submit'] == "Send") { 
    $target_dir = "modules/";
    $hash = md5_file($_FILES["module"]["tmp_name"]);
    $real_name = basename($_FILES["module"]["name"]);
    $target_file = $target_dir . $hash . "." . explode(".", $real_name)[sizeof(explode(".", $real_name))-1];
    $uploadOk = 1;
    $mime_type = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = mime_content_type($_FILES["module"]["tmp_name"]);
        if($check == 'audio/x-mod') {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
            $message = "Module ".basename($real_name)." was not uploaded Not a module.";
        }
        // Check if file already exists
if (file_exists($target_file)) {
    $hash = md5_file($_FILES["module"]["tmp_name"]);
    $query = "SELECT COUNT(`id`) as count FROM `modules` WHERE `hash` = '$hash';";
    $raw = mysqli_query($con, $query);
    $mod_id = mysqli_fetch_assoc($raw);
    if ($mod_id['count'] > 0) {
        $uploadOk = 0;
        $message = "Module ".basename($real_name)." was not uploaded. File exist.";
    }
}
// Check file size
if ($_FILES["module"]["size"] > 5000000) {
    $uploadOk = 0;
    $message = "Module ".basename($real_name)." was not uploaded. Too large.";
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
// if everything is ok, try to upload file
    header("Location: https://".$CFG_app_url."?message=".$message."&severity=1");  
} else {
    if (move_uploaded_file($_FILES["module"]["tmp_name"], $target_file)) {
        $user_id = $_SESSION['user_id'];
        if ($user_id){
            $query = "INSERT INTO modules (`name`, `uploaded_by`, `hash`) VALUES ('$real_name', '$user_id', '$hash');";
        }else{
            $query = "INSERT INTO modules (`name`, `uploaded_by`, `hash`) VALUES ('$real_name', '0', '$hash');";
        }
        $raw = mysqli_query($con, $query);
        header("Location: https://".$CFG_app_url."?message=Module ".$real_name." was uploaded.&severity=0");  
      
    } else {
        header("Location: https://".$CFG_app_url."?message=Something went wrong.&severity=1");  
    }
}
    }
}
?>
