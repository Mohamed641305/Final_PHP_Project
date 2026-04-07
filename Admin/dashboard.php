<?php
session_start();

if (isset($_SESSION['admin_login'])) {


  include('includes/temp/init.php');

  // جلب عدد المستخدمين
  $q1 = $connect->prepare("SELECT * FROM users");
  $q1->execute();
  $userCount = $q1->rowCount();

  // جلب عدد التصنيفات
  $q2 = $connect->prepare("SELECT * FROM categories");
  $q2->execute();
  $cateCount = $q2->rowCount();

  // جلب عدد المنتجات
  $q3 = $connect->prepare("SELECT * FROM products");
  $q3->execute();
  $productCount = $q3->rowCount();

  // جلب عدد التعليقات
  $q4 = $connect->prepare("SELECT * FROM comments");
  $q4->execute();
  $commentCount = $q4->rowCount();
?>

  <!-- ربط Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f9;
    }

    .head {
      text-align: center;
      margin-bottom: 40px;
    }

    .head h1 {
      font-size: 32px;
      font-weight: 700;
      letter-spacing: -0.5px;
      color: #111827;
    }

    .head h1 em {
      font-style: normal;
      color: #6366f1;
      /* أزرق فاتح */
    }

    .head p {
      font-size: 15px;
      margin-top: 8px;
      color: #6b7280;
    }

    .card-custom {
      border-radius: 22px;
      padding: 30px 20px;
      color: #fff;
      text-align: center;
      cursor: pointer;
      transition: 0.35s;
      position: relative;
      overflow: hidden;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
      min-height: 220px;
    }

    .card-custom:hover {
      transform: translateY(-8px);
      box-shadow: 0 22px 50px rgba(0, 0, 0, 0.15);
    }

    .c1 {
      background: linear-gradient(145deg, #4f46e5, #7c3aed);
    }

    .c2 {
      background: linear-gradient(145deg, #059669, #10b981);
    }

    .c3 {
      background: linear-gradient(145deg, #d97706, #f59e0b);
    }

    .c4 {
      background: linear-gradient(145deg, #dc2626, #ef4444);
    }

    .ic-circle {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      position: relative;
      z-index: 1;
      border: 2px solid rgba(255, 255, 255, 0.25);
      margin: 0 auto 15px;
      transition: transform 0.3s;
    }

    .card-custom:hover .ic-circle {
      transform: scale(1.15);
    }

    .card-lbl {
      font-size: 14px;
      font-weight: 600;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      opacity: 0.85;
      margin-bottom: 10px;
    }

    .card-num {
      font-size: 46px;
      font-weight: 800;
      letter-spacing: -1px;
      line-height: 1;
      margin: 10px 0;
    }

    .card-line {
      width: 36px;
      height: 3px;
      border-radius: 2px;
      background: rgba(255, 255, 255, 0.35);
      margin: 0 auto 20px;
    }

    .card-btn {
      padding: 9px 26px;
      border-radius: 25px;
      background: rgba(255, 255, 255, 0.95);
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      cursor: pointer;
      color: #000;
      display: inline-block;
      transition: 0.2s;
    }

    .c1 .card-btn {
      color: #4f46e5;
    }

    .c2 .card-btn {
      color: #059669;
    }

    .c3 .card-btn {
      color: #d97706;
    }

    .c4 .card-btn {
      color: #dc2626;
    }

    .card-btn:hover {
      transform: scale(1.05);
      background: #fff;
    }

    @media (max-width: 991px) {
      .col-md-6.col-lg-3 {
        flex: 0 0 50%;
        max-width: 50%;
      }
    }

    @media (max-width: 575px) {
      .col-md-6.col-lg-3 {
        flex: 0 0 100%;
        max-width: 100%;
      }

      .card-custom {
        padding: 20px;
        min-height: 200px;
      }

      .ic-circle {
        font-size: 28px;
        width: 60px;
        height: 60px;
      }

      .card-num {
        font-size: 36px;
      }

      .card-lbl {
        font-size: 13px;
      }
    }
  </style>

  <div class="container py-5 my-5">

    <div class="head">
      <h1>Admin <em>Dashboard</em></h1>
      <p>Control your website easily · All systems operational</p>
    </div>

    <div class="row g-4 justify-content-center">

      <div class="col-md-6 col-lg-3">
        <div class="card-custom c1">
          <div class="ic-circle"><i class="fas fa-user"></i></div>
          <div class="card-lbl">Users</div>
          <div class="card-num"><?= $userCount ?></div>
          <div class="card-line"></div>
          <a class="card-btn" href="users.php">Manage</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card-custom c2">
          <div class="ic-circle"><i class="fas fa-layer-group"></i></div>
          <div class="card-lbl">Categories</div>
          <div class="card-num"><?= $cateCount ?></div>
          <div class="card-line"></div>
          <a class="card-btn" href="categories.php">Manage</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card-custom c3">
          <div class="ic-circle"><i class="fas fa-box-open"></i></div>
          <div class="card-lbl">Products</div>
          <div class="card-num"><?= $productCount ?></div>
          <div class="card-line"></div>
          <a class="card-btn" href="products.php">Manage</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card-custom c4">
          <div class="ic-circle"><i class="fas fa-comment"></i></div>
          <div class="card-lbl">Comments</div>
          <div class="card-num"><?= $commentCount ?></div>
          <div class="card-line"></div>
          <a class="card-btn" href="comments.php">Manage</a>
        </div>
      </div>

    </div>
  </div>

<?php include('includes/temp/footer.php');
}else {
  header("Location: ../login.php");
  $_SESSION['message_login'] = "Login First";
} ?>