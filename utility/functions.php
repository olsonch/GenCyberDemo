<?php
function logErrorMessage($message) {
    $_SESSION['log'][] = ['message' => $message, 'color' => 'red', 'displayed' => false];
}
function logSuccessMessage($message) {
    $_SESSION['log'][] = ['message' => $message, 'color' => 'green', 'displayed' => false];
}