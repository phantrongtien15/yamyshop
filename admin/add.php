<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['vaitro'] !== 1) {
    header("Location: /streetsoul_store1/view/client/login.php");
    exit;
}

require_once __DIR__ . '/../../config/db.php';
$db = new Database();
$conn = $db->conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $imageName = $_FILES['image']['name'];
    $imageTmp  = $_FILES['image']['tmp_name'];
    $imageError = $_FILES['image']['error'];

    $uploadDir = __DIR__ . '/../../public/images/';
    $imagePath = '';

    if ($imageError === 0) {
        $imagePath = uniqid() . '_' . basename($imageName);
        $destination = $uploadDir . $imagePath;

        if (move_uploaded_file($imageTmp, $destination)) {
            $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sds", $name, $price, $imagePath);
            $stmt->execute();
            $stmt->close();

            header("Location: products.php");
            exit;
        } else {
            echo "<p style='color:red; text-align:center;'>Không thể lưu ảnh lên máy chủ.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>Lỗi khi chọn ảnh.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="/streetsoul_store1/public/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
 /* ===== RESET ===== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Segoe UI", Arial, sans-serif;
}

/* ===== LAYOUT ===== */
body {
  display: flex;
  background-color: #f6f7f9;
  color: #333;
  min-height: 100vh;
}

/* ===== SIDEBAR ===== */
.sidebar {
  width: 250px;
  background-color: #9b1c49;
  color: #fff;
  display: flex;
  flex-direction: column;
  padding: 30px 20px;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
}

.sidebar h3 {
  font-size: 26px;
  font-weight: 700;
  text-align: left;
  margin-bottom: 35px;
}

.sidebar a {
  display: flex;
  align-items: center;
  color: #fff;
  text-decoration: none;
  padding: 12px 15px;
  margin: 5px 0;
  border-radius: 8px;
  transition: 0.3s;
  font-size: 15px;
}

.sidebar a i {
  margin-right: 10px;
}

.sidebar a:hover {
  background-color: #7f1740;
  transform: translateX(4px);
}

/* ===== MAIN CONTENT ===== */
.content {
  flex: 1;
  margin-left: 270px;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 20px;
}

/* ===== FORM CARD ===== */
.dashboard-card {
  background-color: #fff;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  padding: 40px 50px;
  width: 100%;
  max-width: 650px;
  transition: 0.3s;
}

.dashboard-card h2 {
  text-align: center;
  color: #9b1c49;
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 30px;
}

/* ===== FORM INPUTS ===== */
form label {
  display: block;
  font-weight: 600;
  font-size: 14px;
  color: #444;
  margin-top: 15px;
}

form input[type="text"],
form input[type="number"],
form input[type="file"],
form textarea,
form select {
  width: 100%;
  margin-top: 8px;
  padding: 12px 14px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 15px;
  background-color: #fff;
  transition: border-color 0.3s, box-shadow 0.3s;
}

form input:focus,
form select:focus,
form textarea:focus {
  border-color: #9b1c49;
  box-shadow: 0 0 5px rgba(155,28,73,0.3);
  outline: none;
}

/* ===== FIELDSET (Khu vực giao hàng) ===== */
fieldset {
  border: 1px solid #eee;
  border-radius: 10px;
  background-color: #fafafa;
  padding: 15px 20px;
  margin-top: 18px;
}

legend {
  font-weight: 600;
  font-size: 15px;
  color: #9b1c49;
}

/* ===== BUTTON ===== */
form button {
  margin-top: 25px;
  width: 100%;
  padding: 14px;
  border: none;
  background-color: #9b1c49;
  color: #fff;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s;
}

form button:hover {
  background-color: #7f1740;
}

/* ===== BACK LINK ===== */
.back-link {
  margin-top: 20px;
  text-align: center;
}

.back-link a {
  color: #9b1c49;
  text-decoration: none;
  font-weight: 500;
  transition: 0.3s;
}

.back-link a:hover {
  text-decoration: underline;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .sidebar {
    width: 200px;
    padding: 20px;
  }
  .content {
    margin-left: 220px;
    padding: 20px;
  }
  .dashboard-card {
    padding: 25px;
  }
}
    </style>
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

    <div class="content">
    <div class="dashboard-card">
        <h2>Thêm sản phẩm mới</h2>
        <form method="post" enctype="multipart/form-data">
            <!-- 1. Ảnh sản phẩm -->
            <label for="image">Ảnh sản phẩm:</label>
            <input type="file" name="image" accept="image/*" required>

            <!-- 2. Tên sản phẩm -->
            <label for="name">Tên sản phẩm:</label>
            <input type="text" name="name" placeholder="Ví dụ: Áo Thun Mẫu Chữ - Trắng" required>

            <!-- 3. Giá -->
            <label for="price">Giá (VND):</label>
            <input type="number" name="price" placeholder="Ví dụ: 400000" required>

            <!-- 4. Khu vực giao hàng -->
            <fieldset>
                <legend>Khu vực giao hàng:</legend>
                
                <label>Hà Nội:</label>
                <select name="stock_hanoi" required>
                    <option value="con">Còn hàng</option>
                    <option value="het">Hết hàng</option>
                </select>

                <label>TP. Hồ Chí Minh:</label>
                <select name="stock_hcm" required>
                    <option value="con">Còn hàng</option>
                    <option value="het">Hết hàng</option>
                </select>

                <label>Đà Nẵng:</label>
                <select name="stock_danang" required>
                    <option value="con">Còn hàng</option>
                    <option value="het">Hết hàng</option>
                </select>

                <label>Cần Thơ:</label>
                <select name="stock_cantho" required>
                    <option value="con">Còn hàng</option>
                    <option value="het">Hết hàng</option>
                </select>
            </fieldset>

            <!-- 5. Mô tả sản phẩm -->
            <label for="description">Mô tả sản phẩm:</label>
            <textarea name="description" rows="4" placeholder="Mô tả chi tiết sản phẩm" required></textarea>

            <button type="submit">Thêm sản phẩm</button>
        </form>

        <div class="back-link">
            <a href="products.php">← Quay lại danh sách sản phẩm</a>
        </div>
    </div>
</div>

    </div>
</body>
</html>
