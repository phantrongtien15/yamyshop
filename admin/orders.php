<?php
// Giữ session trên toàn website
ini_set('session.cookie_path', '/');
session_start();

// Chỉ admin mới được truy cập
if (!isset($_SESSION['user']) || $_SESSION['user']['vaitro'] != 1) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI']; // lưu lại URL để quay lại sau khi login
    header("Location: /streetsoul_store1/view/client/login.php");
    exit;
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=streetsoul_store999;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM orders ORDER BY order_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    echo "<p style='color: red;'>Lỗi kết nối: " . htmlspecialchars($e->getMessage()) . "</p>";
    $orders = [];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="/streetsoul_store1/public/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { display: flex; background: #f8f9fa; font-family: Arial, sans-serif; margin: 0; }
        .sidebar { width: 250px; height: 100vh; background: #343a40; color: white; padding: 20px; position: fixed; }
        .sidebar a { display: block; color: white; padding: 10px; text-decoration: none; margin: 10px 0; transition: 0.3s; }
        .sidebar a:hover { background: #495057; }
        .content { margin-left: 270px; padding: 20px; width: 100%; }
        .dashboard-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; }
        th { background: #f1f1f1; }
        tr:nth-child(even) { background: #f9f9f9; }
        .btn { display: inline-block; padding: 6px 10px; background: white; color: black; border-radius: 5px; border: 1px solid black; text-decoration: none; }
        .btn:hover { background: #1e7e34; color: white; }
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
            <h2>Quản lý đơn hàng</h2>
            <p>Theo dõi và xác nhận đơn hàng tại đây.</p>
        </div>

        <?php if (!empty($orders)): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Ngày đặt</th>
                    <th>Tên khách hàng</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['id']) ?></td>
                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td>
                        <?php if ($order['status'] !== 'Đã giao hàng'): ?>
                            <a class="btn" href="/streetsoul_store1/view/admin/update_order_status.php?id=<?= urlencode($order['id']) ?>">Xác nhận</a>
                        <?php else: ?>
                            <span>Đã giao</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="dashboard-card">
                <p style="text-align:center; color:#777;">Không có đơn hàng nào.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
