<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'C:\php-8.3.1\PHPMailer-master\src\Exception.php';
require 'C:\php-8.3.1\PHPMailer-master\src\PHPMailer.php';
require 'C:\php-8.3.1\PHPMailer-master\src\SMTP.php';

// Works if I specify the folder path and image name explicitly and use addAttachment
/* $target_dir = 'C:\\Users\\...';
$image_name = 's.jpg';
$image_path = $target_dir . DIRECTORY_SEPARATOR . $image_name;
$mail->addAttachment($image_path, $image_name); */

// Does not work
/* $target_dir = 'C:\\Users\\...';
$target_file = $target_dir . basename($_FILES["picID"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); */

$mail = new PHPMailer(true);

//Server settings
$mail->isSMTP();                              //Send using SMTP
$mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
$mail->SMTPAuth   = true;             //Enable SMTP authentication
$mail->Username   = 'example@gmail.com';   //SMTP write your email
$mail->Password   = 'xxxxxxxxxxxxxx';      //SMTP applications password generated from Google account
$mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
$mail->Port       = 465;

//Recipients
$mail->setFrom($_POST['email']);
$mail->addAddress('example@gmail.com');     //Add a recipient email  
//$mail->addReplyTo($_POST["email"], $_POST["name"]); // reply to sender email
$mail->addReplyTo("example@gmail.com");

//Content
$mail->isHTML(true);    //Set email format to HTML
$mail->CharSet = 'UTF-8';
// Translation: "Applying for internship" + name, surname and email
$mail->Subject = "Кандидатстване за стаж - {$_POST['name']} {$_POST['surname']} ({$_POST['email']})";


// Didn't work either
//$picture = base64_decode($_POST['picID']);
//$picture = base64_decode($_POST['preview']);
//$mail->AddEmbeddedImage($target_file, 'photo');

// I defined an array of the most frequently used email domains to use 
// later on to check with a RegEx if the email provided by user is valid and 
// mathes one of these domains and handle errors if not.
$mail_domains_array =   [ '@gmail.com', '@abv.bg', '@yahoo.com', '@outlook.com', '@live.com' ];

// This is the pattern which will be used for RegEx with preg_match() to check validation
$pattern = '/(' . implode('|', array_map('preg_quote', $mail_domains_array)) . ')$/i';

$fb_pattern = '/^https:\/\/www\.facebook\.com\//i';

// Formating the date to be shown as DD-MM-YYYY since it's most acceptable in my country and easier to the eyes
$dateOfBirthFromForm = $_POST['dateOfBirth'];
$dateObject = DateTime::createFromFormat('Y-m-d', $dateOfBirthFromForm);
$formattedDate = $dateObject->format('d/m/Y');

