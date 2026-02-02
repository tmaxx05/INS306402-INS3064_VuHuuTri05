<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Session 02 Worksheet - Vu Huu Tri </title>
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
    <!-- PART 1: WARM-UP EXERCISES -->
    <!-- ================================================================ -->
    <h2>PART 1: WARM-UP EXERCISES</h2>

    <h3>01. Hello Strings</h3>
    <div class="output">
        <?php
        $name = "Alice";
        $city = "Paris";
        echo $name . " lives in " . $city . ".";
        ?>
    </div>

    <h3>02. Math Ops</h3>
    <div class="output">
        <?php
        $x = 10; 
        $y = 5;
        echo ($x + $y) . PHP_EOL;
        echo ($x - $y) . PHP_EOL;
        echo ($x * $y) . PHP_EOL;
        echo ($x / $y) . PHP_EOL;
        ?>
    </div>

    <h3>03. Casting</h3>
    <div class="output">
        <?php
        $strNum = '25.50';
        $floatNum = (float)$strNum;
        $intNum = (int)$floatNum;
        echo gettype($floatNum) . "($floatNum), ";
        echo gettype($intNum) . "($intNum)";
        ?>
    </div>

    <h3>04. Truthiness</h3>
    <div class="output">
        <?php
        $isOnline = true;
        echo $isOnline ? "User is Online" : "User is Offline";
        ?>
    </div>

    <h3>05. Array Init</h3>
    <div class="output">
        <?php
        $fruits = ["Apple", "Banana", "Pear"];
        echo $fruits[1]; 
        ?>
    </div>

    <h3>06. Sentence Builder</h3>
    <div class="output">
        <?php
        $sentence = "PHP";
        $sentence .= " is";
        $sentence .= " fun";
        echo $sentence;
        ?>
    </div>

    <h3>07. Strict Check</h3>
    <div class="output">
        <?php
        $val1 = 5;
        $val2 = '5';
        $loose = ($val1 == $val2) ? "True" : "False";
        $strict = ($val1 === $val2) ? "True" : "False";
        echo "Equal (==): $loose, Identical (===): $strict";
        ?>
    </div>

    <h3>08. Logic Gate</h3>
    <div class="output">
        <?php
        $age = 20;
        $hasTicket = true;
        if ($age > 18 && $hasTicket) {
            echo "Enter";
        } else {
            echo "Deny";
        }
        ?>
    </div>
</div>

</body>
</html>

