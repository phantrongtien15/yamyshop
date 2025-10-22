<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['vaitro'] !== 1) {
    header("Location: /streetsoul_store1/view/client/login.php");
    exit;
}

require_once __DIR__ . '/../../config/db.php';
$db = new Database();
$conn = $db->conn;

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="/streetsoul_store1/public/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
        color: #111;
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
    .add-btn {
    display: inline-block;
    background-color: #a7194b; /* Màu đồng bộ với Dashboard */
    color: white;
    padding: 10px 20px; /* Tăng padding để chữ không dính */
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: 0.3s;
    margin-bottom: 15px;
    letter-spacing: 0.5px; /* Tạo khoảng cách nhẹ giữa các ký tự */
}



    .add-btn:hover {
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

    img {
        width: 55px;
        height: 55px;
        border-radius: 8px;
        object-fit: cover;
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
        display: inline-block;
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
        display: inline-block;
    }

    .btn-delete:hover {
        background-color: #e85b0c;
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
            <h2>Danh sách sản phẩm</h2>
            <a href="add.php" class="add-btn">+ Thêm sản phẩm</a>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Giá</th>
                    <th>Hot sale</th>
                    <th>Ảnh</th>
                    <th>Hành động</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= number_format($row['price']) ?> VNĐ</td>
                    <td><?= $row['is_hot_sale'] ? '✔️' : '❌' ?></td>
                    <td><img src="/streetsoul_store1/public/images/<?= htmlspecialchars($row['image']) ?>" alt="ảnh"></td>
                    <td class="actions">
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Sửa</a> |
<a href="delete.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Xóa sản phẩm?')">Xóa</a>

                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
