<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Authentication<?php if (!empty($pageTitle)) echo ' - ' . $pageTitle; ?></title>
    <link rel="stylesheet" href="style/bootstrap.min.css">
    <link rel="stylesheet" href="style/styles.css">
</head>
<body>
<div class="container">
    <header>
        <?php
        if (!empty($_SESSION['log'])) {
            $lastElement = end($_SESSION['log']);
            if ($lastElement['displayed'] === false) {
                if ($lastElement['color'] === 'red') {
                    echo "<div class='alert alert-danger text-center'>\n";
                } else {
                    echo "<div class='alert alert-success text-center'>\n";
                }
                echo "<p><strong>" . $lastElement['message'] . "</strong></p>\n";
                echo "</div>\n";
                $_SESSION['log'][count($_SESSION['log']) - 1]['displayed'] = true;
            }
        }
        ?>

        <div class="pb-2 mt-4 mb-2 border-bottom text-right">
            <p>
                <?php if (isset($_SESSION['username'])) : ?>
                    Logged in as: <strong><?php echo $_SESSION['username']; ?></strong> | <a href=".?action=log-out">Log Out</a>
                <?php else : ?>
                    <a href=".?action=show-login-form">Log In</a>
                <?php endif; ?>
            </p>
        </div>
    </header>
    <main>
