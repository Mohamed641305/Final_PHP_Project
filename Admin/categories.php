<?php
session_start();
if (isset($_SESSION['admin_login'])) {

  include 'includes/temp/init.php';

  $page = isset($_GET['page']) ? $_GET['page'] : 'All';

  /* ================== All ================== */
  if ($page == "All") {

    $statement = $connect->prepare("SELECT * FROM categories");
    $statement->execute();
    $categories = $statement->fetchAll();
    $categoryCount = $statement->rowCount();
?>

    <div class="container my-3 py-5">
      <div class="row justify-content-center">
        <div class="col-md-12 ">

          <!-- رسالة النجاح -->
          <?php if (!empty($_SESSION['message'])): ?>
            <div class="alert alert-success shadow-sm rounded-3 text-center py-2 my-3">
              <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
          <?php endif; ?>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Categories <span class="badge bg-primary"><?= $categoryCount ?></span></h3>
            <a href="?page=create" class="btn btn-success shadow-sm">+ Add Category</a>
          </div>

          <div class="card shadow border-0">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center mb-0">
                  <thead class="table-dark">
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($categories as $row): ?>
                      <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                          <a href="?page=show&id=<?= $row['id'] ?>" class="btn btn-sm btn-success me-1"><i class="fas fa-eye"></i></a>
                          <a href="?page=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary me-1"><i class="fas fa-edit"></i></a>
                          <a href="?page=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this category?');"><i class="fas fa-trash"></i></a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
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

  /* ================= CREATE ================= */
  if ($page == "create") {


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $title = trim($_POST['title']);
      $description = trim($_POST['description']);
      // ❗ تركناه كما هو (حسب طلبك)
      if (empty($title) && empty($description)) {
        $_SESSION['error'] = "Fill All Fields.";
      } elseif (empty($title)) {
        $_SESSION['error'] = "Title is required.";
      } elseif (empty($description)) {
        $_SESSION['error'] = "Description is required.";
      } else {
        $statement = $connect->prepare("INSERT INTO categories (title, description, created_at) VALUES (?, ?, NOW())");
        $statement->execute([$title, $description]);
        $_SESSION['message'] = "Created Successfully.";
        header("Location: categories.php");
        exit();
      }
    }
  ?>

    <div class="container my-5 py-5">
      <div class="row justify-content-center">
        <div class="col-md-6 m-auto">

          <!-- رسالة الخطأ -->
          <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger shadow-sm rounded-3 text-center py-2 my-3 auto-hide">
              <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <div class="card shadow border-0 p-4">
            <h3 class="text-center mb-4">Add New Category</h3>
            <form method="POST">
              <input type="text" name="title" class="form-control mb-3" placeholder="Title">
              <input type="text" name="description" class="form-control mb-3" placeholder="Description">
              <input type="submit" value="Create Category" class="btn btn-success w-100">
            </form>
          </div>

        </div>
      </div>
    </div>
  <?php
  }

  /* ================= SHOW ================= */
  if ($page == "show" && isset($_GET['id'])) {

    $statement = $connect->prepare("SELECT * FROM categories WHERE id = ?");
    $statement->execute([$_GET['id']]);
    $category = $statement->fetch();
  ?>

    <div class="container my-3 py-5">
      <div class="row justify-content-center">
        <div class="col-md-12 ">

          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Category Details</h3>
          </div>

          <div class="card shadow border-0">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center mb-0">
                  <thead class="table-dark">
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Created At</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?= $category['id'] ?></td>
                      <td><?= htmlspecialchars($category['title']) ?></td>
                      <td><?= htmlspecialchars($category['description']) ?></td>
                      <td><?= $category['created_at'] ?></td>
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

    $statement = $connect->prepare("SELECT * FROM categories WHERE id = ?");
    $statement->execute([$_GET['id']]);
    $category = $statement->fetch();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $title = $_POST['title'];
      $description = $_POST['description'];

      if (empty($title) && empty($description)) {
        $_SESSION['error'] = "Fill All Fields.";
      } elseif (empty($title)) {
        $_SESSION['error'] = "Title is required.";
      } elseif (empty($description)) {
        $_SESSION['error'] = "Description is required.";
      } else {
        $statement = $connect->prepare("UPDATE categories SET title = ?, description = ? WHERE id = ?");
        $statement->execute([$title, $description, $_GET['id']]);
        $_SESSION['message'] = "Updated Successfully.";
        header("Location: categories.php");
        exit();
      }
    }
  ?>

    <div class="container my-5 py-5">
      <div class="row justify-content-center">
        <div class="col-md-6 m-auto">

          <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger shadow-sm rounded-3 text-center py-2 my-3 auto-hide">
              <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <div class="card shadow border-0 p-4">
            <h3 class="text-center mb-4">Edit Category</h3>
            <form method="POST">
              <input type="text" name="title" class="form-control mb-3" value="<?= htmlspecialchars($category['title']) ?>">
              <input type="text" name="description" class="form-control mb-3" value="<?= htmlspecialchars($category['description']) ?>">
              <input type="submit" value="Update Category" class="btn btn-primary w-100">
            </form>
          </div>

        </div>
      </div>
    </div>
  <?php
  }

  /* ================= DELETE ================= */
  if ($page == "delete" && isset($_GET['id'])) {

    $statement = $connect->prepare("DELETE FROM categories WHERE id = ?");
    $statement->execute([$_GET['id']]);
    $_SESSION['message'] = "Deleted Successfully.";
    header("Location: categories.php");
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

<?php include 'includes/temp/footer.php';
} else {
  header("Location: ../login.php");
  $_SESSION['message_login'] = "Login First";
  exit();
}
?>