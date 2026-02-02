<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Session 02 Worksheet - Vu Huu Tri</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; background-color: rgb(60, 179, 113); color: #333; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; }
        h2 { border-bottom: 2px solid #4a90e2; padding-bottom: 10px; margin-top: 40px; color: #333; }
        h3 { background: #eee; padding: 15px;border-radius: 15px; border-left: 15px solid #4a90e2; margin-top: 20px; font-size: 1.1em; }
        .output { background: #2d3436; color: #81ecec; padding: 15px; border-radius: 15px; font-family: 'Courier New', monospace; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<div class="container">
    <h1>PHP SESSION 02 - PHP PRACTICE WORKSHET</h1>
    <p>Student: VU HUU TRI | ID: 23070745 | Course: INS3064</p>
    <!-- ================================================================ -->
    <!-- PART 4: MINI PROJECTS -->
    <!-- ================================================================ -->
    <h2>PART 4: MINI PROJECTS</h2>

    <!-- 01 BMI Calculator -->
    <h3>01. BMI Calculator</h3>
    <div class="output">
        <?php
        function calculateBMI($kg, $m) {
            $bmi = $kg / ($m * $m);
            $bmiFormatted = round($bmi, 1);
            $category = "";
            
            if ($bmi < 18.5) {
                $category = "Underweight";
            } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
                $category = "Normal";
            } else {
                $category = "Overweight";
            }
            return "BMI: $bmiFormatted ($category)";
        }
        
        echo calculateBMI(70, 1.75);
        ?>
    </div>

    <!-- 02 Student List -->
    <h3>02. Student List</h3>
    <div class="output" style="background: orange; color: black;">
        <?php
        $students = [
            ['name' => 'Nguyen Van A', 'grade' => 88],
            ['name' => 'Vu Thi B', 'grade' => 95],
            ['name' => ' William John', 'grade' => 49],
            ['name' => 'Tran Thi D', 'grade' => 58]
        ];
        ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Grade</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= $student['name'] ?></td>
                    <td><?= $student['grade'] ?></td>
                    <td>
                        <?= $student['grade'] >= 50 
                            ? "<span class='success'>Pass</span>" 
                            : "<span class='danger'>Fail</span>" 
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 03 Prime Seeker -->
    <h3>03. Prime Seeker (1-100)</h3>
    <div class="output">
        <?php
        function isPrime($n): bool {
            if ($n < 2) return false;
            for ($i = 2; $i <= sqrt($n); $i++) {
                if ($n % $i == 0) return false;
            }
            return true;
        }

        $primes = [];
        for ($i = 1; $i <= 100; $i++) {
            if (isPrime($i)) {
                $primes[] = $i;
            }
        }
        echo implode(", ", $primes);
        ?>
    </div>

    <!-- 04 Scoreboard -->
    <h3>04. Scoreboard</h3>
    <div class="output">
        <?php
        $scores = [49, 80, 90, 66, 78, 88, 92, 53];
        
        // Calculations
        $max = max($scores);
        $min = min($scores);
        $avg = array_sum($scores) / count($scores);
        
        // Filter top performers (Above Average)
        $topPerformers = array_filter($scores, function($s) use ($avg) {
            return $s > $avg;
        });

        echo "Min: $min | Max: $max | Average: " . round($avg, 2) . "<br>";
        echo "Top Performers (> Avg): " . implode(", ", $topPerformers);
        ?>
    </div>

</div>

</body>
</html>
