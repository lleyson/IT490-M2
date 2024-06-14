<?php
require_once(__DIR__ . "/partials/nav.php");
require_once(__DIR__ . "/lib/db.php"); // Include the database connection script
?>
<form onsubmit="return validate(this)" method="POST">
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" required />
    </div>
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" required />
    </div>
    <div>
        <label for="pw">Password</label>
        <input type="password" id="pw" name="password" required minlength="8" />
    </div>
    <div>
        <label for="confirm">Confirm</label>
        <input type="password" name="confirm" required minlength="8" />
    </div>
    <input type="submit" value="Register" />
</form>
<script>
    function validate(form) {
        const pw = form.password.value;
        const confirm = form.confirm.value;
        if (pw !== confirm) {
            alert("Passwords must match");
            return false;
        }
        return true;
    }
</script>
<?php
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm"])) {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $username = htmlspecialchars($_POST["username"]);
    $confirm = htmlspecialchars($_POST["confirm"]);

    $hasError = false;
    if (empty($email)) {
        echo "Email must not be empty<br>";
        $hasError = true;
    }
    // Sanitize email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address<br>";
        $hasError = true;
    }
    if (empty($password)) {
        echo "Password must not be empty<br>";
        $hasError = true;
    }
    if (empty($confirm)) {
        echo "Confirm password must not be empty<br>";
        $hasError = true;
    }
    if (strlen($password) < 8) {
        echo "Password too short<br>";
        $hasError = true;
    }
    if ($password !== $confirm) {
        echo "Passwords must match<br>";
        $hasError = true;
    }
    if (!$hasError) {
        echo "Welcome, $email<br>";
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO m1_users (email, password, username) VALUES (:email, :password, :username)");
        try {
            $stmt->execute([":email" => $email, ":password" => $hash, ":username" => $username]);
            echo "Successfully registered!<br>";
        } catch (Exception $e) {
            echo "There was a problem registering<br>";
            echo "<pre>" . var_export($e, true) . "</pre>";
        }
    }
}
?>
