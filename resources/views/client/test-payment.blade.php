@extends('layout.main')
@section('main')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-info text-white text-center py-3">
                    <h3 class="mb-0">
                        <i class="bi bi-credit-card me-2"></i>Test Thanh Toán VNPay
                    </h3>
                </div>
                
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="bi bi-info-circle me-2"></i>Hướng dẫn test thanh toán
                        </h5>
                        <p class="mb-2">Đây là môi trường test VNPay Sandbox. Bạn có thể sử dụng thông tin thẻ test sau:</p>
                        <ul class="mb-0">
                            <li><strong>Số thẻ:</strong> 9704198526191432198</li>
                            <li><strong>Tên chủ thẻ:</strong> NGUYEN VAN A</li>
                            <li><strong>Ngày hết hạn:</strong> 07/15</li>
                            <li><strong>Mã OTP:</strong> 123456</li>
                        </ul>
                    </div>

                    <!-- Thông tin đơn hàng test -->
                    <div class="mb-4">
                        <h5 class="text-primary fw-semibold mb-3">
                            <i class="bi bi-info-circle me-2"></i>Thông tin đơn hàng test
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Phòng:</strong> Phòng Deluxe 101</p>
                                <p><strong>Khách hàng:</strong> Nguyễn Văn Test</p>
                                <p><strong>Nhận phòng:</strong> {{ now()->addDays(1)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Trả phòng:</strong> {{ now()->addDays(3)->format('d/m/Y') }}</p>
                                <p><strong>Số đêm:</strong> 2 đêm</p>
                                <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold">1,000,000 VND</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Nút test thanh toán -->
                    <div class="text-center">
                        <button id="testPaymentBtn" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="bi bi-credit-card me-2"></i>Test Thanh Toán VNPay
                        </button>
                        
                        <div class="mt-3">
                            <a href="{{ route('client.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house-door me-2"></i>Về trang chủ
                            </a>
                        </div>
                    </div>

                    <!-- Hướng dẫn chi tiết -->
                    <div class="mt-5">
                        <h6 class="text-primary fw-bold mb-3">Các bước test thanh toán:</h6>
                        <ol>
                            <li>Nhấn nút "Test Thanh Toán VNPay" ở trên</li>
                            <li>Bạn sẽ được chuyển đến trang VNPay Sandbox</li>
                            <li>Chọn "Thanh toán qua thẻ nội địa" hoặc "Thanh toán qua QR Code"</li>
                            <li>Nhập thông tin thẻ test ở trên</li>
                            <li>Nhập mã OTP: 123456</li>
                            <li>Sau khi thanh toán thành công, bạn sẽ được chuyển về trang chủ</li>
                        </ol>
                    </div>

                    @if (app()->environment('local'))
                    <div class="alert alert-secondary mt-3">
                        <strong>Debug (chỉ hiển thị ở local):</strong>
                        <div>
                            @php
                                $relativeReturn = route('payment.vnpay.return', [], false);
                                $origin = request()->getSchemeAndHttpHost();
                                $baseUrl = request()->getBaseUrl();
                                $fullReturn = rtrim($origin . $baseUrl, '/') . $relativeReturn;
                            @endphp
                            VNPay Return URL hiện tại: <code>{{ $fullReturn }}</code>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const testPaymentBtn = document.getElementById('testPaymentBtn');
    
    testPaymentBtn.addEventListener('click', function() {
        // Tạo form để gửi request test
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("test.create-payment") }}';
        
        // CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    });
});
</script>

@endsection
