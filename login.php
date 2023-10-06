<?php
session_start();

if (isset($_POST["username_or_email"]) && isset($_POST["password"])) {
    $username_or_email = htmlspecialchars($_POST['username_or_email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    require_once("config.php");
    $error_msg_username = "";
    $error_msg_password = "";

    if (empty($username_or_email)) {
        $error_msg_username = "Kullanıcı adı veya e-posta alanı zorunludur.";
    }

    if (empty($password)) {
        $error_msg_password = "Şifre alanı zorunludur.";
    }

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Kullanıcı adı veya e-posta ile veritabanını sorgula
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username_or_email, PDO::PARAM_STR);
        $stmt->bindParam(':email', $username_or_email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard");
            exit();
        } else {
            if (!$user) {
                $error_msg_username = "Kullanıcı Adı veya E-posta Kayıtlı Değil.";
            } else {
                $error_msg_password = "Şifre Yanlıştır.";
            }
        }
    } catch (PDOException $e) {
        echo "Bağlantı hatası: " . $e->getMessage();
    }

    $pdo = null;
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
                <h3 class="card-title text-left mb-3">Login</h3>
                <form action="" method="POST">
                  <div class="form-group">
                    <label>Username or Email</label>
                    <input type="text" name="username_or_email" class="form-control p_input">
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control p_input" required>
                </div>
                  <div class="form-group d-flex align-items-center justify-content-between">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input"> Remember me </label>
                    </div>
                    <a href="#" class="forgot-pass">Forgot password</a>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block enter-btn">Login</button>
                  </div>
                  <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
                        <div class="error"><?php echo $error_msg_username; ?></div>
                        <div class="error"><?php echo $error_msg_password; ?></div>
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