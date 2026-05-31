<?php
/* Project: University CMS - Admin Login
  Author: Shubham Bhatt
  BCA Lab Assignment - 2026
*/
session_start();
if (isset($_POST['login'])) {
    if ($_POST['user'] == "admin" && $_POST['pass'] == "admin123") {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else { $error = "Invalid Login!"; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - University CMS</title>
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
            overflow: hidden;
            position: relative;
        }

        #ambient-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .login-card {
            background: rgba(12, 28, 48, 0.45);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 45px 35px;
            width: 100%;
            max-width: 400px;
            border-radius: 24px;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.8), 
                        inset 0 1px 2px rgba(255, 255, 255, 0.1);
            text-align: center;
            z-index: 2;
            position: relative;
        }

        h2 {
            color: #ffffff;
            font-size: 32px;
            margin-bottom: 25px;
            font-weight: 700;
            background: linear-gradient(135deg, #34d399 0%, #22d3ee 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 20px;
            background: rgba(10, 22, 38, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            color: #ffffff;
            font-size: 15px;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus {
            background: rgba(16, 42, 73, 0.7);
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
            transition: all 0.3s ease;
        }

        button:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #1c86ee 0%, #22d3ee 100%);
        }

        .error-text { color: #f87171; font-size: 14px; margin-bottom: 15px; font-weight: 500; }
        .back-home { display: inline-block; margin-top: 25px; font-size: 14px; color: rgba(255, 255, 255, 0.5); text-decoration: none; border-top: 1px solid rgba(255, 255, 255, 0.08); width: 100%; padding-top: 20px; }
        .back-home:hover { color: #22d3ee; }
    </style>
</head>
<body>

<canvas id="ambient-canvas"></canvas>

<div class="login-card">
    <h2>Admin Login</h2>
    <?php if(isset($error)) echo "<p class='error-text'>⚠️ $error</p>"; ?>
    <form method="POST">
        <input type="text" name="user" placeholder="Username" required>
        <input type="password" name="pass" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <a href="home.php" class="back-home">🏠 Portal Home</a>
</div>

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
