<?php session_start();

include("db.php");
include("functions.php");

if(isset($_SESSION['email'])){
  header("Location: /index.php");
  exit;

}
?>

<html>
<head>
  <!-- <script type="text/javascript" src="validation.js" ></script> -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="style1.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
  <script src='https://www.google.com/recaptcha/api.js'></script>


  <title>Reset Password</title>
</head>
<body>
  <section class="hero has-background-white-bis is-large">
    <div class="hero-body">

      <div class="registration has-background-white">
        <div class="columns is-centered">
          <div class="card-content">
            <h5 class="title is-5">Reset Password</h5>
            <div class="regi_error"> <?php echo $error ?></div>
            <form class="register" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
              <div class="field">
                <input class="input" id ="password" type="password" name="password" placeholder="New Password" required><br/>
              </div>
              <div class="field">
                <input class="input" id ="confirmPassword" type="password" name="confirmPassword" placeholder="Confirm password" required><br/>
              </div>
              <!-- <div class="tagline">6 - 10 characters, must contain at least one number and starts with an alphabet</div> -->
              <div class="field">
                <button class="button is-fullwidth has-background-primary" type="submit" class="button" name="reset">Reset Password</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>


  <div class="footer">
    <?php include("footer.php"); ?>
  </div>
</body>
</html>
<?php
if (isset($_GET['token'])){
  $_SESSION['token'] = $token = $_GET['token'];
}


?>

<?php
if (isset($_POST['reset'])){

  $_password = $_POST['password'];
  $_confirmPass = $_POST['confirmPassword'];
  $token = $_SESSION['token'];
  //echo "$token";
  session_destroy();

  // if (name_validation($_username)==false){
  //   return;
  // }
  // if (email_validation($_email)==false) {
  //   return;
  // }
  // elseif (password_validation($_password)==false) {
  //   return;
  //
  // }
  // elseif (passwordCheck($_password, $_confirmPass)== false){
  //     return;
  // }

  $hashed_password = password_hash($_password, PASSWORD_DEFAULT);

  $query_chk = "SELECT user_id, timestamp FROM pass_session WHERE token='$token'";
  $result_chk = mysqli_query($conn, $query_chk);

  if (mysqli_num_rows($result_chk) > 0) {
    // output data of each row
    while($xrow = mysqli_fetch_assoc($result_chk)) {
      $user_id = $xrow["user_id"];
      $time_stamp = $xrow["timestamp"];
    }
  }

  // if ($sql_email==$_email){
  //   echo "
  //   <div class='regi_error'>
  //   Email address is already registered.
  //   </div>
  //   ";
  //   return ;
  // }

  $query_chk = "SELECT user_id, timestamp FROM pass_session WHERE token='$token'";
  $result_chk = mysqli_query($conn, $query_chk);

  if (mysqli_num_rows($result_chk) > 0) {
    // output data of each row
    while($xrow = mysqli_fetch_assoc($result_chk)) {
      $user_id = $xrow["user_id"];
      $exp_time = $xrow["timestamp"];
    }
  }

  $now_time = date('Y-m-d H:i:s');
  if($now_time>$exp_time){
    echo "Sorry your token has been expired.";
    $query_ = "DELETE FROM pass_session WHERE token='$token'";
    mysqli_query($conn, $query_);
    return ;
  }



  $query = "UPDATE Shopper SET sh_password='$_password'";

  if (mysqli_query($conn, $query)) {

    $query_ = "DELETE FROM pass_session WHERE token='$token'";
    mysqli_query($conn, $query_);

    //echo "<script>window.location = '/index.php'</script>";

  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }

  //mysqli_close($conn);

}




?>