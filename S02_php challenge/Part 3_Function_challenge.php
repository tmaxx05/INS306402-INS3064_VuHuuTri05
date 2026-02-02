<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Session 02 Homework - Le Duc Hung</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; background-color: #f4f4f9; color: #333; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #4a90e2; }
        h2 { border-bottom: 2px solid #4a90e2; padding-bottom: 10px; margin-top: 40px; color: #333; }
        h3 { background: #eee; padding: 8px; border-left: 4px solid #4a90e2; margin-top: 20px; font-size: 1.1em; }
        .output { background: #2d3436; color: #81ecec; padding: 15px; border-radius: 5px; font-family: 'Courier New', monospace; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: #00b894; font-weight: bold; }
        .danger { color: #d63031; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h1>PHP SESSION 02 - PHP PRACTICE WORKSHET</h1>
    <p>Student: VU HUU TRI | Course: INS3064</p>
<!-- ================================================================ -->
    <!-- PART 3: FUNCTION CHALLENGES -->
    <!-- ================================================================ -->
    <h2>PART 3: FUNCTION CHALLENGES</h2>
    <?php
    // Functions definitions
    
    // 01 Greeter
    function greet(string $name): string {
        return "Hello, $name!";
    }

    // 02 Area Calc
    function area(float $w, float $h): float {
        return $w * $h;
    }

    // 03 Adult Check
    function isAdult(?int $age): bool {
        if ($age === null) return false;
        return $age >= 18;
    }

    // 04 Safe Divide
    function safeDiv(float $a, float $b): ?float {
        if ($b == 0) return null;
        return $a / $b;
    }

    // 05 Formatter
    function fmt(float $amt, string $c = '$'): string {
        return $c . number_format($amt, 2);
    }

    // 06 Pure Math
    function add(int $a, int $b): int {
        return $a + $b;
    }
    ?>

    <h3>Test Function Outputs:</h3>
    <div class="output">
        <p><strong>01 Greeter:</strong> <?= greet("Sam") ?></p>
        <p><strong>02 Area:</strong> <?= area(5.5, 2) ?></p>
        <p><strong>03 Adult Check (null):</strong> <?= isAdult(null) ? 'True' : 'False' ?></p>
        <p><strong>03 Adult Check (20):</strong> <?= isAdult(20) ? 'True' : 'False' ?></p>
        <p><strong>04 Safe Divide (10, 0):</strong> <?= var_export(safeDiv(10, 0), true) ?></p>
        <p><strong>04 Safe Divide (10, 2):</strong> <?= safeDiv(10, 2) ?></p>
        <p><strong>05 Formatter:</strong> <?= fmt(50) ?></p>
        <p><strong>06 Pure Math:</strong> <?= add(5, 7) ?></p>
    </div>
</div>

</body>
</html>

