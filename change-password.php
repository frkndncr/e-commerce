<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login");
    exit();
}

if(isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = htmlspecialchars($_POST["current_password"], ENT_QUOTES, 'UTF-8');
    $new_password = htmlspecialchars($_POST["new_password"], ENT_QUOTES, 'UTF-8');
    $confirm_password = htmlspecialchars($_POST["confirm_password"], ENT_QUOTES, 'UTF-8');
    require_once("config.php");

    try {
      $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $dbusername, $dbpassword);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($current_password, $user["password"])) {
            if ($new_password == $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
                $stmt->execute([$hashed_password, $username]);
                $success = "Şifreniz Başarıyla Değiştirilmiştir!";
                sleep(3);
                header("Location: dashboard");
                exit();
            } else {
                $error_msg_tekrar = "Yeni şifrenizi doğrulamak için aynı şifreyi girin.";
            }
        } else {
            $error_msg_mevcut = "Mevcut şifreniz yanlış.";
        }
    } catch(PDOException $e) {
        echo "Bağlantı hatası: " . $e->getMessage();
    }        
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Vallance E-Commerce Login</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/logo2.png" />
  </head>
  <body>
  <div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="row w-100 m-0">
      <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
        <div class="card col-lg-4 mx-auto">
          <div class="card-body px-5 py-5">
            <h3 class="card-title text-left mb-3">Change Password</h3>
            <form action="" method="POST">
              <div class="form-group">
                <label for="current_password">Old Password</label>
                <input type="password" name="current_password" class="form-control p_input" required>
              </div>
              <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" class="form-control p_input" required>
              </div>
              <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control p_input" required>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary btn-block enter-btn">Change Password</button>
              </div>
              <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
                <div class="error"><?php echo $error_msg_username; ?></div>
                <div class="error"><?php echo $error_msg_old_password; ?></div>
                <div class="error"><?php echo $error_msg_new_password; ?></div>
                <div class="error"><?php echo $error_msg_confirm_password; ?></div>
              <?php endif; ?>
              <p class="sign-up">Don't have an Account?<a href="register"> Sign Up</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
  </body>
</html>