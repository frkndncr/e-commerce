<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("config.php");
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Bağlantı hatası: " . $e->getMessage();
}

function checkIfEmailExists($email, $conn) {
  try {
    $sql = "SELECT * FROM users where email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->rowCount() > 0;
  } catch(PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
  }
}

if(isset($_POST['username_or_email'])) {
  $receiverInput = $_POST['username_or_email'];
  $receiverEmail = filter_var($receiverInput, FILTER_VALIDATE_EMAIL);

  if ($receiverEmail === false || !checkIfEmailExists($receiverEmail, $conn)) {
    $error_msg_or_email_username = "Lütfen geçerli bir e-posta adresi girin ve bu e-posta ile bir kayıt bulunduğundan emin olun.";
  } else {
    try {
      $randomPassword = bin2hex(random_bytes(8));
      $reset_token = bin2hex(random_bytes(16));

      $now = date("Y-m-d H:i:s");
      $expir_time = date("Y-m-d H:i:s", strtotime(' + 15 minutes', strtotime($now)));

      $query = "UPDATE users SET reset_token = :reset_token, expired_token = :expir_time WHERE email = :receiverEmail";
      $stmt = $conn->prepare($query);
      $stmt->bindParam(':reset_token', $reset_token, PDO::PARAM_STR);
      $stmt->bindParam(':expir_time', $expir_time, PDO::PARAM_STR);
      $stmt->bindParam(':receiverEmail', $receiverEmail, PDO::PARAM_STR);

      if ($stmt->execute()) {
        // Şifre sıfırlama isteği başarılı bir şekilde veritabanına kaydedildi
        $mail = new PHPMailer(true);
        try {
          $message = '<html>
            <head>
              <title>Şifre Sıfırlama İsteği</title>
            </head>
            <body>
              <p>Merhaba,</p>
              <p>Şifrenizi sıfırlama isteğinde bulundunuz. Şifrenizi sıfırlamak için aşağıdaki linke tıklayabilirsiniz:</p>
              <p>Şifrenizi sıfırlamak için <a href="http://localhost/reset-password.php?token='.$reset_token.'">buraya</a> tıklayın.</p>
              <p>Bu isteği siz yapmadıysanız lütfen dikkate almayınız.</p>
            </body>
          </html>';

          $mail->isSMTP();
          $mail->Host       = 'smtp.hostinger.com';
          $mail->SMTPAuth   = true;
          $mail->Username   = 'support@vallancecard.com.tr';
          $mail->Password   = 'Furkan1234.'; 
          $mail->SMTPSecure = 'ssl';
          $mail->Port       = 465;
          $mail->setFrom('support@vallancecard.com.tr', 'Vallance Destek');
          $mail->addAddress($receiverEmail);
          $mail->addReplyTo('support@vallancecard.com.tr', 'Vallance Destek');
          $mail->isHTML(true);
          $mail->Subject = '=?UTF-8?B?'.base64_encode('Şifre Değiştirme').'?=';
          $mail->Body    = $message;
          $mail->AltBody = 'Merhaba, Yeni şifreniz: 123456. Yeni şifrenizle giriş yapabilirsiniz. Şifrenizi değiştirmek için şu sayfayı ziyaret edebilirsiniz: http://localhost/panel/password-reset.php';
          $mail->send();
          $succes_mail = 'Mail başarıyla gönderildi';
        } catch (Exception $e) {
          $error_email = 'Mail gönderilemedi. Hata: '. $mail->ErrorInfo;
        }
      } else {
        $error_mail = "Şifre sıfırlama işlemi sırasında bir hata oluştu.";
      }
    } catch(PDOException $e) {
      echo "Şifre güncellenirken bir hata oluştu: " . $e->getMessage();
    }
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
                <h3 class="card-title text-left mb-3">Forgot Password</h3>
                <form action="" method="POST">
                  <div class="form-group">
                    <label>Username or Email</label>
                    <input type="text" name="username_or_email" class="form-control p_input">
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block enter-btn">Send Reset Link</button>
                  </div>
                  <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
                    <?php endif; ?>
                  <p class="sign-up">Do you already have an account?<a href="login"> Login</a></p>
                  <form method="post">
                    <?php if (isset($error_mail)) { ?>
                        <div class="error"><?php echo $error_mail; ?></div>
                    <?php }
                    ?>
                    <form method="post">
                    <?php if (isset($error_kayit)) { ?>
                        <div class="error"><?php echo $error_kayit; ?></div>
                    <?php }
                    ?>
                    <form method="post">
                    <?php if (isset($succes_mail)) { ?>
                        <div style="color: green;"><?php echo $succes_mail; ?></div>
                    <?php }
                    ?>
                    <form method="post">
                    <?php if (isset($error_email)) { ?>
                        <div class="error"><?php echo $error_email; ?></div>
                    <?php }
                    ?>
                </div>
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