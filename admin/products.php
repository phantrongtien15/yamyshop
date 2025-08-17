<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['vaitro'] != 1) {
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

        .sidebar { width: 250px; height: 100vh; background: #343a40; color: white; padding: 20px; position: fixed; }
        .sidebar a { display: block; color: white; padding: 10px; text-decoration: none; margin: 10px 0; transition: 0.3s; }
        .sidebar a:hover { background: #495057; }

        .content { margin-left: 270px; padding: 20px; width: 100%; }

        .dashboard-card {
            background: white; padding: 20px; border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1); margin-bottom: 20px;
        }

        h2 { text-align: center; color: #333; margin-bottom: 20px; }

        .add-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .add-btn:hover { background-color: #218838; text-decoration: none; }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th { background-color: #f2f2f2; color: #333; }
        img { width: 50px; height: auto; border-radius: 4px; }
        .actions { white-space: nowrap; }
        .btn {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            margin: 2px;
        }
        .edit-btn { background-color: #007bff; }
        .edit-btn:hover { background-color: #0056b3; text-decoration: none; }
        .delete-btn { background-color: #dc3545; }
        .delete-btn:hover { background-color: #a71d2a; text-decoration: none; }
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
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn edit-btn">Sửa</a> |
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn delete-btn" onclick="return confirm('Xóa sản phẩm?')">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
