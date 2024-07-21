<?php
$servername = "localhost";
$username = "0mar";
$password = "Ai@ktv7L9_Cj4re7";
$dbname = "task23adv";

$errors = [];

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $pass = trim($_POST['password']);
        $pass2 = trim($_POST['password2']);

        // Validate name
        if (empty($name)) {
            $errors[] = "Name is required.";
        }

        // Validate email
        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // Validate passwords
        if (empty($pass)) {
            $errors[] = "Password is required.";
        } elseif (strlen($pass) < 6) {
            $errors[] = "Password must be at least 6 characters long.";
        } elseif ($pass !== $pass2) {
            $errors[] = "Passwords do not match.";
        }

        // Check if there are no errors
        if (empty($errors)) {
            // Hash the password
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO users (email, passwordu, name) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $hashed_password, $name);

            if ($stmt->execute()) {
                echo "Registration successful.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="login.styles.css">
  <title>Sign Up</title>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card mt-5">
          <div class="card-body">
            <h3 class="card-title text-center">Sign Up</h3>
            <!-- PHP block to show validation errors -->
            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                  <p><?php echo $error; ?></p>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <form action="singup.php" method="post" id="registerForm">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="form-group">
                <label for="password2">Confirm Password</label>
                <input type="password" class="form-control" id="password2" name="password2" required>
              </div>
              <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
