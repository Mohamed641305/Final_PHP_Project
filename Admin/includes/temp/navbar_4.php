<!-- navbar.php -->
<nav class="navbar navbar-expand-lg fixed-top custom-navbar">
  <div class="container d-flex align-items-center">

    <!-- الشعار -->
    <a class="navbar-brand" href="dashboard.php">
      <i class="fa-solid fa-gauge-high"></i> Admin Panel
    </a>

    <!-- زر Toggle للموبايل -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- القائمة -->
    <div class="collapse navbar-collapse " id="navbarResponsive">
      <ul class="navbar-nav ms-auto d-flex flex-row align-items-center flex-wrap ">
        <li class="nav-item"><a class="nav-link active-link" href="dashboard_4.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="users_4.php"><i class="fa-solid fa-users"></i> Users</a></li>
        <li class="nav-item"><a class="nav-link" href="categories_4.php"><i class="fa-solid fa-layer-group"></i> Categories</a></li>
        <li class="nav-item"><a class="nav-link logout" href="logout_4.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
      </ul>
    </div>

  </div>
</nav>

<style>
  .custom-navbar {
    background: #2c3e50;
    padding: 5px 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border-bottom: 3px solid #f1c40f;
  }

  .navbar-brand {
    font-size: 18px;
    font-weight: 700;
    color: #f1c40f !important;
  }

  .navbar-nav .nav-link {
    padding: 8px 15px;
    margin-left: 10px;
    color: #ecf0f1;
    font-weight: 600;
    display: flex;
    align-items: center;
    border-radius: 5px;
    transition: all 0.2s;
  }

  .navbar-nav .nav-link:hover {
    color: #f1c40f !important;
    background: rgba(241, 196, 15, 0.1);
    /* ظل خفيف عند hover */
  }

  .navbar-nav .nav-link i {
    margin-right: 8px;
    font-size: 14px;
  }

  .logout {
    color: #e74c3c !important;
  }

  .logout:hover {
    color: #c0392b !important;
  }

  body {
    padding-top: 50px;
    background: #f4f6f9;
  }

  /* --- تعديل الموبايل --- */
  @media(max-width:991px) {
    .navbar-nav {
      flex-direction: column !important;
      /* اجعلها عمودية */
      align-items: flex-start;
      /* تلتصق بالشمال */
      width: 100%;
      margin-top: 5px;
    }

    .navbar-nav .nav-link {
      margin-left: 0;
      padding: 10px 15px;
      width: 100%;
      /* اجعل الرابط يمتد كامل عرض العمود */
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      /* فاصل بين الروابط */
    }

    .navbar-nav .nav-link:last-child {
      border-bottom: none;
      /* آخر رابط بدون فاصل */
    }
  }
</style>