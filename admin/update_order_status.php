<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['vaitro'] != 1) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: /streetsoul_store1/view/client/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Thiếu ID đơn hàng");
}

$id = (int) $_GET['id'];

try {
    $conn = new PDO("mysql:host=localhost;dbname=streetsoul_store999;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lấy trạng thái hiện tại
    $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt->execute([$id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die("Không tìm thấy đơn hàng");
    }

    $currentStatus = $order['status'];

    // Xác định trạng thái mới
    if ($currentStatus === 'Đang xử lý') {
        $newStatus = 'Đang vận chuyển';
    } elseif ($currentStatus === 'Đang vận chuyển') {
        $newStatus = 'Đã giao hàng';
    } else {
        $newStatus = $currentStatus; // Không đổi nữa nếu đã giao hàng
    }

    // Cập nhật trạng thái
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $id]);

    header("Location: /streetsoul_store1/view/admin/orders.php");
    exit;

} catch (PDOException $e) {
    die("Lỗi DB: " . $e->getMessage());
}
?>
