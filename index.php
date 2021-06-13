<?php
require 'model/database.php';
require 'utility/functions.php';
require 'model/login_db.php';

// start session management with a persistent cookie
$lifetime = 60 * 60 * 24 * 7;      // 1 week in seconds

session_set_cookie_params($lifetime, '/');
session_start();

// create a session log array if needed
if (empty($_SESSION['log'])) {
    $_SESSION['log'] = array();
}

if (!empty($_POST)) {
    $_POST = array_map('trim', $_POST);
}

if (isset($_POST['action'])) {
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
} elseif (isset($_GET['action'])) {
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
} else {
    $action = 'show-login-form';
}

if ($action === 'show-login-form') {
    $pageTitle = 'Log In';
    include 'view/login.php';
} elseif ($action === 'show-register-form') {
    $pageTitle = 'Create Account';
    include 'view/register.php';
} elseif ($action === 'register') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirmPassword = filter_input(INPUT_POST, 'confirm-password', FILTER_SANITIZE_STRING);

    if (empty($username)) {
        $errorUsername = 'Please enter a username.';
    } elseif (strlen($username) < 5) {
        $errorUsername = 'The username must have at least 5 characters.';
    } elseif (checkUsername($username) === true) {
        $errorUsername = 'This username is already taken.';
    }

    if (empty($password)) {
        $errorPassword = 'Please enter a password.';
    } else {
        if (strlen($password) < 9) {
            $errorPassword = 'The password must have at least 9 characters.<br>';
        }
        if (!preg_match('/[[:lower:]]/', $password)) {
            $errorPassword .= 'The password must contain a lowercase letter.<br>';
        }
        if (!preg_match('/[[:upper:]]/', $password)) {
            $errorPassword .= 'The password must contain an uppercase letter.<br>';
        }
        if (!preg_match('/[[:digit:]]/', $password)) {
            $errorPassword .= 'The password must contain a number.<br>';
        }
        if (!preg_match('/[!@#%&|?]/', $password)) {
            $errorPassword .= 'The password must contain at least one of the following characters: ! @ # % & | ?<br>';
        }
    }

    if (empty($confirmPassword)) {
        $errorConfirmPassword = 'Please confirm the password.';
    } elseif ($confirmPassword !== $password) {
        $errorConfirmPassword = 'The passwords that were entered do not match.';
    }

    if (empty($errorUsername) && empty($errorPassword) && empty($errorConfirmPassword)) {
        $userIP = $_SERVER['REMOTE_ADDR'];
        if (registerUser($username, $password, $userIP)) {
            header('Location:.?action=show-login-form');
        } else {
            $pageTitle = 'Create Account';
            include 'view/register.php';
        }
    } else {
        $pageTitle = 'Create Account';
        logErrorMessage('The account could not be created. Please see the errors below.');
        include 'view/register.php';
    }
} elseif ($action === 'log-in') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (empty($username)) {
        $errorUsername = 'Please enter your username.';
    } else if (checkUsername($username) === false) {
        $errorUsername = 'No account found with that username.';
    } else {
        if (empty($password)) {
            $errorPassword = 'Please enter your password.';
        } else if (isValidLogin($username, $password) === false) {
            $errorPassword = 'The password is incorrect.';
        }
    }
    if (empty($errorUsername) && empty($errorPassword)) {
        session_start();
        $_SESSION['username'] = $username;
        header('Location:.?action=show-authorized-page');
    } else {
        $pageTitle = 'Log In';
        logErrorMessage('Unsuccessful log in attempt. Please see the errors below.');
        include 'view/login.php';
    }
} elseif ($action === 'log-out') {
    session_start();
    $_SESSION = array();
    session_destroy();
    session_start();
    logSuccessMessage('You have successfully logged out.');
    header('Location:.');
    exit();
} elseif ($action === 'show-authorized-page') {
    if (isset($_SESSION['username'])) {
        $pageTitle = 'Authorized Users';
        logSuccessMessage('You successfully logged in and are authorized to view this page.');
        include 'view/authorized.php';
    } else {
        $pageTitle = 'Unauthorized!';
        logErrorMessage("You were not logged in and authorized to view that page.");
        include 'view/login.php';
    }
} else {
    $error = "The <strong>$action</strong> action was not handled in the code.";
    logErrorMessage($error);
    $pageTitle = 'Code Error';
    header('Location:.');
}