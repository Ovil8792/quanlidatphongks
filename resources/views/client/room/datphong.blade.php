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
                    @if(session('form_errors'))
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach(session('form_errors') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(isset($bookingData) && !empty($bookingData))
                        <div class="alert mb-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dfa974; color: #495057;">
                            <i class="fa fa-lightbulb me-2" style="color: #dfa974;"></i>
                            <strong style="color: #dfa974;">Thông tin từ trang chủ:</strong> 
                            Dữ liệu đặt phòng của bạn đã được tự động điền. 
                            Vui lòng kiểm tra và bổ sung thông tin cá nhân!
                        </div>
                        
                        <!-- Debug: Hiển thị dữ liệu session -->
                        <div class="alert alert-warning mb-4">
                            <strong>Debug Session Data:</strong><br>
                            <pre>{{ print_r($bookingData, true) }}</pre>
                        </div>
                        

                    @else
                        <div class="alert alert-warning mb-4">
                            <strong>Không có dữ liệu session!</strong><br>
                            Vui lòng quay lại trang chủ để nhập thông tin đặt phòng.
                        </div>
                    @endif
                    
                    <form action="{{ route('dathang.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <input type="hidden" name="price_per_night" id="price_per_night" value="{{ $room->base_price }}">
                        <input type="hidden" name="total" id="total">
                        <input type="hidden" name="room_count" id="room_count" value="1">
                        
                        <!-- Thông tin đặt phòng từ session -->
                        @if(isset($bookingData) && !empty($bookingData))
                            <div class="row mb-4">
                                <div class="col-12">
                                                                    <h5 class="fw-semibold mb-3" style="color: #dfa974;">
                                    <i class="bi bi-calendar-event me-2" style="color: #dfa974;"></i>Thông tin đặt phòng từ trang chủ
                                </h5>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Số lượng khách</label>
                                        <input type="text" class="form-control" value="@if(isset($bookingData['guest'])) @if($bookingData['guest'] == '2l1n') 2 lớn + 1 nhỏ @elseif($bookingData['guest'] == '4l3n') 4 lớn + 3 nhỏ @elseif($bookingData['guest'] == '6l4n') 6 lớn + 4 nhỏ @elseif($bookingData['guest'] == 'custom') {{ ($bookingData['custom_guest'] ?? 'Tùy chỉnh') . ' người' }} @else {{ $bookingData['guest'] }} @endif @else N/A @endif" readonly>
                                        @if(isset($bookingData['guest']))
                                            <input type="hidden" name="guest" value="{{ $bookingData['guest'] }}">
                                        @endif
                                        @if(isset($bookingData['custom_guest']) && ($bookingData['guest'] ?? '') === 'custom')
                                            <input type="hidden" name="custom_guest" value="{{ $bookingData['custom_guest'] }}">
                                        @endif
                                    </div>
                                </div>
                               
                                @if(isset($bookingData['custom_guest']) && $bookingData['guest'] == 'custom')
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Số khách tùy chỉnh</label>
                                            <input type="text" class="form-control" value="{{ $bookingData['custom_guest'] }} người" readonly>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        @if(!isset($bookingData) || empty($bookingData) || empty($bookingData['guest']))
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="fw-semibold mb-3" style="color: #dfa974;">
                                        <i class="bi bi-people me-2" style="color: #dfa974;"></i>Chọn số lượng khách
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Số lượng khách</label>
                                        <select class="form-select" name="guest" id="guest_select">
                                            <option value="">Chọn</option>
                                            <option value="2l1n">2 lớn + 1 nhỏ</option>
                                            <option value="4l3n">4 lớn + 3 nhỏ</option>
                                            <option value="6l4n">6 lớn + 4 nhỏ</option>
                                            <option value="custom">Tùy chỉnh</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" id="custom_guest_wrapper" style="display:none;">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Khách (tổng số người)</label>
                                        <input type="number" min="1" class="form-control" name="custom_guest" id="custom_guest_input" placeholder="Nhập số người">
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Thông tin khách hàng -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fw-semibold mb-3" style="color: #dfa974;">
                                    <i class="bi bi-person me-2" style="color: #dfa974;"></i>Thông tin khách hàng
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $bookingData['guest_name'] ?? '') }}" required placeholder="Nhập họ và tên">
                                    <div class="form-text text-danger d-none" data-error-for="name"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $bookingData['guest_phone'] ?? '') }}" required pattern="0[0-9]{9,10}" placeholder="0xxxxxxxxx">
                                    <div class="form-text text-danger d-none" data-error-for="phone"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $bookingData['guest_email'] ?? '') }}" required placeholder="example@email.com">
                                    <div class="form-text text-danger d-none" data-error-for="email"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin đặt phòng -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fw-semibold mb-3" style="color: #dfa974;">
                                    <i class="bi bi-calendar-range me-2" style="color: #dfa974;"></i>Thông tin đặt phòng
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Ngày nhận phòng <span class="text-danger">*</span></label>
                                    <input type="date" name="date_in" id="checkin" 
                                           class="form-control @error('date_in') is-invalid @enderror" 
                                           value="{{ old('date_in', isset($bookingData['date_in']) ? (preg_match('/^\d{4}-\d{2}-\d{2}$/', $bookingData['date_in']) ? $bookingData['date_in'] : date('Y-m-d', strtotime($bookingData['date_in']))) : '') }}" required 
                                           min="{{ date('Y-m-d') }}">
                                    <div class="form-text">Chọn ngày nhận phòng</div>
                                    <div class="form-text text-danger d-none" data-error-for="date_in"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Ngày trả phòng <span class="text-danger">*</span></label>
                                    <input type="date" name="date_out" id="checkout" 
                                           class="form-control @error('date_out') is-invalid @enderror" 
                                           value="{{ old('date_out', isset($bookingData['date_out']) ? (preg_match('/^\d{4}-\d{2}-\d{2}$/', $bookingData['date_out']) ? $bookingData['date_out'] : date('Y-m-d', strtotime($bookingData['date_out']))) : '') }}" required>
                                    <div class="form-text">Chọn ngày trả phòng</div>
                                    <div class="form-text text-danger d-none" data-error-for="date_out"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="button" id="checkAvailabilityBtn" class="btn btn-lg" style="background-color: #dfa974; border-color: #dfa974; color: white;">
                                            <i class="bi bi-search me-2"></i>Kiểm tra khả năng đặt phòng
                                        </button>
                                    </div>
                                    <div class="form-text">Kiểm tra trước khi đặt phòng</div>
                                </div>
                            </div>
                        </div>

                        <!-- Kết quả kiểm tra khả năng đặt phòng -->
                        <div id="availabilityResult" class="mb-4" style="display: none;">
                            <div class="alert" id="availabilityAlert" role="alert">
                                <div id="availabilityMessage"></div>
                            </div>
                        </div>

                        <!-- Thông tin giá -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="fw-semibold mb-3" style="color: #dfa974;">
                                            <i class="bi bi-calculator me-2" style="color: #dfa974;"></i>Chi tiết giá
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
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3" id="submitBtn" disabled>
                                <i class="bi bi-check-circle me-2"></i>XÁC NHẬN ĐẶT PHÒNG
                            </button>
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Vui lòng kiểm tra khả năng đặt phòng trước khi xác nhận
                            </div>
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
                    <h5 class="card-title fw-bold mb-3" style="color: #dfa974;">
                        <i class="bi bi-building me-2" style="color: #dfa974;"></i>NT House
                    </h5>
                    <p class="card-text text-muted mb-3">
                        <i class="bi bi-geo-alt-fill me-2"></i>123 Đường ABC, Quận 1, TP.HCM
                    </p>

                    <div class="mb-3">
                        <h6 class="fw-semibold mb-3" style="color: #dfa974;">
                            <i class="bi bi-door-open me-2" style="color: #dfa974;"></i>{{ $room->name }}
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
                        <h6 class="fw-semibold mb-2" style="color: #dfa974;">
                            <i class="bi bi-star me-2" style="color: #dfa974;"></i>Tiện nghi
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
            const dateIn = new Date(document.getElementById('checkin').value);
            const dateOut = new Date(document.getElementById('checkout').value);
            const totalEl = document.getElementById('total');
            const displayEl = document.getElementById('total_price_display');
            const nightsDisplay = document.getElementById('nights_display');
            const submitBtn = document.getElementById('submitBtn');

            // Same-day booking handling
            if (!isNaN(dateIn) && !isNaN(dateOut) && dateOut.getTime() === dateIn.getTime()) {
                alert('chỉ đặt cùng ngày khi đang ở khách sạn');
                totalEl.value = '';
                displayEl.innerText = '0 đ';
                nightsDisplay.innerText = '0';
                submitBtn.disabled = true;
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-secondary');
                submitBtn.style.backgroundColor = '#6c757d';
                submitBtn.style.borderColor = '#6c757d';
                const infoDiv = document.getElementById('booking_info');
                if (infoDiv) infoDiv.innerHTML = '';
                return;
            }

            if (!isNaN(dateIn) && !isNaN(dateOut) && dateOut > dateIn) {
                const timeDiff = dateOut - dateIn;
                const nights = Math.max(1, Math.round(timeDiff / (1000 * 60 * 60 * 24)));
                const total = price * nights;

            totalEl.value = total;
            displayEl.innerText = total.toLocaleString('vi-VN') + ' đ';
            nightsDisplay.innerText = nights;
            
            // Hiển thị thông tin chi tiết
            const infoDiv = document.getElementById('booking_info');
            if (infoDiv) {
                infoDiv.innerHTML = `
                    <div class="alert border-0 rounded-3" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dfa974 !important;">
                        <div class="row">
                            <div class="col-md-6">
                                <strong style="color: #dfa974;">📅 Thông tin đặt phòng:</strong><br>
                                • Số đêm: <strong style="color: #dfa974;">${nights} đêm</strong><br>
                                • Giá/đêm: <strong style="color: #dfa974;">${price.toLocaleString('vi-VN')}đ</strong>
                            </div>
                            <div class="col-md-6">
                                <strong style="color: #dfa974;">💰 Tổng tiền:</strong><br>
                                <span class="h5 fw-bold" style="color: #28a745;">${total.toLocaleString('vi-VN')}đ</span>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Enable submit button
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-primary');
            submitBtn.style.backgroundColor = '#dfa974';
            submitBtn.style.borderColor = '#dfa974';
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
            submitBtn.style.backgroundColor = '#6c757d';
            submitBtn.style.borderColor = '#6c757d';
        }
    }

    // Validation đã được xử lý bởi BookingCalendarManager
    // Không cần code validation cũ nữa

            // Biến để lưu dữ liệu gốc từ session
        let originalSessionData = {
            dateIn: null,
            dateOut: null
        };
    
    // Khởi tạo
    document.addEventListener('DOMContentLoaded', function() {
        // Hiển thị input tùy chỉnh số khách nếu chọn "custom"
        const guestSelect = document.getElementById('guest_select');
        const customGuestWrapper = document.getElementById('custom_guest_wrapper');
        if (guestSelect && customGuestWrapper) {
            guestSelect.addEventListener('change', function(){
                if (this.value === 'custom') {
                    customGuestWrapper.style.display = 'block';
                } else {
                    customGuestWrapper.style.display = 'none';
                }
            });
        }
        // Event listeners cho date inputs - chỉ tính toán, không thay đổi format
        document.getElementById('checkin').addEventListener('change', function() {
            const checkin = this.value;
            if (checkin) {
                // Cập nhật min cho checkout
                const checkoutField = document.getElementById('checkout');
                checkoutField.min = checkin;
                
                // Tính toán tổng tiền
                calculateTotal();
            }
        });
        
        document.getElementById('checkout').addEventListener('change', function() {
            // Chỉ tính toán tổng tiền, không thay đổi format
            calculateTotal();
        });
        
        // Disable submit button initially
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').classList.remove('btn-primary');
        document.getElementById('submitBtn').classList.add('btn-secondary');
        
        // Tự động tính toán giá nếu có dữ liệu từ session
        @if(isset($bookingData) && !empty($bookingData))
            // Lưu dữ liệu gốc từ session
            originalSessionData.dateIn = document.getElementById('checkin').value;
            originalSessionData.dateOut = document.getElementById('checkout').value;
            
            setTimeout(function() {
                calculateTotal();
            }, 500);
        @endif
        
        // Form validation đã được xử lý bởi BookingCalendarManager
        // Chỉ cần kiểm tra thêm total
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const total = document.getElementById('total').value;
            const errs = [];
            const form = this;
            const getVal = (name) => (form.querySelector(`[name="${name}"]`)?.value || '').trim();
            const setErr = (name, msg) => { const el = document.querySelector(`[data-error-for="${name}"]`); if (el) { el.textContent = msg; el.classList.remove('d-none'); } };
            const clearErrs = () => document.querySelectorAll('[data-error-for]').forEach(el => { el.textContent = ''; el.classList.add('d-none'); });

            clearErrs();

            const name = getVal('name');
            if (!name) setErr('name', 'Họ tên là bắt buộc');
            else if (name.length > 255) setErr('name', 'Họ tên không được quá 255 ký tự');

            const phone = getVal('phone');
            if (!phone) setErr('phone', 'Số điện thoại là bắt buộc');
            else if (!/^0[0-9]{9,10}$/.test(phone)) setErr('phone', 'Số điện thoại không hợp lệ (định dạng: 0xxxxxxxxx)');

            const email = getVal('email');
            if (!email) setErr('email', 'Email là bắt buộc');
            else {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!re.test(email)) setErr('email', 'Email không hợp lệ');
            }

            const dateIn = getVal('date_in');
            const dateOut = getVal('date_out');
            if (!dateIn) setErr('date_in', 'Ngày nhận phòng là bắt buộc');
            if (!dateOut) setErr('date_out', 'Ngày trả phòng là bắt buộc');
            const dateInOk = /^\d{4}-\d{2}-\d{2}$/.test(dateIn);
            const dateOutOk = /^\d{4}-\d{2}-\d{2}$/.test(dateOut);
            if (dateIn && !dateInOk) setErr('date_in', 'Ngày nhận phòng không hợp lệ');
            if (dateOut && !dateOutOk) setErr('date_out', 'Ngày trả phòng không hợp lệ');
            if (dateInOk && dateOutOk) {
                const cin = new Date(dateIn);
                const cout = new Date(dateOut);
                if (!(cout > cin)) setErr('date_out', 'Ngày trả phòng phải sau ngày nhận phòng');
            }

            // room_count mặc định là 1 (ẩn), không cần người dùng chọn

            if (!total) {
                setErr('date_in', 'Vui lòng chọn thời gian hợp lệ để tính toán giá!');
            }

            // Nếu có bất kỳ lỗi nào hiển thị, chặn submit
            const hasErr = Array.from(document.querySelectorAll('[data-error-for]')).some(el => !el.classList.contains('d-none') && el.textContent);
            if (hasErr) {
                e.preventDefault();
                document.querySelector('.alert-danger')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                return false;
            }
        });
        
        // Flag để biết khi nào người dùng đang tương tác với input
        let userInteracting = false;
        
        // Bảo vệ dữ liệu từ session - chỉ khôi phục khi thực sự cần thiết
        function restoreSessionData() {
            // Không khôi phục nếu người dùng đang tương tác
            if (userInteracting) return;
            
            const checkinField = document.getElementById('checkin');
            const checkoutField = document.getElementById('checkout');
            
            // Chỉ khôi phục khi field hoàn toàn trống và có dữ liệu gốc
            if (originalSessionData.dateIn && !checkinField.value && checkinField.value !== '') {
                checkinField.value = originalSessionData.dateIn;
            }
            if (originalSessionData.dateOut && !checkoutField.value && checkoutField.value !== '') {
                checkoutField.value = originalSessionData.dateOut;
            }
        }
        
        // Kiểm tra và khôi phục dữ liệu mỗi 5 giây (giảm tần suất)
        setInterval(restoreSessionData, 5000);
        
        // Theo dõi khi người dùng tương tác với input
        document.getElementById('checkin').addEventListener('focus', function() {
            userInteracting = true;
        });
        
        document.getElementById('checkin').addEventListener('blur', function() {
            setTimeout(() => { userInteracting = false; }, 1000);
        });
        
        document.getElementById('checkout').addEventListener('focus', function() {
            userInteracting = true;
        });
        
        document.getElementById('checkout').addEventListener('blur', function() {
            setTimeout(() => { userInteracting = false; }, 1000);
        });
        
        // Xử lý nút "Kiểm tra khả năng đặt phòng"
        const checkAvailabilityBtn = document.getElementById('checkAvailabilityBtn');
        const availabilityResult = document.getElementById('availabilityResult');
        const availabilityAlert = document.getElementById('availabilityAlert');
        const availabilityMessage = document.getElementById('availabilityMessage');
        const submitBtn = document.getElementById('submitBtn');
        
        checkAvailabilityBtn.addEventListener('click', function() {
            // Lấy dữ liệu từ form
            const dateIn = document.getElementById('checkin').value;
            const dateOut = document.getElementById('checkout').value;
            const roomCount = 1; // mặc định 1 phòng
            
            // Debug: Log dữ liệu để kiểm tra
            console.log('Check Availability - Date In:', dateIn);
            console.log('Check Availability - Date Out:', dateOut);
            console.log('Check Availability - Room Count:', roomCount);
            
            // Kiểm tra dữ liệu đầu vào
            if (!dateIn || !dateOut) {
                showAvailabilityResult('Vui lòng điền đầy đủ thông tin: ngày nhận phòng và ngày trả phòng!', 'danger');
                return;
            }
            
            // Kiểm tra ngày hợp lệ
            const checkinDate = new Date(dateIn);
            const checkoutDate = new Date(dateOut);
            const now = new Date();
            
            if (checkinDate <= now) {
                showAvailabilityResult('Ngày nhận phòng phải sau thời gian hiện tại!', 'danger');
                return;
            }
            
            if (checkoutDate <= checkinDate) {
                showAvailabilityResult('Ngày trả phòng phải sau ngày nhận phòng!', 'danger');
                return;
            }
            
            // Hiển thị loading
            checkAvailabilityBtn.disabled = true;
            checkAvailabilityBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Đang kiểm tra...';

            const roomId = document.querySelector('input[name="room_id"]').value;
            const params = new URLSearchParams({
                room_id: roomId,
                date_in: dateIn,
                date_out: dateOut
            });

            fetch(`{{ route('dathang.check') }}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(result => {
                if (result.success && result.available) {
                    showAvailabilityResult(`
                        <div class="row">
                            <div class="col-md-8">
                                <strong>✅ Có thể đặt phòng!</strong><br>
                                • Phòng: <strong>{{ $room->name }}</strong><br>
                                • Thời gian: <strong>${formatDate(dateIn)}</strong> đến <strong>${formatDate(dateOut)}</strong>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                    `, 'success');

                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-primary');
                    submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>XÁC NHẬN ĐẶT PHÒNG';
                } else {
                    showAvailabilityResult(`
                        <div class="row">
                            <div class="col-md-8">
                                <strong>❌ Không thể đặt phòng!</strong><br>
                                • Phòng đã có đơn trong khoảng thời gian này.<br>
                                • <strong>Vui lòng chọn thời gian khác hoặc phòng khác!</strong>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="bi bi-x-circle text-danger" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                    `, 'danger');
                    submitBtn.disabled = true;
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-secondary');
                }
            })
            .catch(() => {
                showAvailabilityResult('Có lỗi khi kiểm tra khả năng đặt phòng. Vui lòng thử lại.', 'danger');
                submitBtn.disabled = true;
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-secondary');
            })
            .finally(() => {
                checkAvailabilityBtn.disabled = false;
                checkAvailabilityBtn.innerHTML = '<i class="bi bi-search me-2"></i>Kiểm tra khả năng đặt phòng';
            });
        });
        
        // Hàm hiển thị kết quả kiểm tra
        function showAvailabilityResult(message, type) {
            availabilityMessage.innerHTML = message;
            
            // Sử dụng màu tùy chỉnh thay vì Bootstrap classes
            if (type === 'success') {
                availabilityAlert.className = 'alert border-0 rounded-3';
                availabilityAlert.style.background = 'linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%)';
                availabilityAlert.style.border = '1px solid #28a745 !important';
                availabilityAlert.style.color = '#155724';
            } else if (type === 'danger') {
                availabilityAlert.className = 'alert border-0 rounded-3';
                availabilityAlert.style.background = 'linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%)';
                availabilityAlert.style.border = '1px solid #dc3545 !important';
                availabilityAlert.style.color = '#721c24';
            }
            
            availabilityResult.style.display = 'block';
            
            // Scroll đến kết quả
            availabilityResult.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Hàm format ngày cho hiển thị (không thay đổi input)
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: undefined,
                minute: undefined
            });
        }
        
        // Hàm format ngày cho input date (YYYY-MM-DD)
        function formatDateForInput(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            
            return `${year}-${month}-${day}`;
        }
        

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

/* Session Data Styling */
.alert-info {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dfa974 !important;
    color: #495057;
    border-radius: 8px;
}

.alert-info i {
    color: #dfa974;
}

.alert-info strong {
    color: #dfa974;
}

/* Readonly Fields */
.form-control[readonly] {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
    cursor: not-allowed;
}

.form-control[readonly]:focus {
    border-color: #dee2e6;
    box-shadow: none;
}

/* Session Info Section */
.text-primary.fw-semibold {
    color: #dfa974 !important;
}

.bi-calendar-event {
    color: #dfa974;
}

@media (max-width: 768px) {
    .sticky-top {
        position: static;
        margin-top: 20px;
    }
}
</style>

@endsection