<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Complaint Management System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #060e1a; /* Deep midnight theme background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow: hidden;
            position: relative;
        }

        /* Interactive Canvas viewport */
        #ambient-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        /* Premium Sea-Glass Card Window */
        .welcome-card {
            background: rgba(12, 28, 48, 0.45);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 55px 45px;
            width: 100%;
            max-width: 760px;
            border-radius: 24px;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.8), 
                        inset 0 1px 2px rgba(255, 255, 255, 0.1);
            text-align: center;
            z-index: 2;
            position: relative;
        }

        /* Top Centered Icon Badge */
        .top-badge {
            width: 76px;
            height: 76px;
            background: rgba(16, 42, 73, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 32px;
            margin-bottom: 25px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
            color: #ffffff;
            animation: gentleFloat 6s ease-in-out infinite;
        }

        @keyframes gentleFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }

        /* Emerald to Bright Teal Header Text Gradient */
        h1 {
            color: #ffffff;
            font-size: 42px;
            margin-bottom: 14px;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #34d399 0%, #22d3ee 50%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 4px 30px rgba(34, 211, 238, 0.15);
        }

        .subtitle {
            color: rgba(200, 230, 255, 0.55);
            font-size: 15px;
            margin-bottom: 45px;
            line-height: 1.6;
            max-width: 580px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Clean Horizontal Grid Display */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* Sub-glass Selection Items */
        .option-card {
            background: rgba(10, 22, 38, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 35px 15px;
            border-radius: 18px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.05);
        }

        .option-card:hover {
            background: rgba(16, 42, 73, 0.75);
            border-color: rgba(34, 211, 238, 0.4);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(6, 182, 212, 0.2);
        }

        .option-card .icon {
            font-size: 32px;
            margin-bottom: 15px;
            display: block;
        }

        .option-card h3 {
            font-size: 14px;
            font-weight: 500;
            color: rgba(220, 240, 255, 0.85);
            line-height: 1.4;
        }

        .footer {
            font-size: 11px;
            color: rgba(52, 211, 153, 0.35);
            margin-top: 55px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<canvas id="ambient-canvas"></canvas>

<div class="welcome-card">
    <div class="top-badge">🏛️</div>
    <h1>University CMS</h1>
    <p class="subtitle">Official student terminal for filing campus complaints and monitoring infrastructure status</p>

    <div class="options-grid">
        <a href="index.php" class="option-card">
            <span class="icon">✍️</span>
            <h3>File<br>Complaint</h3>
        </a>

        <a href="status.php" class="option-card">
            <span class="icon">🔍</span>
            <h3>Track<br>Status</h3>
        </a>

        <a href="login.php" class="option-card">
            <span class="icon">⚙️</span>
            <h3>Admin<br>Dashboard</h3>
        </a>
    </div>

    <p class="footer">UNIVERSITY PORTAL &copy; 2026</p>
</div>

<script>
    // 1. Get references to the Canvas object and the Rendering Context
    const canvas = document.getElementById('ambient-canvas');
    const ctx = canvas.getContext('2d');

    // 2. Adjust Canvas area parameters to completely map across browser screen dimensions
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;

    // 3. Define the Arrays and Pointer configurations
    const elements = [];
    const totalElements = 40; // Low count ensures peak performance and clean rendering
    const mouse = { x: null, y: null, activeRadius: 150 };

    // 4. Capture global mouse position vectors
    window.addEventListener('mousemove', (e) => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
    });

    window.addEventListener('mouseout', () => {
        mouse.x = null;
        mouse.y = null;
    });

    // 5. Handle screen resizing dynamically
    window.addEventListener('resize', () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    });

    // 6. Object Oriented Design Class for the Floating Glowing Sparks
    class FloatingSpark {
        constructor() {
            this.reset();
            this.y = Math.random() * height; // Distribute across screen initially
        }

        // Initialize or recycle elements when they drift off screen boundaries
        reset() {
            this.x = Math.random() * width;
            this.y = height + 20; // Start just below the bottom margin
            this.radius = Math.random() * 5 + 4; // Distinct readable sizes (4px - 9px)
            
            // Linear velocity: slowly drifts upward and slightly to the right
            this.vx = Math.random() * 0.4 - 0.2;
            this.vy = -(Math.random() * 0.8 + 0.4);
            
            // Alternates colors between translucent emerald and electric cyan
            this.color = Math.random() > 0.5 ? 'rgba(52, 211, 153, 0.25)' : 'rgba(34, 211, 238, 0.25)';
        }

        // Handle spatial arithmetic transformations per animation loop iteration
        update() {
            this.x += this.vx;
            this.y += this.vy;

            // Recycling check: If spark exits top margin, recycle to the bottom
            if (this.y < -20) {
                this.reset();
            }

            // MOUSE INTERACTION LOGIC (Explainable Geometry)
            if (mouse.x !== null && mouse.y !== null) {
                // Step A: Find the horizontal and vertical distance components
                let dx = mouse.x - this.x;
                let dy = mouse.y - this.y;
                
                // Step B: Use Pythagoras' Theorem to compute the actual direct distance line
                let currentDistance = Math.sqrt(dx * dx + dy * dy);
                
                // Step C: If the element falls inside the mouse radius, calculate and apply the repulsion vector
                if (currentDistance < mouse.activeRadius) {
                    let forceFactor = (mouse.activeRadius - currentDistance) / mouse.activeRadius;
                    
                    // Deflect spark positions away from the cursor coordinates
                    this.x -= (dx / currentDistance) * forceFactor * 4;
                    this.y -= (dy / currentDistance) * forceFactor * 4;
                }
            }
        }

        // Render the elements onto the active frame map buffer grid
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = this.color;
            ctx.fill();
        }
    }

    // 7. Populate our system container array loop matching the total count threshold
    for (let i = 0; i < totalElements; i++) {
        elements.push(new FloatingSpark());
    }

    // 8. Main execution loop function executing continuously at 60 frames per second
    function renderLoop() {
        // Clear the canvas buffer context area completely for a fresh drawing cycle
        ctx.clearRect(0, 0, width, height);

        // Process state updates and draws for each individual object inside the matrix array
        elements.forEach(spark => {
            spark.update();
            spark.draw();
        });

        // Request browser to queue the next animation sequence refresh natively
        requestAnimationFrame(renderLoop);
    }

    // 9. Boot up the animation runtime engine
    renderLoop();
</script>
</body>
</html>