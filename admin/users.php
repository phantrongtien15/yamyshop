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

    /* ==== Main ==== */
    .content {
        margin-left: 250px;
        padding: 30px;
        width: calc(100% - 250px);
    }

    h2 {
        font-size: 26px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    /* ==== Card ==== */
    .dashboard-card {
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    /* ==== Nút thêm ==== */
    .btn-add {
        display: inline-block;
        background-color: #8c123d;
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        transition: 0.3s;
    }

    .btn-add:hover {
        background-color: #ff6b1a;
    }

    /* ==== Bảng ==== */
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }

    th, td {
        padding: 14px 18px;
        text-align: center;
        font-size: 14px;
    }

    th {
        background-color: #a7194b;
        color: white;
        font-weight: 600;
    }

    tr:nth-child(even) {
        background-color: #f8f8f8;
    }

    tr:hover {
        background-color: #fde6ef;
    }

    /* ==== Nút hành động ==== */
    .btn-edit {
        background-color: #a7194b;
        color: white;
        padding: 6px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-edit:hover {
        background-color: #8c123d;
    }

    .btn-delete {
        background-color: #ff6b1a;
        color: white;
        padding: 6px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-delete:hover {
        background-color: #e85b0c;
    }

    /* ==== Trạng thái ==== */
    .status-active { color: #1aa14a; font-weight: bold; }
    .status-locked { color: #dc3545; font-weight: bold; }

</style>



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

