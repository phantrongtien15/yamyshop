<?php
// model/order.model.php
include_once __DIR__ . '/../config/db.php';

function insertOrder($name, $phone, $address, $cart, $shippingFee) {
    $database = new Database();
    $conn = $database->conn;

    // Bắt đầu transaction
    $conn->begin_transaction();

    try {
        // Tính tổng giá trị đơn hàng
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $grandTotal = $total + $shippingFee;

        // Insert vào bảng orders (thêm cột status)
        $sql = "INSERT INTO orders (customer_name, phone, address, total_price, shipping_fee, status, created_at)
                VALUES (?, ?, ?, ?, ?, 'Đang xử lý', NOW())";
        
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception('❌ Lỗi chuẩn bị SQL: ' . $conn->error);
        }

        // s = string, d = double/float
        $stmt->bind_param("sssdd", $name, $phone, $address, $grandTotal, $shippingFee);
        if (!$stmt->execute()) {
            throw new Exception('❌ Lỗi khi insert orders: ' . $stmt->error);
        }

        $order_id = $conn->insert_id;
        $stmt->close();

        // Insert chi tiết đơn hàng
        $sqlDetail = "INSERT INTO order_details (order_id, product_id, product_name, quantity, price)
                      VALUES (?, ?, ?, ?, ?)";
        $stmtDetail = $conn->prepare($sqlDetail);
        if ($stmtDetail === false) {
            throw new Exception('❌ Lỗi chuẩn bị SQL chi tiết: ' . $conn->error);
        }

        foreach ($cart as $item) {
            $stmtDetail->bind_param("iisid", $order_id, $item['id'], $item['name'], $item['quantity'], $item['price']);
            if (!$stmtDetail->execute()) {
                throw new Exception('❌ Lỗi insert order_details: ' . $stmtDetail->error);
            }
        }

        $stmtDetail->close();

        // Commit transaction
        $conn->commit();

        return $order_id;

    } catch (Exception $e) {
        // Rollback nếu có lỗi
        $conn->rollback();
        die($e->getMessage());
    }
}
?>
