<?php
if (isset($_POST["email"]) && isset($_POST["username"]) && isset($_POST["password"])) {
    $email = htmlspecialchars($_POST["email"], ENT_QUOTES);
    $username = htmlspecialchars($_POST["username"], ENT_QUOTES);
    $password = htmlspecialchars($_POST["password"], ENT_QUOTES);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);    
    $unique_salt = uniqid(); // Her kullanıcı için farklı bir tuz (salt) oluşturun
    $hashed_email = hash('sha256', $email . $unique_salt);
    $error_msg_email = "";
    $error_msg_username = "";
    $error_msg_password = "";
    $error_msg = "";

    require_once("config");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg_email = "Geçersiz e-posta formatı.";
    }

    if (empty($email)) {
        $error_msg_email = "E-posta alanı zorunludur.";
    }

    if (empty($username)) {
        $error_msg_username = "Kullanıcı adı alanı zorunludur.";
    } else if (!preg_match("/^[a-zA-Z]+$/", $username)) {
        $error_msg_username = "Kullanıcı adı sadece harf içermelidir.";
    } else if (strlen($username) < 3 || strlen($username) > 20) {
        $error_msg_username = "Kullanıcı adı 3 ila 20 karakter uzunluğunda olmalıdır.";
    }

    if (empty($password)) {
        $error_msg_password = "Şifre alanı zorunludur.";
    } else if (strlen($password) < 8) {
        $error_msg_password = "Şifre en az 8 karakter uzunluğunda olmalıdır.";
    } else if (!preg_match("#[0-9]+#", $password)) {
        $error_msg_password = "Şifre en az bir rakam içermek zorundadır.";
    } else if (!preg_match("#[a-zA-Z]+#", $password)) {
        $error_msg_password = "Şifre en az bir harf içermelidir.";
    } else if (!preg_match("#[A-Z]+#", $password)) {
        $error_msg_password = "Şifre en az bir büyük harf içermelidir.";
    }

    if ($error_msg_email || $error_msg_username || $error_msg_password) {
        $error_msg = "Lütfen formdaki hataları düzeltip tekrar deneyiniz.";
    } else {
        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $dbusername, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $check_query = "SELECT * FROM users WHERE email=:email OR username=:username";
            $stmt = $pdo->prepare($check_query);
            $stmt->execute(array(':email' => $email, ':username' => $username));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                $insert_query = "INSERT INTO users (email, username, password) VALUES (:email, :username, :password)";
                $stmt = $pdo->prepare($insert_query);
                $stmt->bindParam(':email', $hashed_email, PDO::PARAM_STR);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $stmt->execute();
                $success = "Başarıyla Kayıt Oldunuz.";
                sleep(2);
                header("location: login");
            } else {
                $error_msg = "Bu e-posta veya kullanıcı adı zaten kullanımda.";
            }
        } catch (PDOException $e) {
            echo "Bağlantı hatası: " . $e->getMessage();
        }

        $pdo = null; // PDO bağlantısını kapat
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Vallance E-Commerce Register</title>
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
                <h3 class="card-title text-left mb-3">Register</h3>
                <form action="" method="POST">
                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control p_input">
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control p_input">
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control p_input">
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
                  <p class="sign-up text-center">Already have an Account?<a href="#"> Sign Up</a></p>
                  <p class="terms">By creating an account you are accepting our<a href="#"> Terms & Conditions</a></p>
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