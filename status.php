<?php 
include 'db.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Complaint Status - University CMS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #060e1a; 
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
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

        .container {
            background: rgba(12, 28, 48, 0.45);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 40px;
            width: 100%;
            max-width: 520px;
            border-radius: 24px;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.8), 
                        inset 0 1px 2px rgba(255, 255, 255, 0.1);
            z-index: 2;
            position: relative;
        }

        h2 {
            text-align: center;
            color: #ffffff;
            font-size: 30px;
            margin-bottom: 25px;
            font-weight: 700;
            background: linear-gradient(135deg, #34d399 0%, #22d3ee 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 15px;
        }

        label {
            font-weight: 500;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            display: block;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 20px;
            background: rgba(10, 22, 38, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            color: #ffffff;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #22d3ee;
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.2);
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1c86ee 0%, #34d399 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: linear-gradient(135deg, #1c86ee 0%, #22d3ee 100%);
        }

        .result-box {
            margin-top: 25px;
            padding: 20px;
            background: rgba(10, 22, 38, 0.8);
            border-radius: 14px;
            border-left: 4px solid #34d399;
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            line-height: 1.6;
        }

        .result-box.pending { border-left-color: #fbbf24; }

        .nav-links {
            text-align: center;
            margin-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 20px;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            margin: 0 12px;
            font-weight: 500;
        }

        .nav-links a:hover { color: #22d3ee; }
        .footer-text { text-align: center; font-size: 11px; color: rgba(52, 211, 153, 0.35); margin-top: 25px; font-weight: 600; }
    </style>
</head>
<body>

<canvas id="ambient-canvas"></canvas>

<div class="container">
    <h2>Track Status</h2>
    <form method="POST">
        <label>Enter Student ID</label>
        <input type="number" name="search_id" placeholder="e.g. 101" required>
        <button type="submit" name="check_status">Search Records</button>
    </form>

    <?php
    if (isset($_POST['check_status'])) {
        $search_id = mysqli_real_escape_string($conn, $_POST['search_id']);
        $result = mysqli_query($conn, "SELECT * FROM complaints WHERE user_id='$search_id' ORDER BY created_at DESC");
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $statusClass = ($row['status'] == 'Pending') ? 'pending' : '';
                $statusEmoji = ($row['status'] == 'Pending') ? '⏳' : '✅';
                echo "<div class='result-box $statusClass'>
                        <strong>Subject:</strong> {$row['subject']}<br>
                        <strong>Category:</strong> {$row['category']}<br>
                        <strong>Priority:</strong> {$row['priority']}<br>
                        <strong>Status:</strong> $statusEmoji {$row['status']}
                      </div>";
            }
        } else {
            echo "<div class='result-box pending' style='border-left-color: #ef4444;'>No active instances found for this ID.</div>";
        }
    }
    ?>

    <div class="nav-links">
        <a href="home.php">🏠 Home</a> | 
        <a href="index.php">✍️ File Complaint</a>
    </div>
</div>

<p class="footer-text">UNIVERSITY PORTAL &copy; 2026</p>

<script>
    const canvas = document.getElementById('ambient-canvas');
    const ctx = canvas.getContext('2d');
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;
    const elements = [];
    const totalElements = 30;
    const mouse = { x: null, y: null, activeRadius: 150 };

    window.addEventListener('mousemove', (e) => { mouse.x = e.clientX; mouse.y = e.clientY; });
    window.addEventListener('mouseout', () => { mouse.x = null; mouse.y = null; });
    window.addEventListener('resize', () => { width = canvas.width = window.innerWidth; height = canvas.height = window.innerHeight; });

    class FloatingSpark {
        constructor() { this.reset(); this.y = Math.random() * height; }
        reset() {
            this.x = Math.random() * width; this.y = height + 20; this.radius = Math.random() * 4 + 3;
            this.vx = Math.random() * 0.4 - 0.2; this.vy = -(Math.random() * 0.7 + 0.4);
            this.color = Math.random() > 0.5 ? 'rgba(52, 211, 153, 0.2)' : 'rgba(34, 211, 238, 0.2)';
        }
        update() {
            this.x += this.vx; this.y += this.vy;
            if (this.y < -20) this.reset();
            if (mouse.x !== null) {
                let dx = mouse.x - this.x; let dy = mouse.y - this.y; let dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < mouse.activeRadius) {
                    let forceFactor = (mouse.activeRadius - dist) / mouse.activeRadius;
                    this.x -= (dx / dist) * forceFactor * 4; this.y -= (dy / dist) * forceFactor * 4;
                }
            }
        }
        draw() { ctx.beginPath(); ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2); ctx.fillStyle = this.color; ctx.fill(); }
    }

    for (let i = 0; i < totalElements; i++) elements.push(new FloatingSpark());
    function renderLoop() {
        ctx.clearRect(0, 0, width, height);
        elements.forEach(spark => { spark.update(); spark.draw(); });
        requestAnimationFrame(renderLoop);
    }
    renderLoop();
</script>
</body>
</html>
