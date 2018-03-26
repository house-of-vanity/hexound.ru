<?php
# works for POST http method only.
if ($_SERVER['REQUEST_METHOD']=='POST') { 
# check captcha
  

# works for button REGISTER only
  if ($_POST['submit'] == 'Register'){
    if (strtolower($_SESSION['captcha']['code']) == strtolower($_POST['captcha'])) {
# calculate hash for provided password with default settings
        $hash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $name = strtolower(mysqli_real_escape_string($con, $_POST['login'])); 
# constuct SQL query
        $query = "SELECT id FROM users WHERE name='$name';";
# fetch mysql result to assoc PHP array 
        $raw = mysqli_fetch_assoc(mysqli_query($con, $query));
# if there isn't this name yet let us register, skip in other way
        if (strlen($raw['id']) == NULL){
# write new user into db
            $query = "INSERT INTO users (name, hash) VALUES ('$name', '$hash');";
            $raw = mysqli_query($con, $query);
# forward user back with message
            header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?message=Registration successful&severity=0");
        } else {
            header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?message=registration error&severity=1");  
        }
    }else {
    header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?message=Wrong captcha.&severity=1");  
    }
  } 
}

  ?>
