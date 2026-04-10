<?php
session_start();
include("includes/db/db.php");
include("includes/temp/header.php");

$message = "";
$email = "";

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

    // البحث بالإيميل فقط
    $statement = $connect->prepare(
      "SELECT * FROM users WHERE email=?"
    );
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
          } else {

            $_SESSION['user_login'] = $email;
            header("Location: index.php");
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
  <div class="row">
    <div class="col-md-6 m-auto">
      <div class="card shadow p-4 ">

        <h4 class="text-center mt-4 mb-5">Login Page</h4>

        <!-- رسائل الخطأ -->
        <?php if (!empty($message)) { ?>
          <h4 class="alert alert-danger alert-dismissible fade show text-center mb-4" role="alert" id="message">
            <?php echo $message; ?>
          </h4>
        <?php } ?>

        <?php
        if (isset($_SESSION['message_login'])) {
          echo "<h4 class='alert alert-danger alert-dismissible fade show text-center mb-4' id='message_login'>" . $_SESSION['message_login'] . "</h4>";
          unset($_SESSION['message_login']);
        }
        ?>

        <form method="post">
          <input type="email" name="email"
            value="<?php echo $email; ?>"
            placeholder="E-mail"
            class="form-control mb-4">

          <input type="password" name="pass"
            placeholder="Password"
            class="form-control mb-5">

          <input type="submit"
            value="Login"
            class="btn btn-success btn-block w-100 d-block">
        </form>

        <p class="text-center mt-4">
          Don't have an account? <a href="register.php">Register here</a>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- JS لإخفاء الرسائل بعد 3 ثواني -->
<script>
  setTimeout(() => {
    const formMsg = document.getElementById('message');
    if (formMsg) formMsg.style.display = 'none';

    const loginMsg = document.getElementById('message_login');
    if (loginMsg) loginMsg.style.display = 'none';
  }, 3000);
</script>