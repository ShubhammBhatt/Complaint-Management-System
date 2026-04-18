<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }
include 'db.php';

if (isset($_GET['solve_id'])) {
    $id = $_GET['solve_id'];
    mysqli_query($conn, "UPDATE complaints SET status='Solved' WHERE id=$id");
    header("Location: admin.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #333; color: white; }
        .prio-High { color: red; font-weight: bold; }
        .prio-Medium { color: orange; }
        .prio-Low { color: green; }
        .btn-solve { background: #28a745; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
        .logout { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
<div class="header">
    <h2>Complaint Management Dashboard</h2>
    <a href="logout.php" class="logout">Logout</a>
</div>
<table>
    <tr>
        <th>ID</th><th>User ID</th><th>Category</th><th>Priority</th><th>Subject</th><th>Status</th><th>Action</th>
    </tr>
    <?php
    $result = mysqli_query($conn, "SELECT * FROM complaints ORDER BY created_at DESC");
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['user_id']}</td>
            <td>{$row['category']}</td>
            <td class='prio-{$row['priority']}'>{$row['priority']}</td>
            <td>{$row['subject']}</td>
            <td>" . ($row['status'] == 'Pending' ? '⏳ Pending' : '✅ Solved') . "</td>
            <td>";
        if($row['status'] == 'Pending') {
            echo "<a href='admin.php?solve_id={$row['id']}' class='btn-solve'>Solve</a>";
        } else { echo "Completed"; }
        echo "</td></tr>";
    }
    ?>
</table>
</body>
</html>