<?php
/**
 * Validation Utilities
 *
 * Provides reusable helper functions for sanitization and validation.
 */

/**
 * Sanitize input by trimming, removing slashes, stripping tags, and escaping HTML characters.
 *
 * @param string $data
 * @return string
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate an email address.
 *
 * @param string $email
 * @return bool
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate that a string length is between min and max (inclusive).
 *
 * @param string $str
 * @param int $min
 * @param int $max
 * @return bool
 */
function validateLength($str, $min, $max) {
    $len = mb_strlen($str);
    return ($len >= $min && $len <= $max);
}

/**
 * Validate a password (checks minimum length and presence of a special character).
 * Default minimum length is 8.
 *
 * @param string $pass
 * @param int $minLength
 * @return bool
 */
function validatePassword($pass, $minLength = 8) {
    if (mb_strlen($pass) < $minLength) {
        return false;
    }
    // Require at least one special character (non-word character or underscore)
    if (!preg_match('/[\W_]/', $pass)) {
        return false;
    }
    return true;
}
