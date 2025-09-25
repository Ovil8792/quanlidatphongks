@extends('layout.main')
@section('main')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <!-- Header hóa đơn -->
                <div class="card-header bg-success text-white text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="mb-0">🎉 Thanh toán thành công!</h3>
                    <p class="mb-0 mt-2">Hóa đơn đặt phòng NT House</p>
                </div>
                
                <div class="card-body p-5">
                    <!-- Thông báo thành công -->
                    <div class="alert alert-success text-center mb-4">
                        <h5 class="mb-2">
                            <i class="bi bi-check-circle me-2"></i>
                            Cảm ơn bạn đã thanh toán thành công!
                        </h5>
                        <p class="mb-0">Đặt phòng của bạn đã được xác nhận. Vui lòng lưu lại hóa đơn này để tra cứu.</p>
                    </div>

                    <!-- Thông tin hóa đơn -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="invoice-info">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-file-text me-2"></i>Thông tin hóa đơn
                                </h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-semibold">Mã hóa đơn:</td>
                                        <td class="text-primary fw-bold">#{{ $bill->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Ngày tạo:</td>
                                        <td>{{ $bill->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Ngày thanh toán:</td>
                                        <td>{{ $bill->payment_date ? \Carbon\Carbon::parse($bill->payment_date)->format('d/m/Y H:i') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Trạng thái:</td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Đã thanh toán
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="customer-info">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-person me-2"></i>Thông tin khách hàng
                                </h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-semibold">Họ tên:</td>
                                        <td>{{ $bill->guest_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Email:</td>
                                        <td>{{ $bill->guest_email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Điện thoại:</td>
                                        <td>{{ $bill->guest_phone }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Chi tiết đặt phòng -->
                    <div class="booking-details mb-4">
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="bi bi-calendar-check me-2"></i>Chi tiết đặt phòng
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Phòng</th>
                                        <th>Ngày nhận</th>
                                        <th>Ngày trả</th>
                                        <th>Số đêm</th>
                                        <th>Giá/đêm</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $invoiceSubtotal = 0; @endphp
                                    @foreach($bill->details as $detail)
                                    <tr>
                                        <td>
                                            <strong>{{ $detail->room->name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $detail->room->category->name ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($bill->checkin)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($bill->checkout)->format('d/m/Y') }}</td>
                                        @php
                                            $cin = \Carbon\Carbon::parse($bill->checkin)->startOfDay();
                                            $cout = \Carbon\Carbon::parse($bill->checkout)->startOfDay();
                                            $nights = max(1, (int) $cin->diffInDays($cout, true));
                                            $rate = (int) ($detail->room_rate ?? 0);
                                            $lineTotal = $rate * $nights;
                                        @endphp
                                        @php $invoiceSubtotal += $lineTotal; @endphp
                                        <td class="text-center">{{ $nights }} đêm</td>
                                        <td class="text-end">{{ number_format($rate, 0, ',', '.') }} VND</td>
                                        <td class="text-end fw-bold">{{ number_format($lineTotal, 0, ',', '.') }} VND</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tổng tiền -->
                    <div class="total-section bg-light rounded-3 p-4 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-0">
                                    <i class="bi bi-calculator me-2"></i>Tổng cộng
                                </h5>
                                <small class="text-muted">Đã bao gồm tất cả các phí</small>
                            </div>
                            <div class="col-md-4 text-end">
                                <h3 class="text-success mb-0 fw-bold">
                                    {{ number_format($invoiceSubtotal ?? 0, 0, ',', '.') }} VND
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin check-in -->
                    <div class="checkin-info alert alert-info mb-4">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle me-2"></i>Thông tin quan trọng
                        </h6>
                        <ul class="mb-0">
                            <li><strong>Thời gian nhận phòng:</strong> {{ \Carbon\Carbon::parse($bill->checkin)->format('d/m/Y') }} từ 14:00</li>
                            <li><strong>Thời gian trả phòng:</strong> {{ \Carbon\Carbon::parse($bill->checkout)->format('d/m/Y') }} trước 12:00</li>
                            <li><strong>Địa chỉ:</strong> NT House - 123 Đường ABC, Quận XYZ, TP.HCM</li>
                            <li><strong>Hotline:</strong> 1900-xxxx (24/7)</li>
                            <li>Vui lòng mang theo CMND/CCCD và hóa đơn này khi nhận phòng</li>
                        </ul>
                    </div>

                    <!-- Nút hành động -->
                    <div class="action-buttons text-center">
                        <button onclick="window.print()" class="btn btn-primary btn-lg px-5 py-3 me-3">
                            <i class="bi bi-printer me-2"></i>In hóa đơn
                        </button>
                        <a href="{{ route('client.index') }}" class="btn btn-outline-secondary btn-lg px-4 py-3">
                            <i class="bi bi-house-door me-2"></i>Về trang chủ
                        </a>
                    </div>

                    <!-- Footer hóa đơn -->
                    <div class="invoice-footer text-center mt-5 pt-4 border-top">
                        <p class="text-muted mb-1">
                            <strong>NT House</strong> - Hệ thống quản lý khách sạn
                        </p>
                        <p class="text-muted small mb-0">
                            Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, .footer {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .container {
        max-width: 100% !important;
        padding: 0 !important;
    }
}

.invoice-info table td,
.customer-info table td {
    padding: 0.5rem 0;
    border: none;
}

.booking-details table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.total-section {
    border-left: 4px solid #28a745;
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
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>

@endsection
