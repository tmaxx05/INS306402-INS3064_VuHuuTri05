<?php
// calculator.php - Self-processing arithmetic calculator
// Requirements: use is_numeric(), prevent division by zero, display equation and result

$resultStr = '';
$error = '';
$num1 = '';
$num2 = '';
$operation = '+';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num1 = $_POST['num1'] ?? '';
    $num2 = $_POST['num2'] ?? '';
    $operation = $_POST['operation'] ?? '+';

    if ($num1 === '' || $num2 === '') {
        $error = 'Both numbers are required.';
    } elseif (!is_numeric($num1) || !is_numeric($num2)) {
        $error = 'Both inputs must be numeric.';
    } else {
        // Convert to numeric values (float or int)
        $a = $num1 + 0;
        $b = $num2 + 0;

        switch ($operation) {
            case '+':
                $res = $a + $b;
                break;
            case '-':
                $res = $a - $b;
                break;
            case '*':
                $res = $a * $b;
                break;
            case '/':
                if ($b == 0) {
                    $error = 'Division by zero is not allowed.';
                } else {
                    $res = $a / $b;
                }
                break;
            default:
                $error = 'Invalid operation.';
        }

        if ($error === '' && isset($res)) {
            if (!is_finite($res)) {
                $error = 'Result is not a finite number.';
            } else {
                // Format result: show up to 10 decimal places but trim trailing zeros
                if (is_int($res) || floor($res) == $res) {
                    $resultStr = (string) $res;
                } else {
                    $resultStr = rtrim(rtrim(sprintf('%.10f', $res), '0'), '.');
                }
                $equation = htmlspecialchars($a) . ' ' . htmlspecialchars($operation) . ' ' . htmlspecialchars($b) . ' = ' . $resultStr;
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Arithmetic Calculator</title>
    <style>
        body { font-family: Arial, sans-serif; max-width:640px; margin:2rem auto; padding:1rem; }
        label { display:block; margin-top:.5rem; }
        input[type=number] { width:100%; padding:.5rem; box-sizing:border-box; }
        select { padding:.4rem; }
        .error { color:#a00; margin-top:1rem; }
        .result { color:#006600; margin-top:1rem; font-weight:bold; }
        .form-row { display:flex; gap:.5rem; align-items:center; }
        .form-row > * { flex:1; }
    </style>
</head>
<body>
    <h1>Arithmetic Calculator</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-row">
            <div>
                <label for="num1">Number 1</label>
                <input type="number" id="num1" name="num1" step="any" value="<?php echo htmlspecialchars($num1); ?>">
            </div>

            <div style="flex:0 0 110px;">
                <label for="operation">Operation</label>
                <select id="operation" name="operation">
                    <option value="+" <?php echo $operation === '+' ? 'selected' : ''; ?>>+</option>
                    <option value="-" <?php echo $operation === '-' ? 'selected' : ''; ?>>-</option>
                    <option value="*" <?php echo $operation === '*' ? 'selected' : ''; ?>>*</option>
                    <option value="/" <?php echo $operation === '/' ? 'selected' : ''; ?>>/</option>
                </select>
            </div>

            <div>
                <label for="num2">Number 2</label>
                <input type="number" id="num2" name="num2" step="any" value="<?php echo htmlspecialchars($num2); ?>">
            </div>
        </div>

        <div style="margin-top:1rem;">
            <button type="submit">Calculate</button>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="error">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php elseif (!empty($equation)): ?>
        <div class="result">✅ <?php echo $equation; ?></div>
    <?php endif; ?>

    <hr>
    <p><strong>Notes:</strong> Inputs are validated with <code>is_numeric()</code>. Division by zero is prevented.</p>
</body>
</html>