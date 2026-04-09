<?php
// core/email_helper.php
// Handles automated order confirmation emails using Gmail SMTP.

// Include PHPMailer files
require_once __DIR__ . '/../libs/phpmailer/Exception.php';
require_once __DIR__ . '/../libs/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../libs/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * CONFIGURATION - Update these with your Gmail details
 */
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'yfake6280@gmail.com');
define('SMTP_PASS', 'zoboqwostydlmajk');
define('FROM_EMAIL', 'yfake6280@gmail.com');
define('FROM_NAME', 'Tiksha Furnishing');

function sendOrderConfirmationEmail($orderId, $customerEmail, $customerName, $totalAmount, $items, $shippingAddress) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        // Recipients
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($customerEmail, $customerName);

        // Content
        $mail->isHTML(false);
        $mail->Subject = "Order Confirmation - #{$orderId} | Tiksha Furnishing";
        
        // Construct Plain Text Email Content
        $itemsText = "";
        foreach ($items as $item) {
            $itemsText .= "- {$item['product_name']} (Qty: {$item['quantity']}) : ₹" . number_format($item['line_total']) . "\n";
        }

        $emailBody = "TIKSHA FURNISHING\n";
        $emailBody .= "Order Confirmation\n";
        $emailBody .= str_repeat("-", 30) . "\n\n";
        $emailBody .= "Hello {$customerName},\n\n";
        $emailBody .= "Thank you for your order! We have received your order #{$orderId} and it is being processed.\n\n";
        $emailBody .= "Order Summary:\n";
        $emailBody .= $itemsText;
        $emailBody .= "Total Amount: ₹" . number_format($totalAmount) . "\n\n";
        $emailBody .= "Shipping Address:\n";
        $emailBody .= $shippingAddress . "\n\n";
        $emailBody .= "If you have any questions, please contact us at support@tikshafurnishing.com\n\n";
        $emailBody .= "Thank you for choosing Tiksha Furnishing.\n";
        $emailBody .= "© " . date('Y') . " Tiksha Furnishing. All rights reserved.";

        $mail->Body = $emailBody;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed. Error: {$mail->ErrorInfo}");
        return false;
    }
}
