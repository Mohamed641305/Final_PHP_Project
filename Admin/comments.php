<?php
session_start();
if (isset($_SESSION['admin_login'])) {

  include 'includes/temp/init.php';



  $page = isset($_GET['page']) ? $_GET['page'] : 'All';


  /* ================== All ================== */
  if ($page == "All") {

    $statement = $connect->prepare("SELECT * FROM comments");
    $statement->execute();
    $comments = $statement->fetchAll();
    $commentCount = $statement->rowCount();
?>

    <div class="container my-3 py-5">
      <div class="row justify-content-center">
        <div class="col-md-12 ">

          <!-- رسالة نجاح -->
          <?php if (!empty($_SESSION['message'])): ?>
            <div class="alert alert-success shadow-sm rounded-3 text-center py-2 my-3 auto-hide">
              <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
          <?php endif; ?>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Comments <span class="badge bg-primary"><?= $commentCount ?></span></h3>
            <a href="?page=create" class="btn btn-success shadow-sm">+ Add Comment</a>
          </div>

          <div class="card shadow border-0">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center mb-0">
                  <thead class="table-dark">
                    <tr>
                      <th>ID</th>
                      <th>Product Title</th>
                      <th>Comment</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php if ($commentCount > 0): ?>
                      <?php foreach ($comments as $row): ?>
                        <tr>
                          <td><?= $row['id'] ?></td>
                          <td><?= htmlspecialchars($row['product_title']) ?></td>
                          <td><?= htmlspecialchars($row['comment']) ?></td>
                          <td>
                            <a href="?page=show&id=<?= $row['id'] ?>" class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                            <a href="?page=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                            <a href="?page=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                              onclick="return confirm('Are you sure to delete this comment?');">
                              <i class="fas fa-trash"></i>
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4">No comments found</td>
                      </tr>
                    <?php endif; ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  <?php
  }


  /* ================= Create ================= */
  if ($page == "create") {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $name = $_POST['name'];
      $title = $_POST['product_title'];
      $description = $_POST['comment'];

      if (empty($name) && empty($title) && empty($description)) {
        $_SESSION['error'] = "Fill All Fields.";
      } elseif (empty($name)) {
        $_SESSION['error'] = "Name is required.";
      } elseif (empty($title)) {
        $_SESSION['error'] = "Title is required.";
      } elseif (empty($description)) {
        $_SESSION['error'] = "Description is required.";
      } else {
        $statement = $connect->prepare("INSERT INTO comments (user_name, product_title, comment, created_at) VALUES (?, ?, ?, NOW())");
        $statement->execute([$name, $title, $description]);

        $_SESSION['message'] = "Created Successfully.";
        header("Location: comments.php");
        exit();
      }
    }
  ?>

    <div class="container my-5 py-5">
      <div class="row justify-content-center">
        <div class="col-md-6 m-auto">

          <!-- رسالة خطأ -->
          <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger shadow-sm rounded-3 text-center py-2 my-3 auto-hide">
              <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <div class="card shadow border-0 p-4">
            <h3 class="text-center mb-4">Add New Comment</h3>

            <form method="POST">
              <input type="text" name="name" class="form-control mb-3" placeholder="Name">
              <input type="text" name="product_title" class="form-control mb-3" placeholder="Product Title">
              <input type="text" name="comment" class="form-control mb-3" placeholder="Comment">
              <input type="submit" value="Add Comment" class="btn btn-success w-100">
            </form>
          </div>

        </div>
      </div>
    </div>

  <?php
  }

  /* ================= SHOW ================= */
  if ($page == "show" && isset($_GET['id'])) {

    $statement = $connect->prepare("SELECT * FROM comments WHERE id = ?");
    $statement->execute([$_GET['id']]);
    $comment = $statement->fetch();
  ?>

    <div class="container my-3 py-5">
      <div class="row justify-content-center">
        <div class="col-md-12 ">

          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Comment Details</h3>
          </div>

          <div class="card shadow border-0">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center mb-0">
                  <thead class="table-dark">
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Product_Title</th>
                      <th>Description</th>
                      <th>Created At</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?= $comment['id'] ?></td>
                      <td><?= htmlspecialchars($comment['user_name']) ?></td>
                      <td><?= htmlspecialchars($comment['product_title']) ?></td>
                      <td><?= htmlspecialchars($comment['comment']) ?></td>
                      <td><?= $comment['created_at'] ?></td>
                      <td>
                        <a href="categories.php" class="btn btn-sm btn-success"><i class="fas fa-house"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  <?php
  }

  /* ================= EDIT ================= */
  if ($page == "edit" && isset($_GET['id'])) {

    $statement = $connect->prepare("SELECT * FROM comments WHERE id = ?");
    $statement->execute([$_GET['id']]);
    $comment = $statement->fetch();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $name = $_POST['name'];
      $title = $_POST['product_title'];
      $description = $_POST['comment'];

      if (empty($name) && empty($title) && empty($description)) {
        $_SESSION['error'] = "Fill All Fields.";
      } elseif (empty($name)) {
        $_SESSION['error'] = "Name is required.";
      } elseif (empty($title)) {
        $_SESSION['error'] = "Title is required.";
      } elseif (empty($description)) {
        $_SESSION['error'] = "Description is required.";
      } else {
        $statement = $connect->prepare("UPDATE comments SET user_name = ?, product_title = ?, comment = ? WHERE id = ?");
        $statement->execute([$name, $title, $description, $_GET['id']]);

        $_SESSION['message'] = "Updated Successfully.";
        header("Location: comments.php");
        exit();
      }
    }
  ?>

    <div class="container my-5 py-5">
      <div class="row justify-content-center">
        <div class="col-md-6 m-auto">

          <!-- رسالة خطأ -->
          <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger shadow-sm rounded-3 text-center py-2 my-3 auto-hide">
              <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <div class="card shadow border-0 p-4">
            <h3 class="text-center mb-4">Edit Comment</h3>

            <form method="POST">
              <input type="text" name="name" class="form-control mb-3" value="<?= htmlspecialchars($comment['user_name']) ?>">
              <input type="text" name="product_title" class="form-control mb-3" value="<?= htmlspecialchars($comment['product_title']) ?>">
              <input type="text" name="comment" class="form-control mb-3" value="<?= htmlspecialchars($comment['comment']) ?>">

              <button class="btn btn-primary w-100">Update Comment</button>
            </form>
          </div>

        </div>
      </div>
    </div>

  <?php
  }

  /* ================= DELETE ================= */
  if ($page == "delete" && isset($_GET['id'])) {
    $statement = $connect->prepare("DELETE FROM comments WHERE id = ?");
    $statement->execute([$_GET['id']]);

    $_SESSION['message'] = "Deleted Successfully";
    header("Location: comments.php");
    exit();
  }
  ?>

  <!-- سكربت موحد -->
  <script>
    setTimeout(() => {
      document.querySelectorAll('.auto-hide').forEach(el => {
        el.style.display = 'none';
      });
    }, 3000);
  </script>

<?php
  include 'includes/temp/footer.php';
} else {
  header("Location: ../login.php");
  $_SESSION['message_login'] = "Login First";
  exit();
}
?>