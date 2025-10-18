<?php
/**
 * Email Notification System
 * Handles automated email notifications for library events
 */

class EmailNotification {
    private $smtp_host;
    private $smtp_port;
    private $smtp_username;
    private $smtp_password;
    private $from_email;
    private $from_name;
    
    public function __construct() {
        // Email configuration - should be moved to config file in production
        $this->smtp_host = 'smtp.gmail.com';
        $this->smtp_port = 587;
        $this->smtp_username = 'your-email@gmail.com'; // Configure with your email
        $this->smtp_password = 'your-app-password'; // Use app-specific password
        $this->from_email = 'noreply@librarysystem.com';
        $this->from_name = 'Digital Library System';
    }
    
    /**
     * Send email notification
     */
    public function sendEmail($to, $subject, $body, $isHTML = true) {
        $headers = [
            'From: ' . $this->from_name . ' <' . $this->from_email . '>',
            'Reply-To: ' . $this->from_email,
            'X-Mailer: PHP/' . phpversion()
        ];
        
        if ($isHTML) {
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=UTF-8';
        }
        
        $headers_string = implode("\r\n", $headers);
        
        return mail($to, $subject, $body, $headers_string);
    }
    
    /**
     * Send book due reminder
     */
    public function sendDueReminder($member_email, $member_name, $book_title, $due_date) {
        $subject = "Book Due Reminder - Digital Library System";
        $body = $this->getDueReminderTemplate($member_name, $book_title, $due_date);
        
        return $this->sendEmail($member_email, $subject, $body);
    }
    
    /**
     * Send overdue notice
     */
    public function sendOverdueNotice($member_email, $member_name, $book_title, $days_overdue) {
        $subject = "Overdue Book Notice - Digital Library System";
        $body = $this->getOverdueNoticeTemplate($member_name, $book_title, $days_overdue);
        
        return $this->sendEmail($member_email, $subject, $body);
    }
    
    /**
     * Send new member welcome email
     */
    public function sendWelcomeEmail($member_email, $member_name, $member_id) {
        $subject = "Welcome to Digital Library System";
        $body = $this->getWelcomeTemplate($member_name, $member_id);
        
        return $this->sendEmail($member_email, $subject, $body);
    }
    
    /**
     * Send book return confirmation
     */
    public function sendReturnConfirmation($member_email, $member_name, $book_title, $return_date) {
        $subject = "Book Return Confirmation - Digital Library System";
        $body = $this->getReturnConfirmationTemplate($member_name, $book_title, $return_date);
        
        return $this->sendEmail($member_email, $subject, $body);
    }
    
    /**
     * Get due reminder email template
     */
    private function getDueReminderTemplate($member_name, $book_title, $due_date) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f8fafc; }
                .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
                .alert { background: #fef3cd; border: 1px solid #fde68a; padding: 15px; border-radius: 5px; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>📚 Book Due Reminder</h2>
                </div>
                <div class='content'>
                    <p>Dear <strong>{$member_name}</strong>,</p>
                    
                    <div class='alert'>
                        <strong>Reminder:</strong> Your borrowed book is due soon!
                    </div>
                    
                    <p>This is a friendly reminder that your borrowed book:</p>
                    <ul>
                        <li><strong>Book:</strong> {$book_title}</li>
                        <li><strong>Due Date:</strong> {$due_date}</li>
                    </ul>
                    
                    <p>Please return the book on or before the due date to avoid any late fees.</p>
                    
                    <p>Thank you for using our Digital Library System!</p>
                </div>
                <div class='footer'>
                    <p>Digital Library Management System<br>
                    This is an automated message. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Get overdue notice email template
     */
    private function getOverdueNoticeTemplate($member_name, $book_title, $days_overdue) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f8fafc; }
                .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
                .alert { background: #fef2f2; border: 1px solid #fecaca; padding: 15px; border-radius: 5px; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>⚠️ Overdue Book Notice</h2>
                </div>
                <div class='content'>
                    <p>Dear <strong>{$member_name}</strong>,</p>
                    
                    <div class='alert'>
                        <strong>Important:</strong> You have an overdue book that needs to be returned immediately!
                    </div>
                    
                    <p>Your borrowed book is now overdue:</p>
                    <ul>
                        <li><strong>Book:</strong> {$book_title}</li>
                        <li><strong>Days Overdue:</strong> {$days_overdue} days</li>
                    </ul>
                    
                    <p><strong>Please return this book as soon as possible to avoid additional late fees.</strong></p>
                    
                    <p>If you have any questions, please contact the library staff.</p>
                </div>
                <div class='footer'>
                    <p>Digital Library Management System<br>
                    This is an automated message. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Get welcome email template
     */
    private function getWelcomeTemplate($member_name, $member_id) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #10b981; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f8fafc; }
                .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>🎉 Welcome to Digital Library System</h2>
                </div>
                <div class='content'>
                    <p>Dear <strong>{$member_name}</strong>,</p>
                    
                    <p>Welcome to our Digital Library Management System! Your account has been successfully created.</p>
                    
                    <p><strong>Your Member ID:</strong> {$member_id}</p>
                    
                    <p>You can now:</p>
                    <ul>
                        <li>Search and browse our book collection</li>
                        <li>Borrow books from the library</li>
                        <li>Track your borrowing history</li>
                        <li>Receive email notifications about due dates</li>
                    </ul>
                    
                    <p>Thank you for joining our library community!</p>
                </div>
                <div class='footer'>
                    <p>Digital Library Management System<br>
                    This is an automated message. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Get return confirmation email template
     */
    private function getReturnConfirmationTemplate($member_name, $book_title, $return_date) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #059669; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f8fafc; }
                .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>✅ Book Return Confirmation</h2>
                </div>
                <div class='content'>
                    <p>Dear <strong>{$member_name}</strong>,</p>
                    
                    <p>Thank you for returning your book to the library!</p>
                    
                    <p><strong>Return Details:</strong></p>
                    <ul>
                        <li><strong>Book:</strong> {$book_title}</li>
                        <li><strong>Return Date:</strong> {$return_date}</li>
                    </ul>
                    
                    <p>Your book has been successfully returned and your account is up to date.</p>
                    
                    <p>We hope you enjoyed the book and look forward to your next visit!</p>
                </div>
                <div class='footer'>
                    <p>Digital Library Management System<br>
                    This is an automated message. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}
?>
