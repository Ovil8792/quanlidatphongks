@extends('layout.main')
@section('main')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">
                        <i class="bi bi-credit-card me-2"></i>Thanh toán đặt phòng
                    </h3>
                </div>
                
                <div class="card-body p-4">
                    @if(isset($bill))
                        {{-- Thông tin đặt phòng --}}
                        <div class="mb-4">
                            <h5 class="text-primary fw-semibold mb-3">
                                <i class="bi bi-info-circle me-2"></i>Thông tin đặt phòng
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Mã đơn hàng:</strong> #{{ $bill->id }}</p>
                                    <p><strong>Phòng:</strong> {{ $bill->details->first()->room->name ?? 'N/A' }}</p>
                                    <p><strong>Khách sạn:</strong> NT House</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Nhận phòng:</strong> {{ \Carbon\Carbon::parse($bill->checkin)->format('d/m/Y H:i') }}</p>
                                    <p><strong>Trả phòng:</strong> {{ \Carbon\Carbon::parse($bill->checkout)->format('d/m/Y H:i') }}</p>
                                    <p><strong>Số đêm:</strong> {{ $nights }} đêm</p>
                                </div>
                            </div>
                        </div>

                        {{-- Tổng tiền --}}
                        <div class="alert alert-success text-center py-3 mb-4">
                            <h4 class="mb-2">Tổng tiền cần thanh toán:</h4>
                            <h2 class="text-success mb-0">{{ number_format($bill->total) }} VNĐ</h2>
                        </div>

                        {{-- Phương thức thanh toán --}}
                        <div class="mb-4">
                            <h5 class="text-primary fw-semibold mb-3">
                                <i class="bi bi-credit-card me-2"></i>Chọn phương thức thanh toán
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card border-2 h-100 payment-method-card" data-method="vnpay">
                                        <div class="card-body text-center p-3">
                                            <img src="{{ asset('img/vnpaylogo.png') }}" alt="VNPay" class="img-fluid mb-2" style="max-height: 40px;">
                                            <h6 class="mb-2">VNPay</h6>
                                            <small class="text-muted">Thanh toán trực tuyến an toàn</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card border-2 h-100 payment-method-card" data-method="momo">
                                        <div class="card-body text-center p-3">
                                            <i class="bi bi-phone text-danger" style="font-size: 2rem;"></i>
                                            <h6 class="mb-2">MOMO</h6>
                                            <small class="text-muted">Thanh toán qua ví điện tử</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nút thanh toán --}}
                        <div class="text-center">
                            <button id="vnpayBtn" class="btn btn-primary btn-lg px-5 py-3 me-3" style="display: none;">
                                <i class="bi bi-credit-card me-2"></i>Thanh toán VNPay
                            </button>
                            
                            <button id="momoBtn" class="btn btn-danger btn-lg px-5 py-3" style="display: none;">
                                <i class="bi bi-phone me-2"></i>Thanh toán MOMO
                            </button>
                            
                            <button id="selectMethodBtn" class="btn btn-primary btn-lg px-5 py-3">
                                <i class="bi bi-arrow-right me-2"></i>Tiếp tục thanh toán
                            </button>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                            <h4 class="mt-3">Không có thông tin đặt phòng</h4>
                            <p class="text-muted">Vui lòng quay lại trang đặt phòng để tiếp tục.</p>
                            <a href="{{ route('client.index') }}" class="btn btn-primary">
                                <i class="bi bi-house-door me-2"></i>Về trang chủ
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($bill))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentCards = document.querySelectorAll('.payment-method-card');
    const vnpayBtn = document.getElementById('vnpayBtn');
    const momoBtn = document.getElementById('momoBtn');
    const selectMethodBtn = document.getElementById('selectMethodBtn');
    let selectedMethod = '';

    // Xử lý chọn phương thức thanh toán
    paymentCards.forEach(card => {
        card.addEventListener('click', function() {
            // Bỏ chọn tất cả
            paymentCards.forEach(c => c.classList.remove('border-primary'));
            
            // Chọn phương thức hiện tại
            this.classList.add('border-primary');
            selectedMethod = this.dataset.method;
            
            // Hiển thị nút thanh toán tương ứng
            if (selectedMethod === 'vnpay') {
                vnpayBtn.style.display = 'inline-block';
                momoBtn.style.display = 'none';
                selectMethodBtn.style.display = 'none';
            } else if (selectedMethod === 'momo') {
                vnpayBtn.style.display = 'none';
                momoBtn.style.display = 'inline-block';
                selectMethodBtn.style.display = 'none';
            }
        });
    });

    // Xử lý thanh toán VNPay
    vnpayBtn.addEventListener('click', function() {
        window.location.href = "{{ route('payment.vnpay', ['bill_id' => $bill->id ?? 0]) }}";
    });

    // Xử lý thanh toán MOMO (placeholder)
    momoBtn.addEventListener('click', function() {
        alert('Tính năng thanh toán MOMO đang được phát triển!');
    });
});
</script>

<style>
.payment-method-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border-color: #e9ecef;
}

.payment-method-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.payment-method-card.border-primary {
    border-color: #0d6efd !important;
    background-color: #f8f9ff;
}
</style>
@endif

@endsection