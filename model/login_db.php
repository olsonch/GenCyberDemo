<?php
function checkUsername($username) {
    $db = Database::getDB();
    $query = 'SELECT id FROM users WHERE username = :username';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $success = $statement->execute();

    if ($statement->errorCode() !== 0 && $success === false) {
        $sqlError = $statement->errorInfo();
        $error = 'The query to check if a user exists did not work because: ' . $sqlError[2];
        logErrorMessage($error);
    }

    $statement->closeCursor();
    return $statement->rowCount() === 1;
}
function registerUser($username, $password, $userIP) {
    $db = Database::getDB();
    $query = 'INSERT INTO users (username, password, ipAddress) VALUES (:username, :password, :userIP)';
    $statement = $db->prepare($query);
    //$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $statement->bindValue(':username', $username);
    // the line below can be modified to determine how the password is stored
    $statement->bindValue(':password', $password);
    $statement->bindValue(':userIP', $userIP);
    $success = $statement->execute();

    if ($statement->errorCode() !== 0 && $success === false) {
        $sqlError = $statement->errorInfo();
        $error = 'The query to register a user did not work because: ' . $sqlError[2];
        logErrorMessage($error);
    } else {
        $successMessage = 'The user <strong>' . $username . '</strong> was successfully registered.';
        logSuccessMessage($successMessage);
    }
    $statement->closeCursor();
    return $success;
}
function isValidLogin($username, $password) {
    $db = Database::getDB();
    $query = 'SELECT username, password FROM users WHERE username = :username';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $success = $statement->execute();

    if ($statement->errorCode() !== 0 && $success === false) {
        $sqlError = $statement->errorInfo();
        $error = 'The query to log in a user did not work because: ' . $sqlError[2];
        logErrorMessage($error);
        $statement->closeCursor();
        return false;
    } else {
        $row = $statement->fetch();
        //$hashedPassword = $row['password'];
        $statement->closeCursor();
        if ($row['password'] === $password) {
        //if (password_verify($password, $hashedPassword)) {
            $successMessage = 'The user <strong>' . $username . '</strong> was successfully logged in.';
            logSuccessMessage($successMessage);
            return true;
        } else {
            $error = 'The password for <strong>' . $username . '</strong> is incorrect.';
            logErrorMessage($error);
            return false;
        }
    }
}