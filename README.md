# Vallance E-Commerce
Vallance E-Commerce, e-ticaret platformlarından ürün verilerini çekmek ve analiz etmek için kullanılan açık kaynaklı bir web uygulamasıdır. Bu projede, Trendyol, Vatan ve Teknosa gibi popüler e-ticaret mağazalarından ürün isimleri, fiyatlar ve diğer ilgili veriler çekilmekte ve kullanıcılar için analiz edilmektedir.

# Proje Açıklaması
Vallance E-Commerce, çeşitli e-ticaret platformlarından veri çekmek ve bu verileri kullanıcı dostu bir şekilde sunmak için tasarlanmış bir web uygulamasıdır. Kullanıcılar, belirledikleri ürünlerin fiyatlarını karşılaştırabilir, en iyi fırsatları bulabilir ve online alışveriş deneyimlerini iyileştirebilirler.

# Kullanılan Teknolojiler
Bu projede kullanılan temel teknolojiler şunlardır:
Backend: PHP ve Node.js
Veritabanı: MySQL
Frontend: HTML, CSS ve JavaScript
Veri Çekme: Web scraping
Özellikler
Trendyol, Vatan ve Teknosa gibi mağazalardan ürün verilerini çekme yeteneği.
Ürün fiyatlarını karşılaştırma ve en iyi fırsatları bulma.
Kullanıcı dostu ve kullanımı kolay arayüz.
Nasıl Kullanılır
Proje hakkında daha fazla bilgi edinmek ve kullanmaya başlamak için Dokümantasyon sayfasını ziyaret edebilirsiniz. 



# Katkılar
Bu projeye katkıda bulunmak isterseniz, lütfen Kat CONTRIBUTING.md dosyasını okuyun. Katkılarınızı memnuniyetle karşılıyoruz.

# Lisans
Bu proje, GNU Genel Kamu Lisansı sürüm 3.0 (GPL-3.0) altında lisanslanmıştır. Daha fazla bilgi için [GPL-3.0](link) dosyasını okuyun.

# İletişim
Projeyle ilgili herhangi bir soru veya geri bildiriminiz varsa, lütfen hi@furkandincer.com adresinden bizimle iletişime geçin.


# Installation

XAMPP Installation
XAMPP is a package that includes PHP, MySQL, Apache and other web development tools. To install XAMPP, you can follow these steps:

Go to the XAMPP Download Page and download the appropriate XAMPP version for your operating system.

Install XAMPP by running the file you downloaded. During installation, you do not need to install unnecessary components, just select the required components.

Once the installation is complete, launch the XAMPP control panel and start services such as Apache and MySQL.

Open your browser and access the XAMPP control panel by going to "http://localhost".

### Install PHPMailer 

PHPMailer is a library for sending emails with PHP. To install PHPMailer, you can follow these steps:

Download PHPMailer from GitHub or install it with Composer:

GitHub Download (Manual): Go to PHPMailer GitHub Repository, download the project and extract the files into a folder of your project.

Installing with Composer (Recommended): If you are using Composer, open a command prompt in the root of your project and install PHPMailer by running the following command:

```bash
  composer require phpmailer/phpmailer

```

To start using PHPMailer in your PHP file, add the use statement as follows:
```bash
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
```

You can start using PHPMailer. An example email sending code:
```bash
$mail = new PHPMailer(true); // Create PHPMailer object
try {
    // Settings
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_username';
    $mail->Password = 'your_password';
    // Adjust other settings...
    // Send Mail
    $mail->send();
} catch (Exception $e) {
    echo "Mail Send Error: {$mail->ErrorInfo}";
}
```

## Install Compuser

Composer Installation
Composer is a dependency manager used to manage PHP packages. To install Composer, you can follow these steps:

Go to the Composer Download Page and download the Composer download file for your operating system.

Install Composer by running the file you downloaded. During installation, you can choose the option to make Composer accessible to all users.

Once the installation is complete, open the command prompt and verify that Composer has been successfully installed by running the composer --version command.
