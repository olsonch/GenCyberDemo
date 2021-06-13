<?php
include 'utility/secure_connection.php';
include 'header.php';
?>
    <h1 class="text-center">Log In</h1>
    <form action="." method="post" class="col-lg-6 mx-auto">
        <hr>
        <p>Please enter a username and password to log in.</p>
        <div class="form-group">
            <label for="username">Username:<sup>*</sup></label>
            <input type="text" name="username" id="username" class="form-control<?php echo (!empty($errorUsername)) ? ' is-invalid ' : ''; ?>"
                   value="<?php if (!empty($username)) echo $username; ?>" autofocus>
            <?php if (!empty($errorUsername)) echo "<span class='invalid-feedback font-weight-bold'>$errorUsername</span>\n"; ?>
        </div>
        <div class="form-group">
            <label for="password">Password:<sup>*</sup></label>
            <input type="password" name="password" id="password" class="form-control<?php echo (!empty($errorPassword)) ? ' is-invalid' : ''; ?>"
                   value="<?php if (!empty($password)) echo $password; ?>">
            <?php if (!empty($errorPassword)) echo "<span class='invalid-feedback font-weight-bold'>$errorPassword</span>\n"; ?>
        </div>
        <div class="form-group text-center">
            <input type="hidden" name="action" value="log-in">
            <input type="submit" class="btn btn-secondary" value="Log In">
            <a class="btn btn-secondary" href=".">Cancel</a>
        </div>
        <div class="text-center">
            <p>No account? <a href=".?action=show-register-form">Register for an account</a>.</p>
            <p><sup>*</sup> Indicates a required field.</p>
        </div>
    </form>

<?php include 'footer.php'; ?>