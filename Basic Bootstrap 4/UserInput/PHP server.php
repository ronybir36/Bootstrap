<?php
// server.php

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Sanitize and get form data
    $name = htmlspecialchars(trim($_POST['username']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars(trim($_POST['password']));
    $gender = htmlspecialchars($_POST['gender']);
    $message = htmlspecialchars(trim($_POST['message']));
    $purpose = isset($_POST['purpose']) ? $_POST['purpose'] : '';
    $newsletter = isset($_POST['newsletter']) ? 'Yes' : 'No';

    $errors = [];

    // Basic validation
    if (empty($name)) $errors[] = "Name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
    if (empty($password)) $errors[] = "Password is required";

    // File upload handling
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        $filename = $_FILES['file']['name'];
        $filetmp = $_FILES['file']['tmp_name'];
        $filesize = $_FILES['file']['size'];
        $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($fileext, $allowed)) {
            $errors[] = "Invalid file type. Only JPG, PNG, PDF allowed";
        }
        if ($filesize > 3 * 1024 * 1024) { // 3MB
            $errors[] = "File too large. Max 3MB";
        }

        // Move file if no errors
        if (empty($errors)) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            move_uploaded_file($filetmp, $uploadDir . $filename);
        }
    }

    // Display errors or success
    if (!empty($errors)) {
        echo "<h3>Errors:</h3><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    } else {
        echo "<h3>Form Submitted Successfully!</h3>";
        echo "<p>Name: $name</p>";
        echo "<p>Email: $email</p>";
        echo "<p>Gender: $gender</p>";
        echo "<p>Message: $message</p>";
        echo "<p>Purpose: $purpose</p>";
        echo "<p>Newsletter: $newsletter</p>";
        if (isset($filename)) echo "<p>Uploaded File: $filename</p>";
    }
} else {
    echo "Please submit the form.";
}
?>