// Checking if the applicant is at least 18 years old
// Getting the year from the applicant's birth date
$year = $dateObject->format('Y');
// Get the current year
$currentYear = date('Y');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ((empty($_POST['name']) || strlen($_POST['name']) < 3) || (empty($_POST['surname']) || strlen($_POST['surname'] < 3))) {
        
        echo "<script>
                alert('Името и фамилията са прекалено къси'); 
            </script>";
       
            echo '<div style="text-align: center; color: red; font-size: 24px; margin-top: 50vh; transform: translateY(-50%);">
            Името и фамилията са прекалено къси!
            Върни се назад и попълни верни данни!
          </div>';
    
                
    } else 
    if (strlen($_POST['phone_number']) < 10) {
        echo "<script>
                    alert('Телефонният номер е прекалено къс!');
                </script>";

                echo '<div style="text-align: center; color: red; font-size: 24px; margin-top: 50vh; transform: translateY(-50%);">
                Телефонният номер е прекалено къс!
                Върни се назад и попълни верни данни!
          </div>';
    } 
    else
    if (!preg_match($pattern, $_POST['email'])) {
        echo "<script>
                    alert('Имейлът е невалиден!');
                </script>";

                echo '<div style="text-align: center; color: red; font-size: 24px; margin-top: 50vh; transform: translateY(-50%);">
                Имейлът е невалиден!
                Върни се назад и попълни верни данни!
          </div>';
    }
    else
    if (!preg_match($fb_pattern, $_POST['fb_link'])) {
        echo "<script>
                    alert('Фейсбук линкът е невалиден!');
                </script>";

                echo '<div style="text-align: center; color: red; font-size: 24px; margin-top: 50vh; transform: translateY(-50%);">
                Фейсбук линкът е невалиден!
                Върни се назад и попълни верни данни!
          </div>';
    }
    else
    if (!($year <= ($currentYear - 18)))
    {
        echo "<script>
                    alert('Трябва да сте пълнолетен, за да кандидатствате!');
                </script>";

                echo '<div style="text-align: center; color: red; font-size: 24px; margin-top: 50vh; transform: translateY(-50%);">
                Трябва да сте пълнолетен, за да кандидатствате! <br>
                Понастоящем не може да кандидатствате за стаж при нас! <br>
                Година, която сте въвели: ' . $year . "<br> Настоящата година е: " . $currentYear . '
                    </div>';
    }
    else {

        $mail->Body = '
                    <html>
                    <head>
                    <style>

                        .img_div{
                            margin-right: 20px;
                            width: 350px;
                            border-style: dashed;
                            border-color: cadetblue;
                            height: 330px;
                            display: inline-grid;
                        }

                        .pictureHolder img {
                            object-fit: cover;
                            width: 350px;
                            height: 330px;
                        }

                        .main_sect{
                            width: 350px;
                            align-self: flex-end;
                        }

                        .form_holder{
                            margin-bottom: 40px;
                            padding-left: 150px;
                            padding-right: 150px;
                            display: flex;
                            justify-content: center;
                        }

                        .el_holder{
                            display: flex; 
                            justify-content: center;
                            margin-top: 30px;
                            box-shadow: 0px 1px 6px 4px #bdbdbdbf;;
                            padding-top: 50px;
                            padding-bottom: 50px;
                            width: 800px;
                            border-radius: 6px;
                            height: 380px;
                        }

                        .inputs{
                            width: 100%;
                            height: 20px;
                            text-align: center;
                            margin-bottom: 10px;
                            border-color: #8ab6ff;
                            border-top: 0px;
                            border-right: 2px;
                            border-left: 0px;
                        }

                        .sect3{
                            display: flex;
                            justify-content: center;
                            margin-bottom: 10px;
                        }

                        .sect5{
                            margin-top: 10px;
                        }
                    </style>
                    </head>
                    <body>
                        <div class="form_holder">
                                <div class="el_holder">
                                    <div class="img_div">
                                        <div class="pictureHolder">
                                            <img id="preview" scr="cid:photo" alt="Applicant Photo">
                                        </div>
                                    </div>
                                    
                                    <div class="main_sect">
                                        <div class="sect1" style="margin-top: 15px;">
                                            <div>
                                                <input class="inputs" value=" ' . $_POST['name'] . ' " type="text" readonly>
                                            </div>
                                            <div>
                                                <input class="inputs" value=" ' . $_POST['surname'] . ' " type="text" readonly>
                                            </div>
                                        </div>
                                        <div class="sect2">
                                            <div>
                                                <input class="inputs" value=" ' . $_POST['phone_number'] . ' " type="text" readonly>
                                            </div>
                                            <div>
                                                <input class="inputs" value=" ' . $_POST['email'] . ' " type="email" readonly>
                                            </div>
                                        </div>
                                        <div class="sect4">
                                            <div>
                                                <input class="inputs" value=" ' . $_POST['fb_link'] . ' " type="text" readonly>
                                            </div>
                                            <div>
                                                <input class="inputs" value=" ' . $formattedDate . ' " readonly>
                                            </div>
                                        </div>
                                        <div class="sect3">
                                            <div>
                                                <input type="radio" checked> ' . $_POST['gender'] . '
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </body>
                        </html>
                    ';

        $mail->send();

        echo "<script>alert('Успешно изпращане!'); document.location.href = 'application.html';</script>";
    }
} else {
    echo "<script>alert('Грешно попълнени данни! Опитай пак!'); document.location.href = 'application.html';</script>";
}
?>