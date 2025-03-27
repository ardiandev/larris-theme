<?php 

// Contact Form Shortcode
function reg_larris_contact_form()
{
    ob_start();

    // Display success message if the form was submitted successfully
    if (isset($_GET['success']) && $_GET['success'] == '1') {
        echo "<p style='color: green;'>Thank you! We will contact you back soon.</p>";
    }
?>
    <form id="ct-contact-form" class="ct-form" method="post">
        <ul class="ct-form-list">
            <li class="ct-form-item">
                <label for="name" class="ct-form-label">First Name</label>
                <input type="text" name="name" id="name" class="ct-form-input" required />
            </li>
            <li class="ct-form-item">
                <label for="subject" class="ct-form-label">Subject</label>
                <input type="text" name="subject" id="subject" class="ct-form-input" required />
            </li>
            <li class="ct-form-item">
                <label for="email" class="ct-form-label">Email</label>
                <input type="email" name="email" id="email" class="ct-form-input" required />
            </li>
            <li class="ct-form-item">
                <label for="message" class="ct-form-label">Message</label>
                <textarea id="message" name="message" class="ct-form-textarea" rows="7" required></textarea>
            </li>
        </ul>
        <input type="hidden" name="ct_form_submitted" value="1">
        <button type="submit" class="ct-form-button">Submit</button>
    </form>
<?php
    return ob_get_clean();
}

add_shortcode("larris_contact_form", "reg_larris_contact_form");

// Handle Form Submission
function ct_handle_form_submission()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ct_form_submitted'])) {

        // Sanitize input
        $name    = sanitize_text_field($_POST['name']);
        $subject = sanitize_text_field($_POST['subject']);
        $email   = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);

        // Validation
        if (empty($name) || empty($subject) || empty($email) || empty($message)) {
            echo "<p style='color: red;'>All fields are required.</p>";
            return;
        }

        if (!is_email($email)) {
            echo "<p style='color: red;'>Invalid email format.</p>";
            return;
        }

        // Email setup
        $to      = "ardianysp18@gmail.com"; // Admin email
        $headers = ['Content-Type: text/html; charset=UTF-8', 'From: ' . $email];
        $body    = "
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                        font-size: 1.2rem;
                    }

                    p {
                        font-size: 1.2rem;
            }
                    .email-container {
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        background-color: #f9f9f9;
                    }
                    .email-header {
                        text-align: center;
                        background-color: #0073aa;
                        color: #fff;
                        padding: 10px 0;
                        border-radius: 5px 5px 0 0;
                    }
                    .email-content {
                        padding: 20px;
                    }
                    .email-content p {
                        margin: 10px 0;
                    }
                    .email-footer {
                        text-align: center;
                        font-size: 12px;
                        color: #777;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='email-header'>
                        <h2>New Contact Form Submission</h2>
                    </div>
                    <div class='email-content'>
                        <p><strong>Name:</strong> $name</p>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Subject:</strong> $subject</p>
                        <p><strong>Message:</strong></p>
                        <p>$message</p>
                    </div>
                    <div class='email-footer'>
                         <p>This email was sent from <strong>" . get_bloginfo('name') . "</strong>.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        // Send mail
        if (wp_mail($to, "New Contact Form Submission: $subject", $body, $headers)) {
            // Redirect to the same page with a success query parameter
            wp_redirect(add_query_arg('success', '1', wp_get_referer()));
            exit;
        } else {
            echo "<p style='color: red;'>Mail sending failed.</p>";
        }
    }
}
add_action('wp', 'ct_handle_form_submission');


function ct_configure_smtp($phpmailer)
{
    // Retrieve SMTP settings from WordPress options
    $smtp_host       = get_option('ct_form_smtp_host', ''); // Default to empty if not set
    $smtp_port       = get_option('ct_form_smtp_port', 587); // Default to 587
    $smtp_username   = get_option('ct_form_smtp_username', '');
    $smtp_password   = get_option('ct_form_smtp_password', '');
    $smtp_encryption = get_option('ct_form_smtp_encryption', 'tls'); // Default to 'tls'
    $smtp_from       = get_bloginfo('admin_email'); // Default to admin email
    $smtp_from_name  = get_bloginfo('name'); // Default to site name

    $phpmailer->isSMTP();
    $phpmailer->Host       = $smtp_host;
    $phpmailer->SMTPAuth   = !empty($smtp_username) && !empty($smtp_password); // Enable auth if username and password are set
    $phpmailer->Port       = $smtp_port;
    $phpmailer->Username   = $smtp_username;
    $phpmailer->Password   = $smtp_password;
    $phpmailer->SMTPSecure = $smtp_encryption;
    $phpmailer->From       = $smtp_from;
    $phpmailer->FromName   = $smtp_from_name;
}
add_action('phpmailer_init', 'ct_configure_smtp');
