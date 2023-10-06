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
    $teknosaproduct = $_POST["teknosaproduct"];
    $liste = [];
    $linkler = [];

    // Boşluğu URL uygun hale getir
    $teknosaproduct = urlencode($teknosaproduct);

    $url = "https://www.teknosa.com/arama/?s=" . $teknosaproduct;
    $response = file_get_contents($url);
    $html_icerigi = $response;

    // HTML içeriğini işlemek için bir HTML ayrıştırıcı kullanabilirsiniz.
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($html_icerigi);
    $finder = new DomXPath($doc);
    $classname = "prd-block2";
    $prices = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    $names = $doc->getElementsByTagName('h3');
    $links = $doc->getElementsByTagName('a');

    // Ürün linklerini çekmek için belirli bir class'a sahip <a> elementlerini bulun
    $linkClassName = "findGowitIdPlp prd-link gowitPlp-js";

    $baseURL = "https://www.teknosa.com";

    foreach ($links as $linkElement) {
        if (strpos($linkElement->getAttribute("class"), $linkClassName) !== false) {
            $relativeLink = $linkElement->getAttribute("href");
    
            // Eğer URL başında "http://" veya "https://" yoksa, temel URL ile birleştirin
            if (strpos($relativeLink, "http://") !== 0 && strpos($relativeLink, "https://") !== 0) {
                $absoluteLink = $baseURL . $relativeLink;
            } else {
                $absoluteLink = $relativeLink;
            }
    
            $linkler[] = $absoluteLink;
        }
    }

    for ($i = 0; $i < $prices->length; $i++) {
        $liste[] = array(
            "Ürün İsmi" => $names->item($i)->textContent,
            "Fiyat" => $prices->item($i)->textContent,
            "Link" => isset($linkler[$i]) ? $linkler[$i] : ""
        );
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
  <body>
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
                <li class="nav-item"> <a class="nav-link" href="seo-robots">Robots Txt</a></li>
                <li class="nav-item"> <a class="nav-link" href="seo-sitemap">Sitemap</a></li>
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
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">Teknosa Product Search</h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Teknosa</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Product Search</li>
                </ol>
              </nav>
            </div>
            <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Product Search</h4>
                    <p class="card-description">Type the product you want to search </p>
                    <form class="forms-sample" method="POST" action="">
                        <div class="form-group">
                            <label for="exampleInputUsername1">Product</label>
                            <input type="text" name="teknosaproduct" class="form-control" id="exampleInputUsername1" placeholder="Product Name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPages">Number of Pages to Scrape</label>
                            <input type="number" name="pages" class="form-control" id="exampleInputPages" placeholder="Number of Pages">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    </form>
                </div>
              </div>
          </div>
      </div>
    <div style="display: flex; flex-direction: row; align-items: center; margin-bottom: 20px;">
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Products</h4>
            <p class="card-description">Export<code></code></p>
            <div class="table-responsive">
            <table id="myDataTable" class="table table-dark">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($liste)) {
                        foreach ($liste as $i => $item) {
                            echo "<tr>";
                            echo "<td>" . ($i + 1) . "</td>";
                            echo "<td>" . $item["Ürün İsmi"] . "</td>";
                            echo "<td>" . $item["Fiyat"] . "</td>";
                            echo "<td><a href='" . $item["Link"] . "' target='_blank'>Link</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Ürün bulunamadı.</td></tr>";
                    }
                    ?>
            </table>
            </div>
        </div>
    </div>
</div>
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