<?php

# works for POST http method only.
if ($_SERVER['REQUEST_METHOD']=='POST') { 
# works for button LOGIN only
  if ($_POST['submit'] == 'Login'){
# calculate hash for provided password with default settings
    $hash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
# filter SQL injection
    $name = mysqli_real_escape_string($con, $_POST['login']); 
    $query = "SELECT hash FROM users WHERE name='$name';";
    $raw = mysqli_query($con, $query); 
# password_verify returns true if provided pass hash related with saved pass hash
    if (password_verify($_POST['pass'], mysqli_fetch_assoc($raw)['hash'])){
      $hash = md5($hash);
      $query = "SELECT id FROM users WHERE name='$name';";
      $raw = mysqli_query($con, $query); 
      $user_id = mysqli_fetch_assoc($raw)['id'];
# write temporarily info about logged in users. if user has fuck_cookie that we have in this table we consider this user as authentic and logged in.
      $query = "INSERT INTO auth_tmp (hash, name_id, valid) VALUES ('$hash', '$user_id', NOW() + INTERVAL 30 DAY);";
      $raw = mysqli_query($con, $query);
# fuck_cookie is md5 from provided pass hash, this shows user correct auth within 1 month
      setcookie('fuck_cookie', $hash, time() + 60 * 60 * 24 * 30);
      $_SESSION['user_name'] = $name;
      $_SESSION['user_id'] = $user_id;
      header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?message=Authorization successful&severity=0");  
    }else{
      header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?message=Authorization error&severity=1");  
    }
  }elseif ($_POST['submit'] == 'gtfo'){
    if (isset($_COOKIE['fuck_cookie'])){
      $fuck_cookie = $_COOKIE['fuck_cookie'];
      $query = "DELETE FROM auth_tmp WHERE hash='$fuck_cookie';";
      $raw = mysqli_query($con, $query); 
      setcookie('fuck_cookie', '', time() - 3600);
      session_destroy();
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?message=Bye bye.&severity=0");  
  }
}
# works for http GET request only
if ($_SERVER['REQUEST_METHOD']=='GET') {
# if user has fuck_cookie we send him user bar with his info (name, etc...)
  if (isset($_COOKIE['fuck_cookie'])){
    $fuck_cookie = $_COOKIE['fuck_cookie'];
    lg(0, 'fuck_cookie - '.$_COOKIE['fuck_cookie']);
    $query = "SELECT name FROM users WHERE id = (SELECT name_id FROM (SELECT * FROM auth_tmp WHERE hash = '$fuck_cookie') AS T1 WHERE IF (CURDATE() < valid, 1, 0) = 1);";
    $raw = mysqli_fetch_assoc(mysqli_query($con, $query));

    if (strlen($raw['name']) > 0) {
      lg(0, "Name - ".$raw['name']);
      lg(0, "Authorized by user fuck_cookie.");
    } else {
      lg(1, "Wrong cookie");
      setcookie('fuck_cookie', '', time() - 3600);
    }
  ?>
<div id="user_ui">
<?php
echo $raw['name'].'<br>';
?>
  <form method="post" action="">					
    <input type="submit" name="submit" value="gtfo">
  </form>
</div>
<?php
# in other way we send him form for login or register
  } else {
    include("captcha.php");
    $_SESSION['captcha'] = simple_php_captcha();
    $_SESSION['captcha']['code'] = strtolower($_SESSION['captcha']['code']);
    lg(0, $_SESSION['captcha']['code']);
  ?>
<div id="user_ui">
<button id=user_ui_button onclick="show()">swap</button>

  <div id=register_form style='display:none'>
    <form method="post" action="">
        <div class="form_description">
            <h2>Register</h2>
        </div>						
            <label class="description" for="login">Login </label>
            <div>
                <input id="login" name="login" type="text" maxlength="255" value=""> 
            </div> 
            <label class="description" for="pass">Password </label>
            <div>
                <input id="pass" name="pass" class="element text medium" type="password" maxlength="255" value=""> 
            </div> 
            <img src="<?php echo $_SESSION['captcha']['image_src']; ?>" alt="CAPTCHA code"><br>
            <div>
                <input id="captcha" name="captcha" type="text" maxlength="15" value=""> 
            </div> 
            <input id="tea-submit" type="submit" name="submit" value="Register">
    </form>
  </div>
  <div id=login_form style='display:block'>
    <form method="post" action="">
        <div class="form_description">
            <h2>Login</h2>
        </div>						
            <label class="description" for="login">Login </label>
            <div>
                <input id="login" name="login" type="text" maxlength="255" value=""> 
            </div> 
            <label class="description" for="pass">Password </label>
            <div>
                <input id="pass" name="pass" class="element text medium" type="password" maxlength="255" value=""> 
            </div> 
            <input id="tea-submit" type="submit" name="submit" value="Login">
    </form>
  </div>
</div>
  <?php
  }
}

?>

<script>
  var user_ui = 0
  function show() {
    if (user_ui == 1){
      document.getElementById("register_form").style.display = "none";
      document.getElementById("login_form").style.display = "block";
      window.user_ui = 0;
    }else{
      document.getElementById("register_form").style.display = "block";
      document.getElementById("login_form").style.display = "none";
      window.user_ui = 1;
    }
  }
</script>
