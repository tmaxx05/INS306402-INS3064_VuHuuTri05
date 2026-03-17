<?php
require_once __DIR__ . '/utils.php';

function assertEqual($desc, $expected, $actual) {
    $ok = ($expected === $actual);
    echo ($ok ? "✅ Pass: " : "❌ Fail: ") . $desc . PHP_EOL;
    if (!$ok) {
        echo "    Expected: ";
        var_export($expected);
        echo PHP_EOL . "    Actual: ";
        var_export($actual);
        echo PHP_EOL;
    }
    return $ok;
}

$passed = 0;
$failed = 0;

// sanitize tests
if (assertEqual('sanitize trims and strips tags + escapes', 'Hello &amp; Welcome', sanitize("  <b>Hello & Welcome</b>  "))) { $passed++; } else { $failed++; }
if (assertEqual('sanitize removes script tags content left escaped', 'alert(&#039;x&#039;) Test', sanitize("  <script>alert('x')</script> Test  "))) { $passed++; } else { $failed++; }

// validateEmail tests
if (assertEqual('validateEmail valid', true, validateEmail('user@example.com'))) { $passed++; } else { $failed++; }
if (assertEqual('validateEmail invalid', false, validateEmail('invalid-email@'))) { $passed++; } else { $failed++; }

// validateLength tests
if (assertEqual('validateLength within bounds', true, validateLength('abcd', 2, 5))) { $passed++; } else { $failed++; }
if (assertEqual('validateLength too short', false, validateLength('a', 2, 5))) { $passed++; } else { $failed++; }
if (assertEqual('validateLength too long', false, validateLength('abcdef', 2, 5))) { $passed++; } else { $failed++; }

// validatePassword tests
if (assertEqual('validatePassword valid (length+special)', true, validatePassword('password!'))) { $passed++; } else { $failed++; }
if (assertEqual('validatePassword invalid (no special)', false, validatePassword('Password1'))) { $passed++; } else { $failed++; }
if (assertEqual('validatePassword invalid (too short)', false, validatePassword('a!b'))) { $passed++; } else { $failed++; }
if (assertEqual('validatePassword valid with underscore special', true, validatePassword('Pass_word1'))) { $passed++; } else { $failed++; }

// Summary
echo PHP_EOL . "Summary: Passed: $passed, Failed: $failed" . PHP_EOL;

if ($failed === 0) {
    echo "All tests passed. ✅" . PHP_EOL;
    exit(0);
} else {
    echo "Some tests failed. ❌" . PHP_EOL;
    exit(1);
}
