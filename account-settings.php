<?php
session_start();

// Veritabanı bağlantısı için ayrı bir konfigürasyon dosyası
include_once("config.php");

if (!isset($_SESSION['username'])) {
    header("Location: login");
    exit();
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

$avatar_path = ""; // Avatar yolunu başlangıçta boş olarak ayarla
$new_username = ""; // Yeni kullanıcı adını başlangıçta boş olarak ayarla

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['file'])) {
        $errors = array();
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $file_ext = explode('.', $_FILES['file']['name']);
        $file_ext = strtolower(end($file_ext));
        $allowed_extensions = array("jpeg", "jpg", "png");

        if (in_array($file_ext, $allowed_extensions) === false) {
            $errors[] = "Uzantıya izin verilmiyor, lütfen JPEG veya PNG dosyası yükleyin.";
        }

        if ($file_size > 2097152) {
            $errors[] = 'Dosya boyutu 2 MB\'dan büyük olmamalıdır.';
        }

        if (empty($errors)) {
            if (move_uploaded_file($file_tmp, "uploads/" . $file_name)) {
                $avatar_path = $file_name; // Dosya adını sadece avatar_path olarak ayarla

                try {
                    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $dbusername, $dbpassword);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $pdo->prepare("UPDATE users SET avatar_path=:avatar_path WHERE username=:username");
                    $stmt->bindParam(':avatar_path', $avatar_path);
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        $success_message = "Profil bilgileriniz başarıyla güncellendi.";
                    } else {
                        $error_message = "Profil bilgileriniz güncellenirken bir hata oluştu. Lütfen tekrar deneyin.";
                    }
                } catch (PDOException $e) {
                    $error_message = "Veritabanı hatası: " . $e->getMessage();
                }
            } else {
                $error_message = "Avatar yüklenirken bir hata oluştu.";
            }
        } else {
            print_r($errors);
        }
    }

    if (isset($_POST['new_username'])) {
        $new_username = $_POST['new_username'];

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $dbusername, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("UPDATE users SET username=:new_username WHERE username=:username");
            $stmt->bindParam(':new_username', $new_username);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $success_message = "Profil bilgileriniz başarıyla güncellendi.";
            } else {
                $error_message = "Profil bilgileriniz güncellenirken bir hata oluştu. Lütfen tekrar deneyin.";
            }
        } catch (PDOException $e) {
            $error_message = "Veritabanı hatası: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Vallance E-Commerce</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/logo2.png" />
  </head>
  <div class="container-scroller">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="dashboard"><img src="assets/images/logo.svg" alt="logo" /></a>
          <a class="sidebar-brand brand-logo-mini" href="dashboard"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <ul class="nav">
          <li class="nav-item profile">
            <div class="profile-desc">
              <div class="profile-pic">
                <div class="count-indicator">
                  <img class="img-xs rounded-circle " src="assets/images/faces/icon.jpg" alt="">
                  <span class="count bg-success"></span>
                </div>
                <div class="profile-name">
                  <h5 class="mb-0 font-weight-normal"><?php echo $username ?></h5>
                </div>
              </div>
              <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
              <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
              <a href="account-settings" class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-settings text-primary"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="change-password" class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-onepassword  text-info"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="logout" class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-logout text-danger"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-icon bg-dark rounded-circle">Logout</p>
                  </div>
                </a>
              </div>
            </div>
          </li>
          <li class="nav-item nav-category">
            <span class="nav-link">Menü</span>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="dashboard">
              <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
              </span>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic-product" aria-expanded="false" aria-controls="ui-basic-product">
              <span class="menu-icon">
                <i class="mdi mdi-reproduction"></i>
              </span>
              <span class="menu-title">Product Search</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic-product">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="trendyol">Trendyol</a></li>
                <li class="nav-item"> <a class="nav-link" href="teknosa">Teknosa</a></li>
                <li class="nav-item"> <a class="nav-link" href="vatan">Vatan</a></li>
                <li class="nav-item"> <a class="nav-link" href="amazon">Amazon</a></li>
                <li class="nav-item"> <a class="nav-link" href="aliexpress">Aliexpress</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="pages/forms/basic_elements.html">
              <span class="menu-icon">
                <i class="mdi mdi-store"></i>
              </span>
              <span class="menu-title">Store İntegration</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic-seo" aria-expanded="false" aria-controls="ui-basic-seo">
              <span class="menu-icon">
                <i class="mdi mdi-google"></i>
              </span>
              <span class="menu-title">SEO</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic-seo">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="search-console">Search Console</a></li>
                <li class="nav-item"> <a class="nav-link" href="google-analytics">Google Analytics</a></li>
                <li class="nav-item"> <a class="nav-link" href="local-seo">Local Seo</a></li>
                <li class="nav-item"> <a class="nav-link" href="seo-amazon">Amazon</a></li>
                <li class="nav-item"> <a class="nav-link" href="seo-aliexpress">Aliexpress</a></li>
              </ul>
            </div>
          </li>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="dashboard"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav w-100">
              <li class="nav-item w-100">
                <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                  <input type="text" class="form-control" placeholder="Search products">
                </form>
              </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                  <div class="navbar-profile">
                    <img class="img-xs rounded-circle" src="assets/images/faces/icon.jpg" alt="">
                    <p class="mb-0 d-none d-sm-block navbar-profile-name"><?php echo $username ?></p>
                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                  <h6 class="p-3 mb-0">Profile</h6>
                  <div class="dropdown-divider"></div>
                  <a href="account-settings" class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-settings text-success"></i>
                      </div>
                    </div>
                    <div class="preview-item-content">
                      <p class="preview-subject mb-1">Settings</p>
                    </div>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="logout" class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-logout text-danger"></i>
                      </div>
                    </div>
                    <div class="preview-item-content">
                      <p class="preview-subject mb-1">Log out</p>
                    </div>
                  </a>
              </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-format-line-spacing"></span>
            </button>
          </div>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
          <div class="container">
        <h1>Account Settings</h1>

        <div class="section">
            <h2>Change Avatar</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="input-container">
                    <input type="file" name="file" id="avatar" accept="image/*">
                    <label for="avatar" class="custom-file-upload">Choose File</label>
                </div>
                <input type="submit" value="Upload" class="btn">
            </form>
        </div>

        <div class="section">
            <h2>Change Username</h2>
            <form action="" method="post">
                <div class="input-container">
                    <input type="text" name="new_username" id="new_username" placeholder="New Username">
                </div>
                <input type="submit" value="Change" class="btn">
            </form>
        </div>
    </div>
        <style>
            .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #696969;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1, h2 {
            color: #000; /* Siyah renk kodu */
        }

        h1 {
            text-align: center;
        }

        .section {
            margin-top: 20px;
        }

        h2 {
            font-size: 1.2em;
            
        }

        .input-container {
            margin-bottom: 15px;
        }

        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label.custom-file-upload {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
        }

        .btn:hover {
            background-color: #708090;
        }
           </style>
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © furkandincer.com 2023</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
            </div>
          </footer>
        </div>
      </div>
    </div>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <script src="assets/js/dashboard.js"></script>
  </body>
</html>