<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/banhaisan/employee/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Xử lý đơn hàng</li>
        </ol>
    </nav>

    <h2 class="mb-4 text-primary"><i class="fas fa-clipboard-list"></i> Danh Sách Đơn Hàng Cần Xử Lý</h2>

    <div class="btn-group mb-3" role="group">
        <button type="button" class="btn btn-outline-primary active">Tất cả</button>
        <button type="button" class="btn btn-outline-warning">Chờ duyệt</button>
        <button type="button" class="btn btn-outline-info">Đang giao</button>
        <button type="button" class="btn btn-outline-success">Hoàn thành</button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Mã Đơn</th>
                        <th scope="col">Khách hàng</th>
                        <th scope="col">Ngày đặt</th>
                        <th scope="col">Tổng tiền</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>#ORD-9981</strong></td>
                        <td>
                            Nguyễn Thị Cẩm<br>
                            <small class="text-muted">0987.654.321</small>
                        </td>
                        <td>04/02/2026</td>
                        <td class="text-danger fw-bold">2,500,000đ</td>
                        <td><span class="badge bg-warning text-dark">Chờ duyệt</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-success" title="Duyệt đơn"><i class="fas fa-check"></i></button>
                            <button class="btn btn-sm btn-danger" title="Hủy đơn"><i class="fas fa-times"></i></button>
                            <button class="btn btn-sm btn-secondary" title="Xem chi tiết"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>#ORD-9982</strong></td>
                        <td>
                            Trần Văn Ba<br>
                            <small class="text-muted">0909.123.456</small>
                        </td>
                        <td>04/02/2026</td>
                        <td class="text-danger fw-bold">500,000đ</td>
                        <td><span class="badge bg-info">Đang giao</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-primary" title="Cập nhật vận chuyển"><i class="fas fa-shipping-fast"></i></button>
                            <button class="btn btn-sm btn-secondary" title="Xem chi tiết"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">Sau</a></li>
        </ul>
    </nav>
</div>

<?php include 'app/views/shares/footer.php'; ?>