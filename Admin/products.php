<?php
session_start();
if (isset($_SESSION['admin_login'])) {


  include 'includes/temp/init.php';



  $page = isset($_GET['page']) ? $_GET['page'] : 'All';

  /* ================== All ================== */
  if ($page == "All") {

    $statement = $connect->prepare("SELECT * FROM products");
    $statement->execute();
    $products = $statement->fetchAll();
    $productCount = $statement->rowCount();
?>

    <div class="container my-3 py-5">
      <div class="row justify-content-center">
        <div class="col-md-12 ">

          <!-- رسالة النجاح -->
          <?php if (!empty($_SESSION['message'])): ?>
            <div class="alert alert-success text-center py-2 my-3">
              <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
          <?php endif; ?>

          <!-- رسالة الخطأ -->
          <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center py-2 my-3">
              <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Products <span class="badge bg-primary"><?= $productCount ?></span></h3>
            <a href="?page=create" class="btn btn-success shadow-sm">+ Add Product</a>
          </div>

          <div class="card shadow border-0">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center mb-0">
                  <thead class="table-dark">
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Price</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php if ($productCount > 0): ?>
                      <?php foreach ($products as $row): ?>
                        <tr>
                          <td><?= $row['id'] ?></td>
                          <td><?= htmlspecialchars($row['title']) ?></td>
                          <td><?= $row['price'] ?></td>
                          <td>
                            <a href="?page=show&id=<?= $row['id'] ?>" class="btn btn-sm btn-success me-1"><i class="fas fa-eye"></i></a>
                            <a href="?page=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary me-1"><i class="fas fa-edit"></i></a>
                            <a href="?page=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this product?');"><i class="fas fa-trash"></i></a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4">No products found.</td>
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

  /* ================== Create ================== */
  if ($page == "create") {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $title = $_POST['title'];
      $description = $_POST['description'];
      $category = $_POST['category'];
      $price = $_POST['price'];

      if (empty($title) && empty($description) && empty($category) && empty($price)) {
        $_SESSION['error'] = "Fill All Fields.";
      } elseif (empty($title)) {
        $_SESSION['error'] = "Title is required.";
      } elseif (empty($description)) {
        $_SESSION['error'] = "Description is required.";
      } elseif (empty($category)) {
        $_SESSION['error'] = "Category is required.";
      } elseif (empty($price)) {
        $_SESSION['error'] = "Price is required.";
      } else {
        $stmtement = $connect->prepare("INSERT INTO products (title, `description`, category_name, price) VALUES (?, ?, ?, ?)");
        $stmtement->execute([$title, $description, $category, $price]);
        $_SESSION['message'] = "Created Successfully.";
        header("Location: products.php");
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
            <h3 class="text-center mb-4">Add New Product</h3>
            <form method="POST">
              <input type="text" name="title" class="form-control mb-3" placeholder="Title">
              <input type="text" name="description" class="form-control mb-3" placeholder="Description">
              <input type="text" name="category" class="form-control mb-3" placeholder="Category">
              <input type="number" name="price" class="form-control mb-3" placeholder="Price">
              <input type="submit" value="Create Product" class="btn btn-success w-100">
            </form>
          </div>

        </div>
      </div>
    </div>
  <?php
  }

  /* ================== Show ================== */
  if ($page == "show" && isset($_GET['id'])) {

    $statement = $connect->prepare("SELECT * FROM products WHERE id = ?");
    $statement->execute([$_GET['id']]);
    $product = $statement->fetch();
  ?>

    <div class="container my-3 py-5">
      <div class="row justify-content-center">
        <div class="col-md-12 ">

          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Product Details</h3>
          </div>

          <div class="card shadow border-0">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center mb-0">
                  <thead class="table-dark">
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Category</th>
                      <th>Description</th>
                      <th>Price</th>
                      <th>Created At</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php if ($product): ?>
                      <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['title']) ?></td>
                        <td><?= htmlspecialchars($product['category_name']) ?></td>
                        <td><?= htmlspecialchars($product['description']) ?></td>
                        <td><?= $product['price'] ?></td>
                        <td><?= $product['created_at'] ?></td>
                        <td>
                          <a href="products.php" class="btn btn-sm btn-success">
                            <i class="fas fa-house"></i>
                          </a>
                        </td>
                      </tr>
                    <?php else: ?>
                      <tr>
                        <td colspan="7">Product not found.</td>
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

  /* ================== Edit ================== */
  if ($page == "edit" && isset($_GET['id'])) {

    $stmtement = $connect->prepare("SELECT * FROM products WHERE id = ?");
    $stmtement->execute([$_GET['id']]);
    $product = $stmtement->fetch();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $title = $_POST['title'];
      $description = $_POST['description'];
      $category = $_POST['category'];
      $price = $_POST['price'];

      if (empty($title) && empty($description) && empty($category) && empty($price)) {
        $_SESSION['error'] = "Fill All Fields.";
      } elseif (empty($title)) {
        $_SESSION['error'] = "Title is required.";
      } elseif (empty($description)) {
        $_SESSION['error'] = "Description is required.";
      } elseif (empty($category)) {
        $_SESSION['error'] = "Category is required.";
      } elseif (empty($price)) {
        $_SESSION['error'] = "Price is required.";
      } else {
        $stmtement = $connect->prepare("UPDATE products SET title = ?, `description` = ?, category_name = ?, price = ? WHERE id = ?");
        $stmtement->execute([$title, $description, $category, $price, $_GET['id']]);
        $_SESSION['message'] = "Updated Successfully.";
        header("Location: products.php");
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
            <h3 class="text-center mb-4">Edit Product</h3>
            <form method="POST">
              <input type="text" name="title" class="form-control mb-3" value="<?= htmlspecialchars($product['title']) ?>">
              <input type="text" name="description" class="form-control mb-3" value="<?= htmlspecialchars($product['description']) ?>">
              <input type="text" name="category" class="form-control mb-3" value="<?= htmlspecialchars($product['category_name']) ?>">
              <input type="number" name="price" class="form-control mb-3" value="<?= $product['price'] ?>" step="0.01">
              <input type="submit" value="Update Product" class="btn btn-primary w-100">
            </form>
          </div>

        </div>
      </div>
    </div>
  <?php
  }

  /* ================== Delete ================== */
  if ($page == "delete" && isset($_GET['id'])) {
    $statement = $connect->prepare("DELETE FROM products WHERE id = ?");
    $statement->execute([$_GET['id']]);
    $_SESSION['message'] = "Deleted Successfully.";
    header("Location: products.php");
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