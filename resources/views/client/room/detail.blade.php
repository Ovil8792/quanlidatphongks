@extends("layout.main")

@section("main")
<!-- Breadcrumb Section Begin -->
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <h2>Chi tiết phòng</h2>
                    <div class="bt-option">
                        <a href="{{ route('client.index') }}">{{ __("messages.Home") }}</a>
                        <span>{{__("messages.Rooms")}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section End -->

<!-- Room Details Section Begin -->
<section class="room-details-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="room-details-item">
                    @if($room->pimage)
                        <img src="{{ asset('storage/upload/' . $room->pimage) }}" alt="{{ $room->name }}" class="img-fluid">
                    @else
                        <img src="{{ asset('img/room/room-1.jpg') }}" alt="Default room image" class="img-fluid">
                    @endif
                    
                    <div class="rd-text">
                        <div class="rd-title">
                            <h3>{{ $room->name }}</h3>
                            <div class="rdt-right">
                                <div class="rating">
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star-half_alt"></i>
                                </div>
                                <a href="{{ route('dathang.form', ['id' => $room->id]) }}" class="btn btn-primary">Đặt ngay</a>
                            </div>
                        </div>
                        <h2>{{ number_format($room->base_price,0,",",".")}}<span>VND/Đêm</span></h2>
                        
                        <table class="table table-borderless">
                            <tbody>
                                @if($room->room_area)
                                <tr>
                                    <td class="r-o"><i class="fa fa-arrows-alt me-2"></i>Diện tích:</td>
                                    <td>{{ $room->room_area }} m²</td>
                                </tr>
                                @endif
                                @if($room->max_guests)
                                <tr>
                                    <td class="r-o"><i class="fa fa-users me-2"></i>Sức chứa:</td>
                                    <td>Tối đa {{ $room->max_guests }} người</td>
                                </tr>
                                @endif
                                @if($room->bed_count)
                                <tr>
                                    <td class="r-o"><i class="fa fa-bed me-2"></i>Giường:</td>
                                    <td>{{ $room->bed_count }} giường</td>
                                </tr>
                                @endif
                                @if($room->floor)
                                <tr>
                                    <td class="r-o"><i class="fa fa-building me-2"></i>Tầng:</td>
                                    <td>{{ $room->floor }}</td>
                                </tr>
                                @endif
                                @if($room->code)
                                <tr>
                                    <td class="r-o"><i class="fa fa-hashtag me-2"></i>Mã phòng:</td>
                                    <td>{{ $room->code }}</td>
                                </tr>
                                @endif
                                @if($room->amenities)
                                <tr>
                                    <td class="r-o"><i class="fa fa-star me-2"></i>Tiện ích:</td>
                                    <td>
                                        <div class="amenities-tags">
                                            @foreach(explode(',', $room->amenities) as $amenity)
                                                @php $amenity = trim($amenity); @endphp
                                                @if($amenity)
                                                    <span class="badge bg-light text-dark me-1 mb-1">{{ $amenity }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        
                        @if($room->description)
                        <div class="f-para">
                            <h5><i class="fa fa-info-circle me-2"></i>Mô tả:</h5>
                            <p>{{ $room->description }}</p>
                        </div>
                        @endif
                        
                        @if($room->requirements)
                        <div class="requirements mt-3">
                            <h5><i class="fa fa-exclamation-triangle me-2"></i>Yêu cầu đặc biệt:</h5>
                            <p class="text-muted">{{ $room->requirements }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if(isset($imglist) && count($imglist) > 0)
                <div class="imglist mt-4">
                    <h4><i class="fa fa-images me-2"></i>Hình ảnh phòng</h4>
                    <div class="row">
                        @foreach ($imglist as $khoanh)
                        <div class="col-md-3 col-sm-4 col-6 mb-3">
                            <img src="{{ asset('storage/upload/' . $khoanh->imgname) }}" 
                                 alt="Room image" 
                                 class="img-fluid rounded shadow-sm"
                                 style="width: 100%; height: 150px; object-fit: cover; cursor: pointer;"
                                 onclick="openImageModal('{{ asset('storage/upload/' . $khoanh->imgname) }}')">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div class="rd-reviews mt-4">
                    <h4><i class="fa fa-comments me-2"></i>Đánh giá từ khách hàng</h4>
                    
                    @if(isset($reviews) && count($reviews) > 0)
                        @foreach($reviews as $review)
                        <div class="review-item">
                            <div class="ri-pic">
                                <img src="{{ asset('img/room/avatar/avatar-1.jpg') }}" alt="Avatar">
                            </div>
                            <div class="ri-text">
                                <span>{{ $review->created_at ? $review->created_at->format('d M Y') : 'N/A' }}</span>
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= ($review->rating ?? 0))
                                            <i class="icon_star"></i>
                                        @else
                                            <i class="icon_star_alt"></i>
                                        @endif
                                    @endfor
                                </div>
                                <h5>{{ $review->guest_name ?? 'Khách hàng' }}</h5>
                                <p>{{ $review->comment ?? 'Không có bình luận' }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fa fa-comment-slash fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Chưa có đánh giá nào cho phòng này</p>
                        </div>
                    @endif
                </div>
                
                <div class="review-add mt-4">
                    <h4><i class="fa fa-edit me-2"></i>Viết đánh giá</h4>
                    <form method="POST" action="{{ route('client.p_review', ['id' => $room->id]) }}" class="ra-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <h5>Chọn kiểu đánh giá:</h5>
                                <div class="rating-type mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="rate" value="star" id="rate_star" checked>
                                        <label class="form-check-label" for="rate_star">Đánh giá bằng sao</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="rate" value="number" id="rate_number">
                                        <label class="form-check-label" for="rate_number">Đánh giá bằng số</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div id="star-rating-wrapper">
                                    <h6>Đánh giá bằng sao:</h6>
                                    <div id="star-rating" class="mb-3"></div>
                                </div>
                                <div id="number-rating-wrapper" class="d-none">
                                    <h6>Đánh giá bằng số (1 - 5):</h6>
                                    <input type="number" name="rating_number" min="1" max="5" step="0.1" 
                                           class="form-control" style="width: 150px;">
                                </div>
                            </div>
                            
                            @if (!session()->has('user'))
                            <div class="col-lg-6">
                                <input required type="text" name="guest_name" placeholder="Tên của bạn *" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <input required type="email" name="guest_email" placeholder="Email *" class="form-control">
                            </div>
                            @endif
                            
                            <div class="col-lg-12 mt-3">
                                <textarea required placeholder="Nội dung đánh giá..." name="comment" 
                                          class="form-control" rows="4"></textarea>
                            </div>
                            
                            <div class="col-lg-12 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-paper-plane me-2"></i>Gửi đánh giá
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
          
            <div class="col-lg-4">
                <div class="room-booking card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fa fa-calendar-check me-2"></i>Đặt phòng ngay</h3>
                    </div>
                    <div class="card-body">
                        <form id="searchForm" action="{{ route('search.pending') }}" method="GET">
                            <div class="mb-3">
                                <label for="date-in" class="form-label">{{ __('messages.cin') }}:</label>
                                <input value="{{ old('date_in') }}" type="date" id="date-in" name="date_in" 
                                       class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="date-out" class="form-label">{{ __('messages.cout') }}:</label>
                                <input type="date" id="date-out" name="date_out" 
                                       class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="guest" class="form-label">{{ __('messages.guest') }}:</label>
                                <select onchange="toggleCustomGuest(this)" id="guest" name="guest" class="form-select">
                                    <option value="2l1n" {{ old('guest') == '2l1n' ? 'selected' : '' }}>2 lớn + 1 nhỏ</option>
                                    <option value="4l3n" {{ old('guest') == '4l3n' ? 'selected' : '' }}>4 lớn + 3 nhỏ</option>
                                    <option value="custom" {{ old('guest') == 'custom' ? 'selected' : '' }}>Nhập riêng</option>
                                </select>
                                <input class="form-control mt-2" type="number" id="customGuest" name="custom_guest"
                                       placeholder="Nhập số người" min="1" style="display: none;" 
                                       value="{{ old('custom_guest') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="room" class="form-label">{{ __('messages.rn') }}:</label>
                                <select id="room" name="room" class="form-select">
                                    <option value="1" {{ old('room') == '1' ? 'selected' : '' }}>1 Phòng</option>
                                    <option value="2" {{ old('room') == '2' ? 'selected' : '' }}>2 Phòng</option>
                                    <option value="3" {{ old('room') == '3' ? 'selected' : '' }}>3 Phòng</option>
                                    <option value="4" {{ old('room') == '4' ? 'selected' : '' }}>4 Phòng</option>
                                    <option value="10" {{ old('room') == '10' ? 'selected' : '' }}>10 Phòng</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="search" class="form-label">Vị trí:</label>
                                <input id="search" name="keyword" value="{{ old('keyword') }}"
                                       class="form-control" type="search" placeholder="Tìm kiếm vị trí...">
                                <input type="hidden" name="selected_location" value="{{ old('selected_location') }}" id="selectedLocation">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search me-2"></i>Tìm kiếm
                            </button>
                        </form>
                        
                        <hr>
                        
                        <div class="room-info">
                            <h6><i class="fa fa-info-circle me-2"></i>Thông tin phòng</h6>
                            <ul class="list-unstyled">
                                @if($room->room_area)
                                    <li><i class="fa fa-arrows-alt me-2 text-primary"></i>Diện tích: {{ $room->room_area }}m²</li>
                                @endif
                                @if($room->max_guests)
                                    <li><i class="fa fa-users me-2 text-primary"></i>Sức chứa: {{ $room->max_guests }} người</li>
                                @endif
                                @if($room->bed_count)
                                    <li><i class="fa fa-bed me-2 text-primary"></i>Số giường: {{ $room->bed_count }}</li>
                                @endif
                                @if($room->floor)
                                    <li><i class="fa fa-building me-2 text-primary"></i>Tầng: {{ $room->floor }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Room Details Section End -->

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hình ảnh phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Room image" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function toggleCustomGuest(select) {
    const customInput = document.getElementById("customGuest");
    if (select.value === "custom") {
        customInput.style.display = "block";
        customInput.required = true;
    } else {
        customInput.style.display = "none";
        customInput.required = false;
    }
}

function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

// Rating system
document.addEventListener('DOMContentLoaded', function() {
    const starRating = document.getElementById('star-rating');
    const rateStar = document.getElementById('rate_star');
    const rateNumber = document.getElementById('rate_number');
    const starWrapper = document.getElementById('star-rating-wrapper');
    const numberWrapper = document.getElementById('number-rating-wrapper');
    
    // Create star rating
    if (starRating) {
        for (let i = 1; i <= 5; i++) {
            const star = document.createElement('i');
            star.className = 'icon_star_alt';
            star.style.cursor = 'pointer';
            star.style.fontSize = '24px';
            star.style.marginRight = '5px';
            star.dataset.rating = i;
            
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                // Update all stars
                starRating.querySelectorAll('i').forEach((s, index) => {
                    if (index < rating) {
                        s.className = 'icon_star';
                    } else {
                        s.className = 'icon_star_alt';
                    }
                });
                
                // Add hidden input for rating
                let hiddenInput = document.querySelector('input[name="rating"]');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'rating';
                    starRating.appendChild(hiddenInput);
                }
                hiddenInput.value = rating;
            });
            
            starRating.appendChild(star);
        }
    }
    
    // Toggle rating type
    if (rateStar && rateNumber) {
        rateStar.addEventListener('change', function() {
            starWrapper.classList.remove('d-none');
            numberWrapper.classList.add('d-none');
        });
        
        rateNumber.addEventListener('change', function() {
            starWrapper.classList.add('d-none');
            numberWrapper.classList.remove('d-none');
        });
    }
    
    // Form validation
    const searchForm = document.getElementById('searchForm');
    const dateIn = document.getElementById('date-in');
    const dateOut = document.getElementById('date-out');
    
    if (searchForm && dateIn && dateOut) {
        searchForm.addEventListener('submit', function(e) {
            if (!dateIn.value || !dateOut.value) {
                e.preventDefault();
                alert('Vui lòng chọn ngày nhận phòng và ngày trả phòng!');
                return false;
            }
            
            if (dateOut.value <= dateIn.value) {
                e.preventDefault();
                alert('Ngày trả phòng phải sau ngày nhận phòng!');
                return false;
            }
        });
        
        // Set min cho date-out khi date-in thay đổi
        dateIn.addEventListener('change', function() {
            dateOut.min = this.value;
            if (dateOut.value && dateOut.value <= this.value) {
                dateOut.value = '';
            }
        });
    }
    
    // Initialize custom guest display
    toggleCustomGuest(document.getElementById('guest'));
});
</script>

<style>
.room-details-item {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.room-details-item img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.rd-text {
    padding: 30px;
}

.rd-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.rd-title h3 {
    color: #dfa974;
    margin: 0;
}

.rd-title h2 {
    color: #333;
    margin-bottom: 20px;
}

.rd-title h2 span {
    font-size: 18px;
    color: #666;
    font-weight: normal;
}

.table td {
    padding: 12px 0;
    border: none;
    vertical-align: middle;
}

.r-o {
    font-weight: 600;
    color: #333;
    width: 40%;
}

.amenities-tags .badge {
    font-size: 0.9rem;
    padding: 8px 12px;
}

.room-booking {
    border: none;
    border-radius: 10px;
}

.room-booking .card-header {
    border-radius: 10px 10px 0 0;
}

.room-info ul li {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.room-info ul li:last-child {
    border-bottom: none;
}

.review-item {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.ri-pic img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.rating i {
    color: #ffc107;
    margin-right: 2px;
}

.icon_star_alt {
    color: #ddd;
}

#star-rating i:hover {
    color: #ffc107;
}

@media (max-width: 768px) {
    .rd-title {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .rd-text {
        padding: 20px;
    }
    
    .room-booking {
        margin-top: 30px;
    }
}
</style>

@endsection