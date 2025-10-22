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

    $sql = "SELECT * FROM orders ORDER BY created_at DESC";
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
    <link rel="stylesheet" href="/streetsoul_store1/view/admin/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { display: flex; background: #f8f9fa; font-family: Arial, sans-serif; margin: 0; }
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

    /* ==== Main content ==== */
    .content {
        margin-left: 250px;
        padding: 30px;
        width: calc(100% - 250px);
    }

    h2 {
        font-size: 26px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    p {
        color: #444;
        margin-bottom: 20px;
    }

    /* ==== Card ==== */
    .dashboard-card {
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    /* ==== Table ==== */
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
        text-align: left;
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

    /* ==== Button ==== */
    .btn {
        display: inline-block;
        padding: 6px 12px;
        background-color: #a7194b;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        transition: 0.3s;
    }

    .btn:hover {
        background-color: #8c123d;
    }

    .status {
        font-weight: bold;
    }

    .status.pending { color: #ff6b1a; }
    .status.done { color: #1aa14a; }
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
                    <td><?= htmlspecialchars($order['created_at']) ?></td>
                    <td><?= htmlspecialchars($order['name']) ?></td>
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
