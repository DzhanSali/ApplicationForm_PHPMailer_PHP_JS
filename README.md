# ApplicationForm_PHPMailer_PHP_JS
An HTML form for submitting user data to a specified email address using PHPMailer and PHP. 

Screenshots are attached displaying the functionality of the project.

First PHP project used to directly send user data as an HTML embedded in email using PHPMailer. 
Fields in the HTML form are validated on the server side (emailHandling.php) and if there are errors, an JS alert function is shown to the user with the error message telling them what the problem is and if they close it too quickly without reading, another page is echoed using PHP to display what the error is in red font.

Upon successful submission, the person whom is specified to receive the email (by the addAddress() function in PHPMailer) receives an email with the Subject consisting of the text "Applying for internship - " and the applicant's name, surname and their email address concatinated at the end for best overview of whom is contacting us. Currently the images are displaying Cyrillic text because this is the alphabet in which context the project was initiated in the first place.

As of 01/2024 I am encountering problems sending the image uploaded by the applicant in the form using JavaScript and their CV (encountering problems with attaching files to the email in general).

Tech stack and IDEs used during the development of this project:
- VS Code
- PHPMailer
- JavaScript
- HTML and CSS
- PHP Server extension in VS Code (brapifra.phpserver) // I did not use XAMPP
