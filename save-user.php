<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to sanitize input data
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Retrieve and sanitize form data
    $full_name = sanitize_input($_POST["full_name"]);
    $national_id = sanitize_input($_POST["national_id"]);
    $dob = sanitize_input($_POST["dob"]);
    $sex = sanitize_input($_POST["sex"]);
    $address = sanitize_input($_POST["address"]);
    $country_code = sanitize_input($_POST["country_code"]);
    $phone = sanitize_input($_POST["phone"]);
    $email = sanitize_input($_POST["email"]);
    $occupation = sanitize_input($_POST["occupation"]);

    // Get user's IP address (from the form submission, though ideally should be server-side detected if critical)
    // For this example, we'll assume the client-side IP detection is sufficient for logging.
    // If you had a hidden field named 'user_ip' submitted with the form:
    // $user_ip = isset($_POST["user_ip"]) ? sanitize_input($_POST["user_ip"]) : "IP Not Provided by Client";
    // However, the HTML does not submit the IP, so we will get it from the server.
    $user_ip = $_SERVER['REMOTE_ADDR'];

    // Get current server timestamp
    $submission_time = date("Y-m-d H:i:s T");

    // Format data for storage
    $data_string = "--------------------------------------------------\n";
    $data_string .= "Submission Timestamp: " . $submission_time . "\n";
    $data_string .= "User IP Address: " . $user_ip . "\n";
    $data_string .= "Full Name: " . $full_name . "\n";
    $data_string .= "National ID: " . $national_id . "\n";
    $data_string .= "Date of Birth: " . $dob . "\n";
    $data_string .= "Sex: " . $sex . "\n";
    $data_string .= "Residential Address: " . $address . "\n";
    $data_string .= "Phone Number: " . $country_code . " " . $phone . "\n";
    $data_string .= "Email Address: " . $email . "\n";
    $data_string .= "Current Occupation: " . $occupation . "\n";
    $data_string .= "--------------------------------------------------\n\n";

    // Define the file path
    $file_path = "userdata.txt"; // Assumes userdata.txt is in the same directory as save-user.php

    // Open the file in append mode and write the data
    if (file_put_contents($file_path, $data_string, FILE_APPEND | LOCK_EX)) {
        // Redirect to a success page or display a success message
        // For simplicity, we'll just output a message.
        // In a real application, you'd likely redirect.
        echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Registration Successful</title>";
        echo "<style>body { font-family: Inter, sans-serif; background-color: #1e1f22; color: #d1d5db; display: flex; justify-content: center; align-items: center; min-height: 100vh; text-align: center; padding: 20px; } .message-container { background-color: #2a2c30; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); } h1 { color: #3b82f6; } p { font-size: 1.1em; }</style>";
        echo "</head><body><div class='message-container'><h1>Registration Successful!</h1><p>Thank you, " . htmlspecialchars($full_name) . ", your details have been saved.</p><p><a href='javascript:history.back()'>Go Back to Form</a></p></div></body></html>";
    } else {
        // Handle error: Unable to write to file
        echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Registration Failed</title>";
        echo "<style>body { font-family: Inter, sans-serif; background-color: #1e1f22; color: #d1d5db; display: flex; justify-content: center; align-items: center; min-height: 100vh; text-align: center; padding: 20px; } .message-container { background-color: #2a2c30; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); } h1 { color: #ef4444; } p { font-size: 1.1em; }</style>";
        echo "</head><body><div class='message-container'><h1>Registration Failed!</h1><p>Sorry, there was an error saving your details. Please try again later.</p><p>Error: Could not write to userdata.txt. Check file permissions.</p><p><a href='javascript:history.back()'>Go Back to Form</a></p></div></body></html>";
        // You might want to log this error to a separate error log file for admin review
        error_log("Failed to write to userdata.txt. Check directory permissions.");
    }

} else {
    // Not a POST request, redirect to form or show an error
    header("Location: index.html"); // Assuming your HTML form is named index.html
    exit;
}
?>
