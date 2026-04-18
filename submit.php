<?php
include 'db.php';
echo "<!DOCTYPE html><html lang='en'><head><style>
    body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100vh; display: flex; justify-content: center; align-items: center; margin: 0; }
    .card { background: white; padding: 40px; border-radius: 15px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
    .btn { display: inline-block; padding: 10px 20px; background: #764ba2; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
</style></head><body><div class='card'>";

if (isset($_POST['submit_complaint'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "INSERT INTO complaints (user_id, category, priority, subject, description) 
            VALUES ('$user_id', '$category', '$priority', '$subject', '$description')";

    if (mysqli_query($conn, $sql)) {
        echo "<h1>✅ Success</h1><p>Complaint registered successfully!</p>";
    } else {
        echo "<h1>❌ Error</h1><p>" . mysqli_error($conn) . "</p>";
    }
}
echo "<a href='index.php' class='btn'>Go Back</a></div></body></html>";
?>