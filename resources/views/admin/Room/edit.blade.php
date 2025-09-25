@extends("admin.layout.main")

@section("page-title", "Chỉnh sửa phòng")

@section("main")
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-pencil-square text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">Chỉnh sửa phòng</h5>
                            <p class="text-muted mb-0">Cập nhật thông tin phòng: {{ $roomdata->name }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.updroom', ['id' => $roomdata->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h6 class="fw-semibold mb-3 text-primary">
                                    <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên phòng <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $roomdata->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="code" class="form-label">Mã phòng <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $roomdata->code) }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="floor" class="form-label">Tầng <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('floor') is-invalid @enderror" 
                                           id="floor" name="floor" value="{{ old('floor', $roomdata->floor) }}" required>
                                    @error('floor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="category" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($cat as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category', $roomdata->category_id) == $category->id ? 'selected' : '' }}>
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
                                               id="price" name="price" value="{{ old('price', $roomdata->base_price) }}" required>
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
                                               id="room_area" name="room_area" value="{{ old('room_area', $roomdata->room_area) }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="bathroom_area" class="form-label">Diện tích phòng tắm (m²)</label>
                                        <input type="number" step="0.1" class="form-control" 
                                               id="bathroom_area" name="bathroom_area" value="{{ old('bathroom_area', $roomdata->bathroom_area) }}">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="max_guests" class="form-label">Số khách tối đa</label>
                                        <select class="form-select" id="max_guests" name="max_guests">
                                            <option value="1" {{ (string)old('max_guests', $roomdata->max_guests) === '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ (string)old('max_guests', $roomdata->max_guests) === '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ (string)old('max_guests', $roomdata->max_guests) === '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ (string)old('max_guests', $roomdata->max_guests) === '4' ? 'selected' : '' }}>4</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="bed_count" class="form-label">Số lượng giường</label>
                                        <select class="form-select" id="bed_count" name="bed_count">
                                            <option value="1" {{ (string)old('bed_count', $roomdata->bed_count) === '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ (string)old('bed_count', $roomdata->bed_count) === '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ (string)old('bed_count', $roomdata->bed_count) === '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ (string)old('bed_count', $roomdata->bed_count) === '4' ? 'selected' : '' }}>4</option>
                                        </select>
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
                                              placeholder="Mô tả chi tiết về phòng...">{{ old('desc', $roomdata->description) }}</textarea>
                                </div>
                                
                                @php
                                    $predefinedAmenities = ['WiFi', 'TV', 'Máy lạnh', 'Máy sưởi', 'Ban công', 'Minibar', 'Két an toàn', 'Máy sấy tóc'];
                                    $currentAmenities = $roomdata->amenities ? explode(', ', $roomdata->amenities) : [];
                                    $customAmenities = array_filter($currentAmenities, function($amenity) use ($predefinedAmenities) {
                                        return !in_array(trim($amenity), $predefinedAmenities);
                                    });
                                    $customAmenitiesText = implode(', ', $customAmenities);
                                @endphp
                                
                                <div class="mb-3">
                                    <label for="amenities" class="form-label">Tiện ích phòng</label>
                                    
                                    <!-- Tiện ích có sẵn -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-muted">Tiện ích có sẵn:</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="wifi" name="amenities_checkbox[]" value="WiFi" {{ in_array('WiFi', old('amenities_checkbox', $currentAmenities)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="wifi">WiFi miễn phí</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="tv" name="amenities_checkbox[]" value="TV" {{ in_array('TV', old('amenities_checkbox', $currentAmenities)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tv">TV màn hình phẳng</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="ac" name="amenities_checkbox[]" value="Máy lạnh" {{ in_array('Máy lạnh', old('amenities_checkbox', $currentAmenities)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="ac">Máy lạnh</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="heater" name="amenities_checkbox[]" value="Máy sưởi" {{ in_array('Máy sưởi', old('amenities_checkbox', $currentAmenities)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="heater">Máy sưởi</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="balcony" name="amenities_checkbox[]" value="Ban công" {{ in_array('Ban công', old('amenities_checkbox', $currentAmenities)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="balcony">Ban công</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="minibar" name="amenities_checkbox[]" value="Minibar" {{ in_array('Minibar', old('amenities_checkbox', $currentAmenities)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="minibar">Minibar</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="safe" name="amenities_checkbox[]" value="Két an toàn" {{ in_array('Két an toàn', old('amenities_checkbox', $currentAmenities)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="safe">Két an toàn</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="hairdryer" name="amenities_checkbox[]" value="Máy sấy tóc" {{ in_array('Máy sấy tóc', old('amenities_checkbox', $currentAmenities)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="hairdryer">Máy sấy tóc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tiện ích tùy chỉnh -->
                                    <div class="mb-2">
                                        <label for="amenities_custom" class="form-label fw-semibold text-muted">Tiện ích khác:</label>
                                        <textarea class="form-control" id="amenities_custom" name="amenities_custom" rows="2" 
                                                  placeholder="Nhập các tiện ích khác (tùy chọn)...">{{ old('amenities_custom', $customAmenitiesText) }}</textarea>
                                        <div class="form-text">Các tiện ích này sẽ được thêm vào danh sách tiện ích có sẵn</div>
                                    </div>
                                    
                                    <!-- Hiển thị tiện ích đã chọn -->
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-muted">Tiện ích đã chọn:</label>
                                        <div id="selectedAmenities" class="p-2 bg-light rounded border">
                                            @if($roomdata->amenities)
                                                @foreach($currentAmenities as $amenity)
                                                    @if(in_array(trim($amenity), $predefinedAmenities))
                                                        <span class="badge bg-primary me-1 mb-1">{{ trim($amenity) }}</span>
                                                    @endif
                                                @endforeach
                                                @if($customAmenitiesText)
                                                    @foreach(explode(', ', $customAmenitiesText) as $customAmenity)
                                                        <span class="badge bg-success me-1 mb-1">{{ trim($customAmenity) }}</span>
                                                    @endforeach
                                                @endif
                                            @else
                                                <span class="text-muted">Chưa có tiện ích nào được chọn</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Input ẩn để lưu tất cả tiện ích -->
                                    <input type="hidden" id="amenities" name="amenities" value="{{ old('amenities', $roomdata->amenities) }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="requirements" class="form-label">Yêu cầu đặc biệt</label>
                                    <textarea class="form-control" id="requirements" name="requirements" rows="2" 
                                              placeholder="Các yêu cầu đặc biệt cho phòng...">{{ old('requirements', $roomdata->requirements) }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái phòng</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="available" {{ old('status', $roomdata->status) == 'available' ? 'selected' : '' }}>Có sẵn</option>
                                        <option value="occupied" {{ old('status', $roomdata->status) == 'occupied' ? 'selected' : '' }}>Đã có khách</option>
                                        <option value="maintenance" {{ old('status', $roomdata->status) == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                                        <option value="reserved" {{ old('status', $roomdata->status) == 'reserved' ? 'selected' : '' }}>Đã đặt trước</option>
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
                                    <div class="form-text">Chọn ảnh mới để thay thế ảnh hiện tại (JPG, PNG, GIF)</div>
                                    @error('pimage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                @if($roomdata->pimage)
                                <div class="mb-3">
                                    <label class="form-label">Ảnh hiện tại</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/upload/' . $roomdata->pimage) }}" 
                                             alt="{{ $roomdata->name }}" 
                                             class="img-thumbnail" 
                                             style="max-width: 150px; height: auto;">
                                        <div>
                                            <small class="text-muted">Ảnh hiện tại sẽ được giữ nguyên nếu không chọn ảnh mới</small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div id="imagePreview" class="d-none">
                                    <label class="form-label">Ảnh mới</label>
                                    <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                                
                                <input type="hidden" name="old_img" value="{{ $roomdata->pimage }}">
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.roomlist') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-check-circle me-2"></i>Cập nhật phòng
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
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
        }
    });
});
</script>
@endsection