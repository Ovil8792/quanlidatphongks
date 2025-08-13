@extends("layout.main")

@section("main")
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-success text-white text-center py-4">
                    <div class="mb-3">
                        <i class="fa fa-check-circle fa-4x"></i>
                    </div>
                    <h3 class="mb-0">🎉 Đặt phòng thành công!</h3>
                </div>
                
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h4 class="text-success mb-3">Cảm ơn bạn đã đặt phòng tại NT House</h4>
                        <p class="text-muted">Thông tin đặt phòng của bạn đã được ghi nhận. Vui lòng hoàn tất thanh toán để xác nhận đặt phòng.</p>
                    </div>

                    <!-- Thông tin đặt phòng -->
                    <div class="booking-details bg-light rounded-3 p-4 mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fa fa-info-circle me-2"></i>Chi tiết đặt phòng
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <strong><i class="fa fa-hotel me-2"></i>Phòng:</strong>
                                    <span class="ms-2">{{ $room_name }}</span>
                                </div>
                                <div class="detail-item mb-3">
                                    <strong><i class="fa fa-user me-2"></i>Khách hàng:</strong>
                                    <span class="ms-2">{{ $guest_name }}</span>
                                </div>
                                <div class="detail-item mb-3">
                                    <strong><i class="fa fa-calendar-check me-2"></i>Ngày nhận:</strong>
                                    <span class="ms-2">{{ \Carbon\Carbon::parse($checkin)->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <strong><i class="fa fa-calendar-times me-2"></i>Ngày trả:</strong>
                                    <span class="ms-2">{{ \Carbon\Carbon::parse($checkout)->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="detail-item mb-3">
                                    <strong><i class="fa fa-moon me-2"></i>Số đêm:</strong>
                                    <span class="ms-2">{{ $nights }} đêm</span>
                                </div>
                                <div class="detail-item mb-3">
                                    <strong><i class="fa fa-money-bill me-2"></i>Tổng tiền:</strong>
                                    <span class="ms-2 text-danger fw-bold">{{ number_format($total, 0, ',', '.') }} VND</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin thanh toán -->
                    <div class="payment-info bg-primary bg-opacity-10 rounded-3 p-4 mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fa fa-credit-card me-2"></i>Thông tin thanh toán
                        </h5>
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-2"><strong>Mã đặt phòng:</strong> <span class="text-primary fw-bold">#{{ $bill_id }}</span></p>
                                <p class="mb-0 text-muted">Vui lòng hoàn tất thanh toán trong vòng 24 giờ để xác nhận đặt phòng</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="price-display">
                                    <small class="text-muted">Tổng cộng</small>
                                    <div class="h3 text-success mb-0">{{ number_format($total, 0, ',', '.') }} VND</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Các bước tiếp theo -->
                    <div class="next-steps mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="fa fa-list-ol me-2"></i>Các bước tiếp theo
                        </h5>
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <div class="step-item">
                                    <div class="step-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                        <i class="fa fa-check fa-lg"></i>
                                    </div>
                                    <h6>1. Đặt phòng</h6>
                                    <small class="text-muted">Hoàn tất ✓</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="step-item">
                                    <div class="step-icon bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                        <i class="fa fa-credit-card fa-lg"></i>
                                    </div>
                                    <h6>2. Thanh toán</h6>
                                    <small class="text-muted">Đang chờ</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="step-item">
                                    <div class="step-icon bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                        <i class="fa fa-home fa-lg"></i>
                                    </div>
                                    <h6>3. Nhận phòng</h6>
                                    <small class="text-muted">Chưa đến</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nút hành động -->
                    <div class="action-buttons text-center">
                        <a href="{{ route('dathang.process-payment') }}" class="btn btn-primary btn-lg px-5 py-3 me-3">
                            <i class="fa fa-credit-card me-2"></i>Thanh toán ngay
                        </a>
                        <a href="{{ route('client.index') }}" class="btn btn-outline-secondary btn-lg px-4 py-3">
                            <i class="fa fa-home me-2"></i>Về trang chủ
                        </a>
                    </div>

                    <!-- Lưu ý -->
                    <div class="alert alert-info mt-4 mb-0">
                        <h6 class="alert-heading">
                            <i class="fa fa-info-circle me-2"></i>Lưu ý quan trọng
                        </h6>
                        <ul class="mb-0">
                            <li>Vui lòng giữ mã đặt phòng <strong>#{{ $bill_id }}</strong> để tra cứu</li>
                            <li>Thanh toán sẽ được xử lý qua VNPay - cổng thanh toán an toàn</li>
                            <li>Bạn sẽ nhận được email xác nhận sau khi thanh toán thành công</li>
                            <li>Liên hệ hotline <strong>1900-xxxx</strong> nếu cần hỗ trợ</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.booking-details {
    border-left: 4px solid #28a745;
}

.payment-info {
    border-left: 4px solid #007bff;
}

.detail-item {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.detail-item:last-child {
    border-bottom: none;
}

.step-icon {
    transition: all 0.3s ease;
}

.step-item:hover .step-icon {
    transform: scale(1.1);
}

.price-display {
    background: rgba(255, 255, 255, 0.8);
    padding: 15px;
    border-radius: 10px;
}

.action-buttons .btn {
    transition: all 0.3s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    .card-body {
        padding: 2rem !important;
    }
    
    .action-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .action-buttons .btn:last-child {
        margin-bottom: 0;
    }
}
</style>

@endsection
