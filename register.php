<?php
session_start();
include("includes/db/db.php");
include("includes/temp/header.php");

$message = "";
$name = "";
$email = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $name  = trim($_POST['name']);
  $email = trim($_POST['email']);
  $pass  = trim($_POST['pass']);
  $cpass = trim($_POST['cpass']);

  /* ========================= VALIDATION ========================= */
  if (empty($name) || empty($email) || empty($pass) || empty($cpass)) {
    $message = "Please fill in all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Enter a valid email address.";
  } elseif (strlen($pass) < 5) {
    $message = "Password must be at least 5 characters long.";
  } elseif ($pass !== $cpass) {
    $message = "Passwords do not match.";
  }

  /* ========================= REGISTER ========================= */
  if (empty($message)) {
    // تحقق من وجود الايميل بالفعل
    $stmt = $connect->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(array($email));
    $count = $stmt->rowCount();
    if ($count > 0) {
      $message = "Email is already registered.";
    } else {


      $stmt = $connect->prepare("INSERT INTO users (`username`, email, `password`, `role`, `status` , created_at) VALUES (?, ?, ?, 'user', '1', NOW())");
      $stmt->execute([$name, $email, $pass]);

      $_SESSION['message_register'] = $_SESSION['message_login'];
      $_SESSION['message_login'] = "Registration successful! You can login now.";
      header("Location: login.php");
      exit();
    }
  }
}
?>

<div class="container mt-5 pt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-6">
      <div class="card shadow p-4">

        <h3 class="text-center mb-4">Register Page</h3>

        <!-- عرض الرسائل -->
        <?php if (!empty($message)) { ?>
          <div id="formMessage" class="alert alert-danger text-center">
            <?php echo htmlspecialchars($message); ?>
          </div>
        <?php } ?>

        <form method="post">
          <div class="mb-3">
            <input type="text" name="name"
              value="<?php echo htmlspecialchars($name); ?>"
              placeholder="Full Name"
              class="form-control">
          </div>
          <div class="mb-3">
            <input type="email" name="email"
              value="<?php echo htmlspecialchars($email); ?>"
              placeholder="E-mail"
              class="form-control">
          </div>
          <div class="mb-3">
            <input type="password" name="pass"
              placeholder="Password"
              class="form-control">
          </div>
          <div class="mb-4">
            <input type="password" name="cpass"
              placeholder="Confirm Password"
              class="form-control">
          </div>

          <button type="submit" class="btn btn-success w-100">Register</button>
        </form>

        <p class="text-center mt-3">
          Already have an account? <a href="login.php">Login here</a>
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
  }, 3000);
</script>

<?php include("includes/temp/footer.php"); ?>