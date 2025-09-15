@extends("admin.layout.main")

@section("page-title", "Thêm phòng mới")

@section("main")
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-plus-circle text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">Thêm phòng mới</h5>
                            <p class="text-muted mb-0">Điền thông tin phòng để thêm vào hệ thống</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.storeroom') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h6 class="fw-semibold mb-3 text-primary">
                                    <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên phòng <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @php
                                    $code = [1,2,3,4,5,6,7,8,9];
                                @endphp
                                <div class="mb-3">
                                    <label for="code" class="form-label">Mã phòng <span class="text-danger">*</span></label>
                                    <select name="code" id="code" class="form-control @error('code') is-invalid @enderror" required>
                                        <option value="">-- Chọn mã phòng --</option>
                                        @foreach($code as $c)
                                            <option value="{{ $c }}" {{ old('code') == $c ? 'selected' : '' }}>
                                                {{ $c }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="floor" class="form-label">Tầng <span class="text-danger">*</span></label>
                                    <select name="floor" id="floor" class="form-control @error('floor') is-invalid @enderror" required>
                                        <option value="">-- Chọn tầng --</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                    </select>
                                    @error('floor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="category" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($catelist as $category)
                                            <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price', 0) }}" required>
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Room Details -->
                            <div class="col-md-6">
                                <h6 class="fw-semibold mb-3 text-primary">
                                    <i class="bi bi-rulers me-2"></i>Thông tin chi tiết
                                </h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="room_area" class="form-label">Diện tích phòng (m²)</label>
                                        <input type="number" step="0.1" class="form-control" 
                                               id="room_area" name="room_area" value="{{ old('room_area') }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="bathroom_area" class="form-label">Diện tích phòng tắm (m²)</label>
                                        <input type="number" step="0.1" class="form-control" 
                                               id="bathroom_area" name="bathroom_area" value="{{ old('bathroom_area') }}">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="max_guests" class="form-label">Số khách tối đa</label>
                                        <input type="number" class="form-control" 
                                               id="max_guests" name="max_guests" value="{{ old('max_guests') }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="bed_count" class="form-label">Số lượng giường</label>
                                        <input type="number" class="form-control" 
                                               id="bed_count" name="bed_count" value="{{ old('bed_count') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description & Amenities -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="fw-semibold mb-3 text-primary">
                                    <i class="bi bi-card-text me-2"></i>Mô tả & Tiện ích
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="desc" class="form-label">Mô tả phòng</label>
                                    <textarea class="form-control" id="desc" name="desc" rows="3" 
                                              placeholder="Mô tả chi tiết về phòng...">{{ old('desc') }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="amenities" class="form-label">Tiện ích phòng</label>
                                    
                                    <!-- Tiện ích có sẵn -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-muted">Tiện ích có sẵn:</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="wifi" name="amenities_checkbox[]" value="WiFi" {{ in_array('WiFi', old('amenities_checkbox', explode(', ', old('amenities', '')))) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="wifi">WiFi miễn phí</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="tv" name="amenities_checkbox[]" value="TV" {{ in_array('TV', old('amenities_checkbox', explode(', ', old('amenities', '')))) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tv">TV màn hình phẳng</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="ac" name="amenities_checkbox[]" value="Máy lạnh" {{ in_array('Máy lạnh', old('amenities_checkbox', explode(', ', old('amenities', '')))) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="ac">Máy lạnh</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="heater" name="amenities_checkbox[]" value="Máy sưởi" {{ in_array('Máy sưởi', old('amenities_checkbox', explode(', ', old('amenities', '')))) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="heater">Máy sưởi</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="balcony" name="amenities_checkbox[]" value="Ban công" {{ in_array('Ban công', old('amenities_checkbox', explode(', ', old('amenities', '')))) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="balcony">Ban công</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="minibar" name="amenities_checkbox[]" value="Minibar" {{ in_array('Minibar', old('amenities_checkbox', explode(', ', old('amenities', '')))) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="minibar">Minibar</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="safe" name="amenities_checkbox[]" value="Két an toàn" {{ in_array('Két an toàn', old('amenities_checkbox', explode(', ', old('amenities', '')))) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="safe">Két an toàn</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="hairdryer" name="amenities_checkbox[]" value="Máy sấy tóc" {{ in_array('Máy sấy tóc', old('amenities_checkbox', explode(', ', old('amenities', '')))) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="hairdryer">Máy sấy tóc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tiện ích tùy chỉnh -->
                                    <div class="mb-2">
                                        <label for="amenities_custom" class="form-label fw-semibold text-muted">Tiện ích khác:</label>
                                        <textarea class="form-control" id="amenities_custom" name="amenities_custom" rows="2" 
                                                  placeholder="Nhập các tiện ích khác (tùy chọn)...">{{ old('amenities_custom', '') }}</textarea>
                                        <div class="form-text">Các tiện ích này sẽ được thêm vào danh sách tiện ích có sẵn</div>
                                    </div>
                                    
                                    <!-- Hiển thị tiện ích đã chọn -->
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-muted">Tiện ích đã chọn:</label>
                                        <div id="selectedAmenities" class="p-2 bg-light rounded border">
                                            @if(old('amenities'))
                                                @php
                                                    $amenitiesArray = explode(', ', old('amenities'));
                                                @endphp
                                                @foreach($amenitiesArray as $amenity)
                                                    <span class="badge bg-primary me-1 mb-1">{{ trim($amenity) }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Chưa có tiện ích nào được chọn</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Input ẩn để lưu tất cả tiện ích -->
                                    <input type="hidden" id="amenities" name="amenities" value="{{ old('amenities') }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="requirements" class="form-label">Yêu cầu đặc biệt</label>
                                    <textarea class="form-control" id="requirements" name="requirements" rows="2" 
                                              placeholder="Các yêu cầu đặc biệt cho phòng...">{{ old('requirements') }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái phòng</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Có sẵn</option>
                                        <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Đã có khách</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                                        <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Đã đặt trước</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Image Upload -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="fw-semibold mb-3 text-primary">
                                    <i class="bi bi-image me-2"></i>Hình ảnh phòng
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="pimage" class="form-label">Ảnh chính phòng</label>
                                    <input type="file" class="form-control @error('pimage') is-invalid @enderror" 
                                           id="pimage" name="pimage" accept="image/*">
                                    <div class="form-text">Chọn ảnh đại diện cho phòng (JPG, PNG, GIF)</div>
                                    @error('pimage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div id="imagePreview" class="d-none">
                                    <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.roomlist') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Thêm phòng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('pimage');
    const imagePreview = document.getElementById('imagePreview');
    const preview = document.getElementById('preview');
    
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('d-none');
        }
    });
    
    // Room code filtering based on floor
    const floorSelect = document.getElementById('floor');
    const codeSelect = document.getElementById('code');
    const allCodeOptions = Array.from(codeSelect.options).slice(1); // Bỏ qua option đầu tiên (-- Chọn mã phòng --)
    
    // Lưu trữ tất cả mã phòng ban đầu
    const originalCodeOptions = allCodeOptions.map(option => ({
        value: option.value,
        text: option.text,
        element: option
    }));
    
    // Hàm lọc mã phòng theo tầng
    async function filterRoomCodesByFloor(floor) {
        if (!floor) {
            // Nếu không chọn tầng, hiển thị tất cả mã phòng
            originalCodeOptions.forEach(option => {
                option.element.style.display = '';
                option.element.disabled = false;
            });
            return;
        }
        
        try {
            // Gọi API để lấy mã phòng đã sử dụng
            const response = await fetch(`/api/used-room-codes/${floor}`);
            const data = await response.json();
            const usedCodes = data.used_codes;
            
            // Ẩn các mã phòng đã sử dụng
            let availableCodes = 0;
            originalCodeOptions.forEach(option => {
                if (usedCodes.includes(parseInt(option.value))) {
                    option.element.style.display = 'none';
                    option.element.disabled = true;
                } else {
                    option.element.style.display = '';
                    option.element.disabled = false;
                    availableCodes++;
                }
            });
            
            // Reset selection nếu mã phòng hiện tại đã bị ẩn
            if (codeSelect.value && usedCodes.includes(parseInt(codeSelect.value))) {
                codeSelect.value = '';
            }
            
            // Hiển thị thông báo nếu không có mã phòng nào khả dụng
            if (availableCodes === 0) {
                // Thêm thông báo vào select
                const noOptionMessage = codeSelect.querySelector('.no-available-message');
                if (!noOptionMessage) {
                    const option = document.createElement('option');
                    option.className = 'no-available-message';
                    option.value = '';
                    option.textContent = '-- Không có mã phòng khả dụng ở tầng này --';
                    option.disabled = true;
                    codeSelect.appendChild(option);
                }
            } else {
                // Xóa thông báo nếu có mã phòng khả dụng
                const noOptionMessage = codeSelect.querySelector('.no-available-message');
                if (noOptionMessage) {
                    noOptionMessage.remove();
                }
            }
            
        } catch (error) {
            console.error('Lỗi khi lấy dữ liệu mã phòng:', error);
        }
    }
    
    // Xử lý sự kiện thay đổi tầng
    floorSelect.addEventListener('change', function() {
        const selectedFloor = this.value;
        const currentCode = codeSelect.value;
        
        // Reset mã phòng nếu đã chọn trước đó
        if (currentCode) {
            codeSelect.value = '';
        }
        
        filterRoomCodesByFloor(selectedFloor);
    });
    
    // Amenities handling
    const amenitiesCheckboxes = document.querySelectorAll('input[name="amenities_checkbox[]"]');
    const amenitiesCustom = document.getElementById('amenities_custom');
    const selectedAmenitiesDiv = document.getElementById('selectedAmenities');
    const amenitiesHiddenInput = document.getElementById('amenities');
    
    function updateSelectedAmenities() {
        const selectedCheckboxes = Array.from(amenitiesCheckboxes).filter(cb => cb.checked);
        const customText = amenitiesCustom.value.trim();
        
        let allAmenities = selectedCheckboxes.map(cb => cb.value);
        if (customText) {
            allAmenities.push(customText);
        }
        
        // Cập nhật input ẩn
        amenitiesHiddenInput.value = allAmenities.join(', ');
        
        // Cập nhật hiển thị
        if (allAmenities.length > 0) {
            selectedAmenitiesDiv.innerHTML = allAmenities.map(amenity => 
                `<span class="badge bg-primary me-1 mb-1">${amenity}</span>`
            ).join('');
        } else {
            selectedAmenitiesDiv.innerHTML = '<span class="text-muted">Chưa có tiện ích nào được chọn</span>';
        }
    }
    
    // Xử lý sự kiện checkbox
    amenitiesCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedAmenities);
    });
    
    // Xử lý sự kiện textarea tùy chỉnh
    amenitiesCustom.addEventListener('input', updateSelectedAmenities);
    
    // Khởi tạo hiển thị ban đầu
    updateSelectedAmenities();
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        // Kiểm tra các trường bắt buộc
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Kiểm tra logic nghiệp vụ
        const selectedFloor = floorSelect.value;
        const selectedCode = codeSelect.value;
        
        if (selectedFloor && selectedCode) {
            // Kiểm tra xem mã phòng có bị ẩn không (đã được sử dụng)
            const selectedOption = codeSelect.querySelector(`option[value="${selectedCode}"]`);
            if (selectedOption && selectedOption.style.display === 'none') {
                alert('Mã phòng này đã được sử dụng ở tầng này. Vui lòng chọn mã phòng khác!');
                e.preventDefault();
                return;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
        }
    });
});
</script>
@endsection