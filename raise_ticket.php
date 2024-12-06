<?php
// Include PHPMailer classes (adjust paths as needed)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Database configuration
$host = 'localhost';
$dbname = 'u221875567_ticket_isuues';
$username = 'u221875567_Prayatna_it';
$password = 'Prayatna1@';

// Admin email
$adminEmail = 'pshaurya983@gmail.com';

// Sender email credentials for SMTP
$senderEmail = 'it_officer@giveyourissuesofit.org.in';
$senderPassword = 'ITofficer123@';
$smtpServer = 'smtp.hostinger.com';
$smtpPort = 465; // Adjust if needed

// Retrieve POST data
$name = isset($_POST['name']) ? trim($_POST['name']) : null;
$designation = isset($_POST['designation']) ? trim($_POST['designation']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : null;
$issue = isset($_POST['issue']) ? trim($_POST['issue']) : null;
$attachment = isset($_FILES['file']) ? $_FILES['file'] : null;

// Validate required fields
if (empty($name) || empty($designation) || empty($email) || empty($mobile) || empty($issue)) {
    die("Error: All required fields must be filled.");
}

// Generate a unique ticket ID
$ticket_id = uniqid('ticket_');

// Handle the file upload (if provided)
$uploadedFilePath = null;
if ($attachment && $attachment['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'issue_images/' . $email; // Directory based on user email
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            die("Error: Could not create directory for file upload.");
        }
    }

    $uploadedFilePath = $uploadDir . '/' . basename($attachment['name']);
    if (!move_uploaded_file($attachment['tmp_name'], $uploadedFilePath)) {
        die("Error: File upload failed.");
    }
} else {
    if ($attachment && $attachment['error'] !== UPLOAD_ERR_NO_FILE) {
        die("Error: A file upload error occurred. Code: " . $attachment['error']);
    }
}

// Save the ticket to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO tickets (id, name, designation, email, mobile, issue, attachment) 
                           VALUES (:id, :name, :designation, :email, :mobile, :issue, :attachment)");
    $stmt->bindParam(':id', $ticket_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':designation', $designation);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mobile', $mobile);
    $stmt->bindParam(':issue', $issue);
    $stmt->bindParam(':attachment', $uploadedFilePath);
    $stmt->execute();
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// Send confirmation email to the user with proper HTML structure
$userMessage = "
<p>Thank you for your request. Your unique ticket code is 
<span style=\"color: red; font-weight: bold;\">$ticket_id</span></p>
<p>Please use it to track your issue. We will resolve it as soon as possible.</p>
";

sendEmail($email, "Ticket Submission Confirmation", $userMessage);

// Notify the admin about the new ticket (with attachment if available)
$adminMessage = "
<p>A new IT issue has been submitted:</p>
<p><strong>Name:</strong> $name<br>
<strong>Email:</strong> $email<br>
<strong>Designation:</strong> $designation<br>
<strong>Mobile:</strong> $mobile<br>
<strong>Issue Description:</strong> $issue<br>
<strong>Ticket ID:</strong> $ticket_id</p>
";

sendEmail($adminEmail, "New IT Issue Submitted", $adminMessage, $uploadedFilePath);

// Redirect to success page
header("Location: success-ticket.php");
exit();

/**
 * Function to send an email.
 */
function sendEmail($recipientEmail, $subject, $body, $fileAttachment = null) {
    global $senderEmail, $senderPassword, $smtpServer, $smtpPort;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $smtpServer;
        $mail->SMTPAuth = true;
        $mail->Username = $senderEmail;
        $mail->Password = $senderPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $smtpPort;

        $mail->setFrom($senderEmail, 'IT Support');
        $mail->addAddress($recipientEmail);
        $mail->Subject = $subject;

        // Enable HTML for the message body
        $mail->isHTML(true);
        $mail->Body = $body;

        if ($fileAttachment && file_exists($fileAttachment)) {
            $fileData = file_get_contents($fileAttachment);
            if ($fileData !== false) {
                $mail->addStringAttachment($fileData, basename($fileAttachment));
            }
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log error if needed
        return false;
    }
}