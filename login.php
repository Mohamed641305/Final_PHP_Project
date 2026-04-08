<?php
session_start();
include("includes/db/db.php");
include("includes/temp/header.php");

$message = "";
$email = "";

// ====================== POST LOGIN ======================
if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $email = $_POST['email'];
  $pass  = $_POST['pass'];

  /* ========================= VALIDATION ========================= */
  $fields = [$email, $pass];
  $empty = 0;

  foreach ($fields as $f) {
    if ($f == "") $empty++;
  }

  if ($empty >= 2) {
    $message = "Please fill in all fields.";
  } else if ($email == "") {
    $message = "Please enter Email.";
  } else if ($pass == "") {
    $message = "Please enter Password.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Enter a valid email address.";
  } else if (strlen($pass) < 5) {
    $message = "Password must be at least 5 characters long.";
  }

  /* ========================= LOGIN ========================= */
  if (empty($message)) {
    $statement = $connect->prepare("SELECT * FROM users WHERE email=?");
    $statement->execute(array($email));
    $userCount = $statement->rowCount();

    if ($userCount > 0) {

      $result = $statement->fetch();

      // الباسورد غلط
      if ($pass != $result['password']) {
        $_SESSION['message_login'] = "Check Your Password";
      } else {
        if ($result['status'] == 1) {
          if ($result['role'] == "admin") {
            $_SESSION['admin_login'] = $email;
            header("Location: Admin/dashboard.php");
            exit();
          } else {
            $_SESSION['user_login'] = $email;
            header("Location: index.php");
            exit();
          }
        } else {
          $_SESSION['message_login'] = "Your Account Not Active";
        }
      }
    } else {
      $_SESSION['message_login'] = "Your Account Not in DB";
    }
  }
}
?>

<div class="container mt-5 pt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-6">
      <div class="card shadow p-4">

        <h3 class="text-center mb-4">Login Page</h3>

        <!-- عرض الرسائل -->
        <?php if (!empty($message)) { ?>
          <div id="formMessage" class="alert alert-danger text-center">
            <?php echo htmlspecialchars($message); ?>
          </div>
        <?php } ?>

        <?php
        if (isset($_SESSION['message_login'])) {
          echo "<div id='loginMessage' class='alert alert-danger text-center'>" . $_SESSION['message_login'] . "</div>";
          unset($_SESSION['message_login']);
        }
        ?>

        <form method="post">
          <div class="mb-3">
            <input type="email" name="email"
              value="<?php echo htmlspecialchars($email); ?>"
              placeholder="E-mail"
              class="form-control">
          </div>
          <div class="mb-4">
            <input type="password" name="pass"
              placeholder="Password"
              class="form-control hidden">
          </div>

          <button type="submit" class="btn btn-success w-100">Login</button>
        </form>

        <p class="text-center mt-3">
          Don't have an account? <a href="register.php">Register here</a>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- JS لإخفاء الرسائل بعد 3 ثواني -->
<script>
  setTimeout(() => {
    const formMsg = document.getElementById('formMessage');
    if (formMsg) formMsg.style.display = 'none';

    const loginMsg = document.getElementById('loginMessage');
    if (loginMsg) loginMsg.style.display = 'none';
  }, 3000);
</script>

<?php include("includes/temp/footer.php"); ?>