@extends("layout.main")

@section("main")
<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-light">
        <h3 class="mb-4 text-success fw-bold text-center">
            <i class="bi bi-check-circle-fill me-2"></i> Xác nhận đặt phòng
        </h3>

        {{-- Thông tin khách hàng --}}
        <div class="mb-4">
            <h5 class="text-primary fw-semibold">👤 Thông tin khách hàng:</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Họ tên: <strong>{{ $booking['name'] }}</strong></li>
                <li class="list-group-item">SĐT: <strong>{{ $booking['phone'] }}</strong></li>
                <li class="list-group-item">Email: <strong>{{ $booking['email'] }}</strong></li>
            </ul>
        </div>

        {{-- Thông tin đặt phòng --}}
        <div class="mb-4">
            <h5 class="text-primary fw-semibold">🛏️ Thông tin đặt phòng:</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Khách sạn: <strong>{{ $room->hotel->name ?? 'Không xác định' }}</strong></li>
                <li class="list-group-item">Phòng: <strong>{{ $room->name }}</strong></li>
                <li class="list-group-item">Nhận phòng: {{ \Carbon\Carbon::parse($booking['checkin'])->format('d/m/Y H:i') }}</li>
                <li class="list-group-item">Trả phòng: {{ \Carbon\Carbon::parse($booking['checkout'])->format('d/m/Y H:i') }}</li>
                <li class="list-group-item text-danger fw-bold">Tổng tiền: {{ number_format($booking['total']) }} đ</li>
            </ul>
        </div>

        {{-- Gợi ý --}}
        <div class="alert alert-info text-center rounded-3">
            Cảm ơn bạn đã đặt phòng tại <strong>{{ $room->hotel->name ?? 'NT House' }}</strong>!
            <br>Chúng tôi sẽ liên hệ bạn để xác nhận chi tiết.
        </div>

        {{-- Tiền đặt cọc --}}
        <div class="mb-4">
            <h5 class="text-success">
                💰 Tiền đặt cọc (50%):
                <strong>{{ number_format($booking['total'] / 2) }} đ</strong>
            </h5>
            <p class="text-muted">Vui lòng chuẩn bị trước số tiền này để hoàn tất khi nhận phòng.</p>
        </div>

        {{-- Hành động --}}
        <div class="d-flex justify-content-center gap-3 mt-3">
            <a href="{{ route('client.index') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-house-door"></i> Về trang chủ
            </a>
            <a href="{{ route("client.payment") }}" class="btn btn-success px-4">
                <i class="bi bi-credit-card"></i> Thanh toán đặt cọc
            </a>
        </div>
    </div>
</div>
@endsection