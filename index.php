<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Complaint Portal</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100vh; display: flex; justify-content: center; align-items: center; }
        .container { background: #fff; padding: 30px; width: 450px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        h2 { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #764ba2; padding-bottom: 10px; }
        label { font-weight: 600; font-size: 13px; color: #555; }
        input, textarea, select { width: 100%; padding: 10px; margin: 5px 0 15px 0; border: 1px solid #ddd; border-radius: 8px; }
        button { width: 100%; padding: 12px; background: #764ba2; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
        .nav-links { text-align: center; margin-top: 15px; font-size: 14px; }
        .nav-links a { color: #764ba2; text-decoration: none; margin: 0 10px; }
    </style>
</head>
<body>
<div class="container">
    <h2>University CMS</h2>
    <form action="submit.php" method="POST">
        <label>Student ID</label>
        <input type="number" name="user_id" placeholder="Enter your ID" required>
        
        <label>Issue Category</label>
        <select name="category">
            <option value="Hostel">🏠 Hostel</option>
            <option value="WiFi">🌐 WiFi / Network</option>
            <option value="Academic">📚 Academic</option>
            <option value="Canteen">🍔 Canteen</option>
        </select>

        <label>Priority Level</label>
        <select name="priority">
            <option value="Low">🟢 Low</option>
            <option value="Medium" selected>🟡 Medium</option>
            <option value="High">🔴 High</option>
        </select>

        <label>Subject</label>
        <input type="text" name="subject" placeholder="Short title" required>
        
        <label>Description</label>
        <textarea name="description" placeholder="Details..." rows="3" required></textarea>
        
        <button type="submit" name="submit_complaint">Submit Complaint</button>
    </form>
    <div class="nav-links">
        <a href="status.php">Check Status</a> | <a href="login.php">Admin Login</a>
    </div>
</div>
</body>
</html>