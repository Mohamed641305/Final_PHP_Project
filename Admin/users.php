<?php
session_start();
if (isset($_SESSION['admin_login'])) {


    include 'includes/temp/init.php';

    $page = isset($_GET['page']) ? $_GET['page'] : "All";

    /* ================== All ================== */
    if ($page === "All") {

        $stmt = $connect->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll();
        $count = $stmt->rowCount();
?>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-12">

                    <!-- رسالة نجاح -->
                    <?php if (!empty($_SESSION['message'])): ?>
                        <div class="alert alert-success text-center py-2 my-3 auto-hide">
                            <?= htmlspecialchars($_SESSION['message']) ?>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold">
                            Users <span class="badge bg-primary"><?= $count ?></span>
                        </h3>
                        <a href="?page=create" class="btn btn-success shadow-sm">+ Add User</a>
                    </div>

                    <div class="card shadow border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle text-center mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php if ($count > 0): ?>
                                            <?php foreach ($users as $row): ?>
                                                <tr>
                                                    <td><?= $row['id'] ?></td>
                                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                                    <td>
                                                        <a href="?page=show&id=<?= $row['id'] ?>" class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                                        <a href="?page=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                                        <a href="?page=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?');"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4">No users found</td>
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
    if ($page === "create") {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username']);
            $email    = trim($_POST['email']);
            $password = trim($_POST['password']);
            $role     = $_POST['role'];
            $status   = $_POST['status'];

            if (empty($username) || empty($email) || empty($password)) {
                $_SESSION['error'] = "Please fill all fields!";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email!";
            } elseif (strlen($password) < 6) {
                $_SESSION['error'] = "Password too short!";
            } else {
                $stmt = $connect->prepare("INSERT INTO users (username, email, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$username, $email, $password, $role, $status]);

                $_SESSION['message'] = "User Created Successfully";
                header("Location: users.php");
                exit();
            }
        }
    ?>

        <div class="container mt-5 pt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">

                    <!-- رسالة خطأ -->
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center py-2 my-3 auto-hide">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <div class="card shadow border-0 p-4">
                        <h3 class="text-center mb-4">Add New User</h3>

                        <form method="POST">
                            <input type="text" name="username" class="form-control mb-3" placeholder="Username">
                            <input type="email" name="email" class="form-control mb-3" placeholder="Email">
                            <input type="password" name="password" class="form-control mb-3" placeholder="Password">

                            <select name="role" class="form-control mb-3">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>

                            <select name="status" class="form-control mb-3">
                                <option value="1">Active</option>
                                <option value="0">Blocked</option>
                            </select>

                            <button class="btn btn-success w-100">Create User</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    <?php
    }

    /* ================= SHOW ================= */
    if ($page == "show" && isset($_GET['id'])) {

        $statement = $connect->prepare("SELECT * FROM users WHERE id = ?");
        $statement->execute([$_GET['id']]);
        $user = $statement->fetch();
    ?>

        <div class="container my-3 py-5">
            <div class="row justify-content-center">
                <div class="col-md-12 ">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold">User Details</h3>
                    </div>

                    <div class="card shadow border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle text-center mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Created_at</th>
                                            <th>Updated_at</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= $user['id'] ?></td>
                                            <td><?= htmlspecialchars($user['username']) ?></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td><?= htmlspecialchars($user['password']) ?></td>
                                            <td><?= htmlspecialchars($user['role']) ?></td>
                                            <td><?= $user['status'] == 1 ? 'Active' : 'Blocked' ?></td>
                                            <td><?= $user['created_at'] ?></td>
                                            <td><?= $user['updated_at'] ?></td>
                                            <td>
                                                <a href="users.php" class="btn btn-sm btn-success"><i class="fas fa-house"></i></a>
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

    /* ================== Edit ================== */
    if ($page === "edit" && isset($_GET['id'])) {

        $stmt = $connect->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$_GET['id']]);
        $user = $stmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $status = $_POST['status'];

            if (empty($username) && empty($email) && empty($password) && empty($role) && empty($status)) {
                $_SESSION['error'] = "Fill All Fields.";
            } elseif (empty($username)) {
                $_SESSION['error'] = "Username is required.";
            } elseif (empty($email)) {
                $_SESSION['error'] = "Email is required.";
            } elseif (empty($password)) {
                $_SESSION['error'] = "Password is required.";
            } elseif (empty($role)) {
                $_SESSION['error'] = "Role is required.";
            } elseif (empty($status)) {
                $_SESSION['error'] = "Status is required.";
            } else {
                $stmt = $connect->prepare("UPDATE users SET username=?, email=?, `password`=?, `role`=?, `status`=? WHERE id=?");
                $stmt->execute([$username, $email, $password, $role, $status, $_GET['id']]);

                $_SESSION['message'] = "Updated Successfully";
                header("Location: users.php");
                exit();
            }
        }
    ?>
        <div class="container mt-5 pt-5">
            <div class="col-md-6 m-auto">
                <!-- رسالة خطأ -->
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger shadow-sm rounded-3 text-center py-2 my-3 auto-hide">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="card p-4 shadow">
                    <h3 class="text-center mb-3">Edit User</h3>

                    <form method="POST">
                        <input type="text" name="username" class="form-control mb-3" value="<?= htmlspecialchars($user['username']) ?>">
                        <input type="email" name="email" class="form-control mb-3" value="<?= htmlspecialchars($user['email']) ?>">
                        <input type="text" name="password" class="form-control mb-3" value="<?= htmlspecialchars($user['password']) ?>">

                        <select name="role" class="form-control mb-3">
                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>

                        <select name="status" class="form-control mb-3">
                            <option value="1" <?= $user['status'] == 1 ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= $user['status'] == 0 ? 'selected' : '' ?>>Blocked</option>
                        </select>

                        <button class="btn btn-primary w-100">Update</button>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }

    /* ================== Delete ================== */
    if ($page === "delete" && isset($_GET['id'])) {
        $stmt = $connect->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$_GET['id']]);

        $_SESSION['message'] = "Deleted Successfully";
        header("Location: users.php");
        exit();
    }
    ?>

    <!-- سكربت موحد -->
    <script>
        // هذا السكربت لإخفاء رسائل النجاح أو الخطأ بعد 3 ثواني
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