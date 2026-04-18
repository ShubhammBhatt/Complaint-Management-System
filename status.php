<!DOCTYPE html>
<html>
<head>
    <title>Check Status</title>
    <style>
        body { font-family: sans-serif; background: #667eea; display: flex; justify-content: center; padding-top: 50px; }
        .status-box { background: white; padding: 30px; border-radius: 12px; width: 500px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        input { width: 70%; padding: 10px; }
        button { padding: 10px; background: #764ba2; color: white; border: none; cursor: pointer; }
        .record { border-bottom: 1px solid #eee; padding: 10px 0; }
    </style>
</head>
<body>

<div class="status-box">
    <h2>Check Your Complaint Status</h2>
    <form method="GET">
        <input type="number" name="search_id" placeholder="Enter your Student ID" required>
        <button type="submit">Search</button>
    </form>

    <?php
    if(isset($_GET['search_id'])) {
        include 'db.php';
        $search = mysqli_real_escape_string($conn, $_GET['search_id']);
        $result = mysqli_query($conn, "SELECT * FROM complaints WHERE user_id = '$search'");
        
        echo "<hr><h3>Your Results:</h3>";
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo "<div class='record'>
                        <strong>Subject:</strong> {$row['subject']} <br>
                        <strong>Status:</strong> " . ($row['status'] == 'Solved' ? '✅ Solved' : '⏳ Pending') . "
                      </div>";
            }
        } else {
            echo "<p>No complaints found for this ID.</p>";
        }
    }
    ?>
    <br><a href="index.php">Go Back</a>
</div>

</body>
</html>