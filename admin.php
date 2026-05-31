<?php

session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }
include 'db.php';

if (isset($_GET['solve_id'])) {
    $id = intval($_GET['solve_id']);
    mysqli_query($conn, "UPDATE complaints SET status='Solved' WHERE id=$id");
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - University CMS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #060e1a; 
            min-height: 100vh;
            padding: 40px 20px;
            overflow-x: hidden;
            position: relative;
            color: #ffffff;
        }

        #ambient-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .dashboard-wrapper { max-width: 1200px; margin: 0 auto; z-index: 2; position: relative; }

        .header {
            background: rgba(12, 28, 48, 0.45);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 25px 35px;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h2 { font-size: 26px; font-weight: 700; background: linear-gradient(135deg, #34d399 0%, #22d3ee 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .header-links a { color: rgba(255, 255, 255, 0.6); text-decoration: none; font-weight: 500; margin-left: 25px; }
        .header-links a:hover { color: #22d3ee; }
        .header-links .logout { color: #f87171; }

        .table-container { background: rgba(12, 28, 48, 0.3); backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; overflow: hidden; box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6); }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 16px 20px; font-size: 15px; }
        th { background: rgba(16, 42, 73, 0.8); color: rgba(255, 255, 255, 0.8); font-weight: 600; border-bottom: 1px solid rgba(255, 255, 255, 0.1); text-transform: uppercase; font-size: 13px; letter-spacing: 1px; }
        tr { border-bottom: 1px solid rgba(255, 255, 255, 0.04); background: rgba(10, 22, 38, 0.3); }
        tr:hover { background: rgba(16, 42, 73, 0.4); }

        .prio-High { color: #f87171; font-weight: 600; }
        .prio-Medium { color: #fbbf24; font-weight: 500; }
        .prio-Low { color: #34d399; font-weight: 500; }

        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 13px; font-weight: 500; }
        .status-Pending { background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.2); }
        .status-Solved { background: rgba(52, 211, 153, 0.1); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.2); }

        .btn-solve { background: linear-gradient(135deg, #1c86ee 0%, #34d399 100%); color: white; padding: 6px 14px; text-decoration: none; border-radius: 8px; font-size: 13px; font-weight: 600; }
        .btn-solve:hover { background: linear-gradient(135deg, #1c86ee 0%, #22d3ee 100%); }
        .text-completed { color: rgba(255, 255, 255, 0.4); font-size: 13px; }
    </style>
</head>
<body>

<canvas id="ambient-canvas"></canvas>

<div class="dashboard-wrapper">
    <div class="header">
        <h2>Admin Dashboard</h2>
        <div class="header-links">
            <a href="home.php">🏠 Portal Home</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Category</th>
                <th>Priority</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM complaints ORDER BY created_at DESC");
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>#{$row['id']}</td>
                    <td><strong>{$row['user_id']}</strong></td>
                    <td>{$row['category']}</td>
                    <td class='prio-{$row['priority']}'>{$row['priority']}</td>
                    <td>{$row['subject']}</td>
                    <td><span class='status-badge status-{$row['status']}'>" . ($row['status'] == 'Pending' ? '⏳ Pending' : '✅ Solved') . "</span></td>
                    <td>";
                if($row['status'] == 'Pending') {
                    echo "<a href='admin.php?solve_id={$row['id']}' class='btn-solve'>Solve</a>";
                } else { 
                    echo "<span class='text-completed'>Completed</span>"; 
                }
                echo "</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<script>
    const canvas = document.getElementById('ambient-canvas');
    const ctx = canvas.getContext('2d');
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;
    const elements = [];
    const totalElements = 40;

    window.addEventListener('resize', () => { width = canvas.width = window.innerWidth; height = canvas.height = window.innerHeight; });

    class AdminPetal {
        constructor() { this.reset(); this.y = Math.random() * height; }
        reset() {
            this.x = Math.random() * width; this.y = height + 20; this.radius = Math.random() * 4 + 3;
            this.vx = Math.random() * 0.4 - 0.2; this.vy = -(Math.random() * 0.7 + 0.4);
            this.color = Math.random() > 0.5 ? 'rgba(52, 211, 153, 0.15)' : 'rgba(34, 211, 238, 0.15)';
        }
        update() { this.x += this.vx; this.y += this.vy; if (this.y < -20) this.reset(); }
        draw() { ctx.beginPath(); ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2); ctx.fillStyle = this.color; ctx.fill(); }
    }

    for (let i = 0; i < totalElements; i++) elements.push(new AdminPetal());
    function renderLoop() {
        ctx.clearRect(0, 0, width, height);
        elements.forEach(spark => { spark.update(); spark.draw(); });
        requestAnimationFrame(renderLoop);
    }
    renderLoop();
</script>
</body>
</html>
