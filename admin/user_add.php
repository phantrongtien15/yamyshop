<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
$database = new Database();
$conn = $database->conn;

if (!isset($_SESSION['user']) || $_SESSION['user']['vaitro'] != 1) {
    header("Location: /streetsoul_store1/view/client/login.php");
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tendangnhap = trim($_POST['tendangnhap']);
    $matkhau = $_POST['matkhau'];
    $matkhau2 = $_POST['matkhau2'];
    $hoten = trim($_POST['hoten']);
    $email = trim($_POST['email']);
    $phai = intval($_POST['phai']);
    $vaitro = intval($_POST['vaitro']);
    $active = isset($_POST['active']) ? 1 : 0;

    if ($matkhau !== $matkhau2) $errors[] = "Mật khẩu xác nhận không khớp";

    $check = $conn->prepare("SELECT id FROM users WHERE tendangnhap=?");
    $check->bind_param("s", $tendangnhap);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $errors[] = "Tên đăng nhập đã tồn tại";
    }

    if (empty($errors)) {
        $hash = password_hash($matkhau, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (tendangnhap, matkhau, hoten, email, phai, vaitro, active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiii", $tendangnhap, $hash, $hoten, $email, $phai, $vaitro, $active);
        $stmt->execute();
        header("Location: users.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="stylesheet" href="/streetsoul_store1/view/admin/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style> 
* { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { display: flex; background: #f8f9fa; }

        * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, sans-serif;
}

body {
  display: flex;
  background: #f8f9fa;
}

/* ==== Sidebar ==== */
.sidebar {
  width: 220px;
  height: 100vh;
  background-color: #a7194b;
  color: white;
  padding: 25px 20px;
  display: flex;
  flex-direction: column;
  position: fixed;
}

.sidebar h3 {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 40px;
}

.sidebar a {
  color: white;
  text-decoration: none;
  padding: 10px 0;
  font-size: 15px;
  display: block;
  transition: 0.3s;
}

.sidebar a:hover {
  color: #ffe0e9;
  transform: translateX(4px);
}

/* ==== Content ==== */
.content {
  margin-left: 250px;
  padding: 30px;
  width: calc(100% - 250px);
}

/* ==== Form Container ==== */
form {
  width: 100%;
  max-width: 700px;
  margin: 60px auto;
  background: #fff;
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
}

/* ==== Tiêu đề ==== */
h2 {
  text-align: center;
  margin-bottom: 25px;
  color: #a7194b;
  font-size: 26px;
  font-weight: bold;
}

/* ==== Input & Select ==== */
.form-control,
.form-select {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #ddd;
  border-radius: 6px;
  margin-bottom: 18px;
  font-size: 15px;
  transition: border-color 0.3s ease;
}

.form-control:focus,
.form-select:focus {
  border-color: #a7194b;
  outline: none;
  box-shadow: 0 0 3px rgba(167, 25, 75, 0.4);
}

/* ==== Checkbox ==== */
.form-check {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.form-check-input {
  margin-right: 8px;
  accent-color: #a7194b;
}

/* ==== Nút Lưu ==== */
.btn-success {
  background: #a7194b;
  color: #fff;
  border: none;
  padding: 12px 20px;
  border-radius: 8px;
  cursor: pointer;
  width: 100%;
  font-size: 16px;
  font-weight: 600;
  transition: 0.3s;
}

.btn-success:hover {
  background: #8c123d;
}

/* ==== Thông báo lỗi ==== */
.alert-danger {
  max-width: 700px;
  margin: 15px auto;
  padding: 14px;
  background: #f8d7da;
  color: #721c24;
  border-radius: 6px;
  border: 1px solid #f5c2c7;
}

/* ==== Responsive ==== */
@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    height: auto;
    position: relative;
  }

  .content {
    margin-left: 0;
    padding: 20px;
    width: 100%;
  }

  form {
    margin: 30px 10px;
    padding: 25px;
  }
}

    </style>

    <meta charset="UTF-8">
    <title>Thêm người dùng</title>
</head>

<body>

<div class="sidebar">
    <h3>Quản trị</h3>
    <a href="/streetsoul_store1/index.php"><i class="fa fa-home"></i> Trang chủ</a>
    <a href="/streetsoul_store1/view/admin/orders.php"><i class="fa fa-shopping-cart"></i> Quản lý đơn hàng</a>
    <a href="/streetsoul_store1/view/admin/users.php"><i class="fa fa-user"></i> Quản lý người dùng</a>
    <a href="/streetsoul_store1/view/admin/products.php"><i class="fa fa-box"></i> Quản lý sản phẩm</a>
    <a href="/streetsoul_store1/view/client/logout.php"><i class="fa fa-sign-out"></i> Đăng xuất</a>
</div>
    
<?php if ($errors): ?>
<div class="alert alert-danger"><ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>

</div>
<?php endif; ?>

<form method="post">
        <h2>Thêm người dùng</h2>
    <input name="tendangnhap" class="form-control mb-2" placeholder="Tên đăng nhập" required>
    <input type="password" name="matkhau" class="form-control mb-2" placeholder="Mật khẩu" required>
    <input type="password" name="matkhau2" class="form-control mb-2" placeholder="Xác nhận mật khẩu" required>
    <input name="hoten" class="form-control mb-2" placeholder="Họ tên">
    <input name="email" type="email" class="form-control mb-2" placeholder="Email">
    <select name="phai" class="form-select mb-2">
        <option value="0">Nữ</option>
        <option value="1">Nam</option>
    </select>
    <select name="vaitro" class="form-select mb-2">
        <option value="0">Người dùng</option>
        <option value="1">Admin</option>
    </select>
    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="active" checked> <label>Hoạt động</label>
    </div>
    <button class="btn btn-success">Lưu</button>
</form>
</body>
</html>
