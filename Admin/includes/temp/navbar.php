<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top admin-navbar">
  <div class="container">

    <a class="navbar-brand" href="dashboard.php">
      <i class="fa-solid fa-gauge-high"></i> Admin Panel
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active-link" href="dashboard.php">
            <i class="fa-solid fa-house"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="users.php">
            <i class="fa-solid fa-users"></i> Users
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categories.php">
            <i class="fa-solid fa-layer-group"></i> Categories
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="products.php">
            <i class="fa-solid fa-box-open"></i> Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="comments.php">
            <i class="fa-solid fa-comments"></i> Comments
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link logout" href="logout.php">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
          </a>
        </li>
      </ul>
    </div>

  </div>
</nav>

<style>
  /* Navbar عصري وجذاب */
  .admin-navbar {
    background: #1e1e2f;
    padding: 14px 0;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
    border-bottom: 3px solid #ffcc00;
    transition: all 0.3s ease;
  }

  .navbar-brand {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 22px;
    color: #ffcc00 !important;
    letter-spacing: 0.5px;
  }

  .nav-link {
    font-family: 'Inter', sans-serif;
    color: #f1f1f1 !important;
    margin-left: 18px;
    font-weight: 600;
    position: relative;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
  }

  .nav-link i {
    margin-right: 6px;
    font-size: 16px;
    transition: 0.3s;
  }

  /* تأثير hover ديناميكي */
  .nav-link:hover i {
    transform: rotate(10deg) scale(1.1);
    color: #ffcc00;
  }

  .nav-link:hover {
    color: #ffcc00 !important;
  }

  .nav-link::after {
    content: '';
    position: absolute;
    width: 0%;
    height: 2px;
    left: 0;
    bottom: -4px;
    background: #ffcc00;
    border-radius: 2px;
    transition: 0.3s;
  }

  .nav-link:hover::after,
  .nav-link.active-link::after {
    width: 100%;
  }

  .logout {
    color: #ff6b6b !important;
    font-weight: 600;
  }

  .logout:hover {
    color: #ff3b3b !important;
  }

  body {
    padding-top: 70px;
    background: #f4f5f8;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .nav-link {
      margin-left: 0;
      padding: 8px 0;
    }
  }

</style>