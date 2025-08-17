<?php
// Đảm bảo session dùng chung toàn site
ini_set('session.cookie_path', '/');
session_start();

// Nếu chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['user']) || $_SESSION['user']['vaitro'] != 1) {
    // Lưu trang hiện tại để quay lại sau khi login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: /streetsoul_store1/view/client/login.php");
    exit;
}

// Kiểm tra ID hợp lệ
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID đơn hàng không hợp lệ.";
    exit;
}

$orderId = (int) $_GET['id'];

try {
    // Kết nối CSDL
    $conn = new PDO(
        "mysql:host=localhost;dbname=streetsoul_store999;charset=utf8",
        "root",
        ""
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lấy trạng thái hiện tại
    $stmt = $conn->prepare("SELECT status FROM orders WHERE id = :id");
    $stmt->execute([':id' => $orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo "Không tìm thấy đơn hàng.";
        exit;
    }

    // Xác định trạng thái tiếp theo
    $currentStatus = $order['status'];
    if ($currentStatus == 'Đang xử lý') {
        $newStatus = 'Đã xác nhận';
    } elseif ($currentStatus == 'Đã xác nhận') {
        $newStatus = 'Đã giao';
    } else {
        // Nếu đã giao thì không thay đổi
        $newStatus = $currentStatus;
    }

    // Cập nhật trạng thái
    $sql = "UPDATE orders SET status = :status WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':status' => $newStatus,
        ':id' => $orderId
    ]);

    // Quay về trang danh sách đơn hàng
    header("Location: /streetsoul_store1/view/admin/orders.php");
    exit;
} catch (PDOException $e) {
    echo "Lỗi: " . htmlspecialchars($e->getMessage());
}
