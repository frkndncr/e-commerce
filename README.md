# Vallance E-Commerce
Vallance E-Commerce is an open source web application for looking at and analyzing product lines from e-commerce platforms. In this project, product names, prices and other relevant data are retrieved from popular e-commerce stores such as Trendyol, Vatan and Teknosa and analyzed for users.

# Project Description
Vallance E-Commerce is a web application designed to pull data from various e-commerce platforms and present this data in a user-friendly way. Users can compare prices of their selected products, identify the best markets, and improve their online shopping experience.

# Used technologies
The main technologies used in this project are:
Backend: PHP and Node.js
Database: MySQL
Front End: HTML, CSS and JavaScript
Data Extraction: Web scraping
Features
Ability not to miss products from stores such as Trendyol, Vatan and Teknosa.
Compare product prices and find the best deals.
User-friendly and easy-to-use distribution.
How to use
You can visit the Documentation to learn more about the project and get started.

# Things To Do 
Trendyol, Vatan, Teknosa stores are ready, but Amazon, Aliexpress are not ready, nothing in the SEO category is ready, only the pages are ready, nothing has been done by the backend, these are the missing things, apart from that, everything is ok, you can improve them if you wish. 
06.10.2023

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


# Screenshots

### Dashhboard
![App Screenshot](https://i.hizliresim.com/4mw1hcp.png)

### Product Search Page

![App Screenshot](https://i.hizliresim.com/p88aeq7.png)


# Contributions
If you would like to contribute to this project, please read Kat CONTRIBUTING.md. We welcome your contributions.

# Licence
This project is licensed under the GNU General Public License version 3.0 (GPL-3.0). For more information, read [GPL-3.0](link).

# Communication
If you have any questions or feedback about the project, please contact us at hi@furkandincer.com.
