<?php
// Kiểm tra quyền truy cập
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['vaitro'] !== 1) {
    header("Location: /streetsoul_store1/view/client/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/streetsoul_store1/public/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
            body {
        display: flex;
        background-color: #fff;
    }

    /* ==== Sidebar ==== */
    .sidebar {
        width: 200px;
        height: 100vh;
        background-color: #a7194b; /* màu hồng đậm */
        color: white;
        padding: 25px 20px;
        display: flex;
        flex-direction: column;
        position: fixed;
    }

    .sidebar h2 {
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
    .main {
        margin-left: 250px;
        width: calc(100% - 250px);
        padding: 30px;
    }

    .main h1 {
        font-size: 26px;
        font-weight: bold;
        margin-bottom: 30px;
    }

    /* ==== Thống kê ==== */
    .stats {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        flex: 1;
        color: white;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }

    .card h3 {
        font-size: 16px;
        margin-bottom: 8px;
    }

    .card p {
        font-size: 22px;
        font-weight: bold;
    }

    .card.red { background-color: #b6194a; }
    .card.blue { background-color: #2049ff; }
    .card.orange { background-color: #ff6b1a; }

    /* ==== Biểu đồ ==== */
    .chart-section {
        display: flex;
        gap: 20px;
    }

    .chart-box {
        flex: 1;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 20px;
        min-height: 300px;
    }

    .chart-box h4 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
    }
</style>

</head> 
<body> 
    <!-- Sidebar --> 
     <div class="sidebar"> <h2>Quản trị</h2> 
     <a href="/streetsoul_store1/view/admin/dashboard.php"><i class="fa fa-home"></i> Trang chủ</a> 
     <a href="/streetsoul_store1/view/admin/orders.php"><i class="fa fa-shopping-cart"></i> Quản lý đơn hàng</a> 
     <a href="/streetsoul_store1/view/admin/users.php"><i class="fa fa-user"></i> Quản lý người dùng</a> 
     <a href="/streetsoul_store1/view/admin/products.php"><i class="fa fa-box"></i> Quản lý sản phẩm</a> 
     <a href="/streetsoul_store1/view/client/logout.php"><i class="fa fa-sign-out"></i> Đăng xuất</a> </div>
    <!-- Main -->
<div class="main">
    <h1>Chào Mừng <?= htmlspecialchars($_SESSION['user']['hoten']) ?>!</h1>
    <p>Ở đây bạn có thể quản lý các đơn hàng, người dùng và các chức năng khác.</p>
    <!-- Thống kê -->
    <div class="stats">
        <div class="card red">
            <h3>Tổng doanh thu:</h3>
            <p>200,000 VND</p>
        </div>
        <div class="card blue">
            <h3>Tổng đơn hàng:</h3>
            <p>1</p>
        </div>
        <div class="card orange">
            <h3>Số sản phẩm:</h3>
            <p>1</p>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="chart-section">
        <div class="chart-box">
            <h4>Biểu đồ thống kê đơn hàng</h4>
            <canvas id="orderChart"></canvas>
        </div>
        <div class="chart-box">
            <h4>Top sản phẩm có lượt yêu thích nhiều nhất</h4>
            <canvas id="favoriteChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js scripts -->
<script>
    // Biểu đồ đơn hàng theo tháng
    const ctxOrder = document.getElementById('orderChart');
    new Chart(ctxOrder, {
        type: 'bar',
        data: {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
            datasets: [{
                label: 'Số đơn hàng',
                data: [5, 8, 4, 10, 6, 12],
                backgroundColor: '#2049ff'
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Biểu đồ sản phẩm yêu thích
    const ctxFavorite = document.getElementById('favoriteChart');
    new Chart(ctxFavorite, {
        type: 'pie',
        data: {
            labels: ['Áo Thun', 'Quần Jeans', 'Áo Khoác', 'Phụ kiện'],
            datasets: [{
                data: [10, 7, 5, 3],
                backgroundColor: ['#b6194a', '#2049ff', '#ff6b1a', '#ffcc00']
            }]
        }
    });
</script>

</body>
</html>
