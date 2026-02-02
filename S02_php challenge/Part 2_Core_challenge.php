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
    <!-- PART 2: CORE CHALLENGES -->
    <!-- ================================================================ -->
    <h2>PART 2: CORE CHALLENGES</h2>

    <h3>01. Grade Bot</h3>
    <div class="output">
        <?php
        $score = 85;
        if ($score >= 90) {
            echo "Grade: A";
        } elseif ($score >= 80) {
            echo "Grade: B";
        } elseif ($score >= 70) {
            echo "Grade: C";
        } else {
            echo "Grade: F";
        }
        ?>
    </div>

    <h3>02. Day Planner</h3>
    <div class="output">
        <?php
        $dayNum = 3;
        switch ($dayNum) {
            case 1: echo "Monday"; break;
            case 2: echo "Tuesday"; break;
            case 3: echo "Wednesday"; break;
            case 4: echo "Thursday"; break;
            case 5: echo "Friday"; break;
            case 6: echo "Saturday"; break;
            case 7: echo "Sunday"; break;
            default: echo "Invalid";
        }
        ?>
    </div>

    <h3>03. Multi-Table (Nested Loop)</h3>
    <div class="output">
        <?php
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                echo ($i * $j) . " ";
            }
            echo "<br>"; // New line after each row
        }
        ?>
    </div>

    <h3>04. Cart Total</h3>
    <div class="output">
        <?php
        $prices = [10, 20, 5];
        $total = 0;
        foreach ($prices as $price) {
            $total += $price;
        }
        echo "Total: $total";
        ?>
    </div>

    <h3>05. Countdown</h3>
    <div class="output">
        <?php
        $count = 10;
        while ($count >= 1) {
            echo "$count, ";
            $count--;
        }
        echo "Liftoff!";
        ?>
    </div>

    <h3>06. Even Filter</h3>
    <div class="output">
        <?php
        for ($i = 1; $i <= 20; $i++) {
            if ($i % 2 == 0) {
                echo "$i, ";
            }
        }
        ?>
    </div>

    <h3>07. Array Reverse (Manual)</h3>
    <div class="output">
        <?php
        $original = [1, 2, 3, 4, 5];
        $reversed = [];
        // Loop from the last index down to 0
        for ($i = count($original) - 1; $i >= 0; $i--) {
            $reversed[] = $original[$i];
        }
        print_r($reversed);
        ?>
    </div>

    <h3>08. FizzBuzz</h3>
    <div class="output">
        <?php
        for ($i = 1; $i <= 50; $i++) {
            if ($i % 3 == 0 && $i % 5 == 0) {
                echo "FizzBuzz, ";
            } elseif ($i % 3 == 0) {
                echo "Fizz, ";
            } elseif ($i % 5 == 0) {
                echo "Buzz, ";
            } else {
                echo "$i, ";
            }
        }
        ?>
    </div>
</div>

</body>
</html>

