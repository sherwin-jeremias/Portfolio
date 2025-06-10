<?php
header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'An error occurred. Please try again.'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Oops! Please complete the form and try again.";
        echo json_encode($response);
        exit;
    }

    // Set the recipient email address.
    // Replace with your actual email address.
    $recipient = "your-email@example.com";

    // Set the email subject.
    $subject = "New contact from $name via your Portfolio";

    // Build the email content.
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Send the email.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        $response['success'] = true;
        $response['message'] = "Thank you! Your message has been sent.";
    } else {
        $response['message'] = "Oops! Something went wrong and we couldn't send your message.";
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    $response['message'] = "There was a problem with your submission, please try again.";
}

echo json_encode($response);
?>