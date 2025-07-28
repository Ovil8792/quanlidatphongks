@extends("layout.main")

@section("main")
{{-- <a class="btn btn-primary" href="{{ route("testr") }}">test</a> --}}
<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Form bên trái -->
        <div class="col-md-7">
            <div class="bg-white p-4 rounded shadow-sm">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form action="{{ route('dathang.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    <input type="hidden" name="price_per_night" id="price_per_night" value="{{ $room->base_price }}">
                    <input type="hidden" name="total" id="total">
                    <div class="mb-3">
                        <label class="form-label">Họ và tên *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="tel" name="phone" class="form-control" required pattern="0[0-9]{9,10}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ngày nhận phòng *</label>
                        <input type="date" name="checkin" id="checkin" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ngày trả phòng *</label>
                        <input type="date" name="checkout" id="checkout" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tổng tiền:</label>
                        <div id="total_price_display" class="fw-bold text-primary">0 đ</div>
                    </div>

                    <button class="btn btn-primary w-100">ĐẶT NGAY!</button>
                </form>

            </div>
        </div>

        <!-- Thông tin phòng & khách sạn -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                @if($room->pimage)
                <img src="{{ asset('storage/upload/' . $room->pimage) }}" alt="Ảnh phòng" class="card-img-top img-fluid" style="object-fit: cover; height: 250px;">
                @endif

                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold">{{ $room->hotel->name ?? 'Tên khách sạn' }}</h5>
                    <p class="card-text text-muted mb-3">
                        <i class="bi bi-geo-alt-fill"></i> {{ $room->hotel->address ?? 'Địa chỉ không xác định' }}
                    </p>

                    <div class="mb-3">
                        <h6 class="fw-semibold">Phòng: {{ $room->name }}</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                Giá: <strong class="text-danger">{{ number_format($room->base_price) }}đ / đêm</strong>
                            </li>
                            <li class="list-group-item">Diện tích: {{ $room->size ?? '20 m²' }}</li>
                            <li class="list-group-item">Tối đa: {{ $room->max_guests ?? 2 }} người lớn</li>
                            <li class="list-group-item">Giường: {{ $room->bed_type ?? '1 giường đôi lớn' }}</li>
                            <li class="list-group-item">Tiện nghi: {{ $room->amenities ?? 'WiFi miễn phí, Bãi đỗ xe, Máy lạnh' }}</li>
                        </ul>
                        <p class="text-muted small mt-2 mb-0">* Không hoàn tiền</p>
                    </div>

                    <div class="alert alert-success mt-3 mb-2 py-2" role="alert">
                        <strong>🎉 Xin chúc mừng!</strong> Bạn đã tìm được phòng với giá rẻ nhất ở <strong>{{ $room->hotel->name ?? 'khách sạn' }}</strong>.
                    </div>

                    <div class="alert alert-info mt-2 py-2" role="alert">
                        <strong>Lựa chọn khách sạn tốt nhất:</strong><br>
                        {{ $room->hotel->description ?? 'Khách sạn tiện nghi, sạch sẽ và phục vụ tận tình.' }}
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<!-- Script tính tổng tiền -->
<script>
    function calculateTotal() {
        const price = parseFloat(document.getElementById('price_per_night').value);
        const checkin = new Date(document.getElementById('checkin').value);
        const checkout = new Date(document.getElementById('checkout').value);
        const totalEl = document.getElementById('total');
        const displayEl = document.getElementById('total_price_display');

        if (!isNaN(checkin) && !isNaN(checkout) && checkout > checkin) {
            const timeDiff = checkout - checkin;
            const nights = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
            const total = price * nights;

            totalEl.value = total;
            displayEl.innerText = total.toLocaleString('vi-VN') + ' đ';
        } else {
            totalEl.value = '';
            displayEl.innerText = '0 đ';
        }
    }

    document.getElementById('checkin').addEventListener('change', calculateTotal);
    document.getElementById('checkout').addEventListener('change', calculateTotal);
</script>
@endsection