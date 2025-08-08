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
                                        <input type="number" class="form-control" 
                                               id="max_guests" name="max_guests" value="{{ old('max_guests', $roomdata->max_guests) }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="bed_count" class="form-label">Số lượng giường</label>
                                        <input type="number" class="form-control" 
                                               id="bed_count" name="bed_count" value="{{ old('bed_count', $roomdata->bed_count) }}">
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
                                
                                <div class="mb-3">
                                    <label for="amenities" class="form-label">Tiện ích phòng</label>
                                    <textarea class="form-control" id="amenities" name="amenities" rows="3" 
                                              placeholder="Liệt kê các tiện ích có sẵn...">{{ old('amenities', $roomdata->amenities) }}</textarea>
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