<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
$database = new Database();
$conn = $database->conn;

// Chỉ cho phép admin
if (!isset($_SESSION['user']) || $_SESSION['user']['vaitro'] != 1) {
    header("Location: /streetsoul_store1/view/client/login.php");
    exit;
}

// Lấy danh sách người dùng
$sql = "SELECT * FROM users ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="stylesheet" href="/streetsoul_store1/view/admin/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
     /* Reset chung */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

* { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { display: flex; background: #f8f9fa; }

        .sidebar { width: 250px; height: 100vh; background: #343a40; color: white; padding: 20px; position: fixed; }
        .sidebar a { display: block; color: white; padding: 10px; text-decoration: none; margin: 10px 0; transition: 0.3s; }
        .sidebar a:hover { background: #495057; }

        .content { margin-left: 270px; padding: 20px; width: 100%; }

        .dashboard-card {
            background: white; padding: 20px; border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1); margin-bottom: 20px;
        }
/* Nội dung */
.content {
    margin-left: 270px;
    padding: 20px;
    width: calc(100% - 270px);
}

h2 {
    margin-bottom: 15px;
    font-size: 22px;
    font-weight: bold;
}

/* Nút thêm */
.btn-add {
    background: #28a745;
    color: white;
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
}
.btn-add:hover {
    background: #218838;
}

/* Bảng */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
th, td {
    padding: 12px;
    text-align: center;
    font-size: 14px;
}
th {
    background: #f1f3f5;
    font-weight: bold;
    color: #333;
    border-bottom: 2px solid #dee2e6;
}
td {
    border-bottom: 1px solid #dee2e6;
}
tr:last-child td {
    border-bottom: none;
}

/* Nút hành động */
.btn-edit {
    background: #0d6efd;
    color: white;
    padding: 5px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 13px;
}
.btn-edit:hover {
    background: #0b5ed7;
}

.btn-delete {
    background: #dc3545;
    color: white;
    padding: 5px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 13px;
}
.btn-delete:hover {
    background: #bb2d3b;
}


        </style>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
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
    <h2>Quản lý người dùng</h2>
    <a href="user_add.php" class="btn btn-add">+ Thêm người dùng</a>
    <br><br>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Giới tính</th>
            <th>Vai trò</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['tendangnhap']) ?></td>
            <td><?= htmlspecialchars($u['hoten']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['phai'] == 1 ? 'Nam' : 'Nữ' ?></td>
            <td><?= $u['vaitro'] == 1 ? 'Admin' : 'User' ?></td>
            <td><?= $u['active'] == 1 ? 'Hoạt động' : 'Khoá' ?></td>
            <td>
                <a href="user_edit.php?id=<?= $u['id'] ?>" class="btn btn-edit">Sửa</a>
                <a href="user_delete.php?id=<?= $u['id'] ?>" class="btn btn-delete" onclick="return confirm('Xác nhận xoá?')">Xoá</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
</body>
</html>
