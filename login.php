<!DOCTYPE html>
<html>

<head>
  <title>Login Page</title>
  <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
  <div class="container">
    <div class="login-box">
      <h2>Login</h2>
      <div class="form-box">
        <form action="login.php" method="post">
          <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
          </div>
          <button type="submit">Login</button>
        </form>
      </div>
      <div class= "error-message">
      <?php
      session_start();
      require_once('config.php');
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $_SESSION['username'] = $username;

        // Prepare and execute the query to check credentials
        $sql = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
          $row = $result->fetch_assoc();
          $validUsername = $row["username"];
          $validPassword = $row["password"];
          $userType = $row["type"];

          // Verify the password
          if ($validUsername == $username && $validPassword == $password) {
            // Redirect to the appropriate dashboard based on the user type
            if ($userType === 'admin') {
              header("Location: admindashboard.php");
            } else {
              header("Location: dashboard.php");
            }
            exit;
          } else {
            // Invalid credentials, display an error message or redirect back to the login page.
            echo "Entered password and user name does not match.";
          }
        } else {
          // Invalid credentials, display an error message or redirect back to the login page.
          echo "Invalid username.";
        }

        // Close the connection
        $conn->close();
      }
      ?>
      </div>
    </div>
  </div>
</body>

</html>
