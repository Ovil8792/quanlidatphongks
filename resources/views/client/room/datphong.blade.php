@extends("layout.main")

@section("main")

<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Form bên trái -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>Đặt phòng - {{ $room->name }}
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('dathang.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <input type="hidden" name="price_per_night" id="price_per_night" value="{{ $room->base_price }}">
                        <input type="hidden" name="total" id="total">
                        
                        <!-- Thông tin khách hàng -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary fw-semibold mb-3">
                                    <i class="bi bi-person me-2"></i>Thông tin khách hàng
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required placeholder="Nhập họ và tên">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone') }}" required pattern="0[0-9]{9,10}" placeholder="0xxxxxxxxx">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" required placeholder="example@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin đặt phòng -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary fw-semibold mb-3">
                                    <i class="bi bi-calendar-range me-2"></i>Thông tin đặt phòng
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Ngày giờ nhận phòng <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="checkin" id="checkin" 
                                           class="form-control @error('checkin') is-invalid @enderror" 
                                           value="{{ old('checkin') }}" required 
                                           min="{{ date('Y-m-d\TH:i') }}">
                                    <div class="form-text">Chọn thời gian nhận phòng</div>
                                    @error('checkin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Ngày giờ trả phòng <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="checkout" id="checkout" 
                                           class="form-control @error('checkout') is-invalid @enderror" 
                                           value="{{ old('checkout') }}" required>
                                    <div class="form-text">Chọn thời gian trả phòng</div>
                                    @error('checkout')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin giá -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-semibold mb-3">
                                            <i class="bi bi-calculator me-2"></i>Chi tiết giá
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-2"><strong>Giá/đêm:</strong> <span class="text-danger">{{ number_format($room->base_price) }}đ</span></p>
                                                <p class="mb-2"><strong>Số đêm:</strong> <span id="nights_display">0</span> đêm</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-2"><strong>Tổng tiền:</strong></p>
                                                <h4 id="total_price_display" class="text-success fw-bold mb-0">0 đ</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thông tin bổ sung -->
                        <div id="booking_info"></div>

                        <!-- Nút submit -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3" id="submitBtn">
                                <i class="bi bi-check-circle me-2"></i>XÁC NHẬN ĐẶT PHÒNG
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Thông tin phòng & khách sạn -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top: 20px;">
                @if($room->pimage)
                <img src="{{ asset('storage/upload/' . $room->pimage) }}" alt="Ảnh phòng" 
                     class="card-img-top img-fluid" style="object-fit: cover; height: 200px;">
                @endif

                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold mb-3">
                        <i class="bi bi-building me-2"></i>NT House
                    </h5>
                    <p class="card-text text-muted mb-3">
                        <i class="bi bi-geo-alt-fill me-2"></i>123 Đường ABC, Quận 1, TP.HCM
                    </p>

                    <div class="mb-3">
                        <h6 class="fw-semibold text-primary mb-3">
                            <i class="bi bi-door-open me-2"></i>{{ $room->name }}
                        </h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-currency-dollar me-2"></i>Giá/đêm:</span>
                                <strong class="text-danger">{{ number_format($room->base_price) }}đ</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-arrows-angle-expand me-2"></i>Diện tích:</span>
                                <strong>{{ $room->room_area ?? '20' }} m²</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-people me-2"></i>Tối đa:</span>
                                <strong>{{ $room->max_guests ?? 2 }} người</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-bed me-2"></i>Số giường:</span>
                                <strong>{{ $room->bed_count ?? 1 }} giường</strong>
                            </li>
                        </ul>
                    </div>

                    @if($room->amenities)
                    <div class="mb-3">
                        <h6 class="fw-semibold text-primary mb-2">
                            <i class="bi bi-star me-2"></i>Tiện nghi
                        </h6>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach(explode(', ', $room->amenities) as $amenity)
                                <span class="badge bg-light text-dark border">{{ trim($amenity) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="alert alert-success mt-3 mb-2 py-2" role="alert">
                        <strong>🎉 Ưu đãi đặc biệt!</strong><br>
                        Giá tốt nhất cho phòng chất lượng cao
                    </div>

                    <div class="alert alert-info mt-2 py-2" role="alert">
                        <strong>💡 Lưu ý:</strong><br>
                        • Không hoàn tiền<br>
                        • Hủy phòng trước 24h
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
        const nightsDisplay = document.getElementById('nights_display');
        const submitBtn = document.getElementById('submitBtn');

        if (!isNaN(checkin) && !isNaN(checkout) && checkout > checkin) {
            const timeDiff = checkout - checkin;
            const nights = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
            const total = price * nights;

            totalEl.value = total;
            displayEl.innerText = total.toLocaleString('vi-VN') + ' đ';
            nightsDisplay.innerText = nights;
            
            // Hiển thị thông tin chi tiết
            const infoDiv = document.getElementById('booking_info');
            if (infoDiv) {
                infoDiv.innerHTML = `
                    <div class="alert alert-info border-0 rounded-3">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>📅 Thông tin đặt phòng:</strong><br>
                                • Số đêm: <strong>${nights} đêm</strong><br>
                                • Giá/đêm: <strong>${price.toLocaleString('vi-VN')}đ</strong>
                            </div>
                            <div class="col-md-6">
                                <strong>💰 Tổng tiền:</strong><br>
                                <span class="h5 text-success fw-bold">${total.toLocaleString('vi-VN')}đ</span>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Enable submit button
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-primary');
        } else {
            totalEl.value = '';
            displayEl.innerText = '0 đ';
            nightsDisplay.innerText = '0';
            
            const infoDiv = document.getElementById('booking_info');
            if (infoDiv) {
                infoDiv.innerHTML = '';
            }
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-secondary');
        }
    }

    // Validation cho checkout
    document.getElementById('checkout').addEventListener('change', function() {
        const checkin = new Date(document.getElementById('checkin').value);
        const checkout = new Date(this.value);
        
        if (checkout <= checkin) {
            alert('Thời gian trả phòng phải sau thời gian nhận phòng!');
            this.value = '';
            calculateTotal();
        }
    });

    // Validation cho checkin
    document.getElementById('checkin').addEventListener('change', function() {
        const checkin = new Date(this.value);
        const now = new Date();
        
        if (checkin < now) {
            alert('Thời gian nhận phòng không thể trong quá khứ!');
            this.value = '';
            calculateTotal();
        }
    });

    // Event listeners
    document.getElementById('checkin').addEventListener('change', calculateTotal);
    document.getElementById('checkout').addEventListener('change', calculateTotal);
    
    // Khởi tạo
    document.addEventListener('DOMContentLoaded', function() {
        // Set min cho checkout
        document.getElementById('checkin').addEventListener('change', function() {
            const checkin = this.value;
            if (checkin) {
                document.getElementById('checkout').min = checkin;
            }
        });
        
        // Disable submit button initially
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').classList.remove('btn-primary');
        document.getElementById('submitBtn').classList.add('btn-secondary');
        
        // Form validation
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const total = document.getElementById('total').value;
            
            if (!checkin || !checkout || !total) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin và chọn thời gian hợp lệ!');
                return false;
            }
        });
    });
</script>

<style>
.sticky-top {
    position: sticky;
    top: 20px;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .sticky-top {
        position: static;
        margin-top: 20px;
    }
}
</style>

@endsection