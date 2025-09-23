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

                <!-- Agoda style highlight section -->
                @endif
            </div>
            <div class="col-lg-4">
                <div class="room-booking card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0 text-center"><i class="fa fa-calendar-check me-2"></i> Đặt phòng ngay</h3>
                    </div>
                    <div class="card-body">

                        <form id="searchForm" action="{{ route('search.pending') }}" method="GET">
                            <div class="mb-3">
                                <label for="date-in" class="form-label">{{ __('messages.cin') }}:</label>
                                <input value="{{ isset($bookingData['date_in']) ? $bookingData['date_in'] : old('date_in') }}"
                                    type="date" id="date-in" name="date_in"
                                    class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="date-out" class="form-label">{{ __('messages.cout') }}:</label>
                                <input type="date" id="date-out" name="date_out"
                                    value="{{ isset($bookingData['date_out']) ? $bookingData['date_out'] : old('date_out') }}"
                                    class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="guest" class="form-label">{{ __('messages.guest') }}:</label>
                                <select onchange="toggleCustomGuest(this)" id="guest" name="guest" class="form-select">
                                    <option value="2l1n" {{ (isset($bookingData['guest']) && $bookingData['guest'] == '2l1n') || old('guest') == '2l1n' ? 'selected' : '' }}>2 lớn + 1 nhỏ</option>
                                    <option value="4l3n" {{ (isset($bookingData['guest']) && $bookingData['guest'] == '4l3n') || old('guest') == '4l3n' ? 'selected' : '' }}>4 lớn + 3 nhỏ</option>
                                    <option value="6l4n" {{ (isset($bookingData['guest']) && $bookingData['guest'] == '6l4n') || old('guest') == '6l4n' ? 'selected' : '' }}>6 lớn + 4 nhỏ</option>
                                    <option value="custom" {{ (isset($bookingData['guest']) && $bookingData['guest'] == 'custom') || old('guest') == 'custom' ? 'selected' : '' }}>Nhập riêng</option>
                                </select>
                                <div id="customGuestWrapper" class="mt-2" style="display: {{ (isset($bookingData['guest']) && $bookingData['guest'] == 'custom') ? 'block' : 'none' }};">
                                    <input class="form-control" type="number" id="customGuest" name="custom_guest"
                                        placeholder="Nhập số người" min="1" max="20"
                                        value="{{ isset($bookingData['custom_guest']) ? $bookingData['custom_guest'] : old('custom_guest') }}"
                                        oninput="validateCustomGuest()">
                                    <small id="customGuestHelp" class="text-muted">Nhập số người từ 1-20</small>
                                </div>
                            </div>


                            <!-- <button type="submit" class="btn btn-primary w-100 mt-2">
                                <i class="fa fa-search me-2"></i>Tìm kiếm
                            </button> -->
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
                <br><br>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">


                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="badge bg-success">Vị trí 8,5</span>
                            <span class="badge bg-primary">Dịch vụ 8,0</span>
                            <span class="badge bg-info text-dark">Đáng giá tiền 7,9</span>
                            <span class="badge bg-secondary">Cơ sở vật chất 7,6</span>
                        </div>


                        <!-- Điểm vị trí -->
                        <div class="mb-4">
                            <span class="fw-bold fs-6 text-success">8,5 Tuyệt vời</span><br>
                            <small class="text-muted">Điểm đánh giá vị trí</small>
                            <p class="mb-0 mt-2">
                                <i class="fa fa-map-marker-alt text-danger me-2"></i>
                                Vị trí tuyệt vời - Cạnh bờ biển
                            </p>
                        </div>

                        <!-- Tiện ích -->
                        <div class="mb-4">
                            <i class="fa fa-parking text-primary me-2"></i>
                            Đỗ xe <span class="text-success fw-bold">MIỄN PHÍ</span>
                        </div>

                        <!-- Các địa danh nổi tiếng -->
                        <div>
                            <p class="fw-bold mb-3">Các địa danh nổi tiếng</p>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fa fa-umbrella-beach me-2"></i>Bãi sau <span class="float-end text-muted">200 m</span></li>
                                <li class="mb-2"><i class="fa fa-lightbulb me-2"></i>Ngọn hải đăng <span class="float-end text-muted">1,5 km</span></li>
                                <li class="mb-2"><i class="fa fa-cross me-2"></i>Tượng Chúa Kitô Vua <span class="float-end text-muted">1,6 km</span></li>
                                <li class="mb-2"><i class="fa fa-landmark me-2"></i>Bảo tàng Vũ khí Cổ <span class="float-end text-muted">1,8 km</span></li>
                                <li class="mb-2"><i class="fa fa-water me-2"></i>Bãi trước <span class="float-end text-muted">1,9 km</span></li>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="hotel-highlights mt-5">
            <h4 class="mb-3"><i class="fa fa-star me-2 text-warning"></i>Điểm nổi bật nhất</h4>
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-3">
                    <i class="fa fa-map-marker-alt fa-2x text-danger mb-2"></i>
                    <p class="mb-0">Ngay cạnh bờ biến</p>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <i class="fa fa-concierge-bell fa-2x text-primary mb-2"></i>
                    <p class="mb-0">Bàn tiếp tân 24 giờ</p>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <i class="fa fa-umbrella-beach fa-2x text-info mb-2"></i>
                    <p class="mb-0">Cách Bãi sau 200m</p>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <i class="fa fa-wifi fa-2x text-success mb-2"></i>
                    <p class="mb-0">Wi-Fi miễn phí</p>
                </div>
            </div>

            <hr>

            <h4 class="mb-3"><i class="fa fa-building me-2 text-warning"></i>Cơ sở vật chất</h4>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li><i class="fa fa-check text-success me-2"></i>Miễn phí Wi-Fi</li>
                        <li><i class="fa fa-check text-success me-2"></i>Bếp chung</li>
                        <li><i class="fa fa-check text-success me-2"></i>Đỗ xe miễn phí</li>
                        <li><i class="fa fa-check text-success me-2"></i>Giữ hành lý</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li><i class="fa fa-check text-success me-2"></i>Bàn tiếp tân 24h</li>
                        <li><i class="fa fa-check text-success me-2"></i>Ban công/sân hiên</li>
                        <li><i class="fa fa-check text-success me-2"></i>Phòng gia đình</li>
                        <li><i class="fa fa-check text-success me-2"></i>Dịch vụ phòng</li>
                    </ul>
                </div>
            </div>

            <hr>

            <h4 class="mb-3"><i class="fa fa-info-circle me-2 text-warning"></i>Về chúng tôi</h4>
            <p class="text-muted">
                Nằm ở vị trí trung tâm tại Thắng Tam của Vũng Tàu, chỗ nghỉ này đặt quý khách ở gần các điểm thu hút
                và tùy chọn ăn uống thú vị.
            </p>
        </div>
        <div class="container my-4">
            <div class="card shadow-sm border-0 rounded-3 bg-white">
                <div class="card-body">
                    <!-- Tiêu đề chính vàng nhạt -->
                    <h5 class="mb-3 fw-bold text-center" style="color:#f4c542;">
                        Tiện nghi và cơ sở vật chất
                    </h5>

                    <div class="row">
                        <!-- Cột 1 -->
                        <div class="col-md-3 mb-3">
                            <h6 class="fw-bold" style="color:#3399ff;">Ngôn ngữ</h6>
                            <ul class="list-unstyled small">
                                <li>Tiếng Việt</li>
                                <li>Tiếng Anh</li>
                            </ul>

                            <h6 class="fw-bold mt-3" style="color:#3399ff;">Truy cập Internet</h6>
                            <ul class="list-unstyled small">
                                <li>Dịch vụ Internet</li>
                                <li>Internet</li>
                                <li>Wi-Fi miễn phí trong tất cả các phòng</li>
                                <li>Wi-Fi ở nơi công cộng</li>
                            </ul>

                            <h6 class="fw-bold mt-3" style="color:#3399ff;">Ăn uống</h6>
                            <ul class="list-unstyled small">
                                <li>Bếp chung</li>
                                <li>Dịch vụ phòng</li>
                            </ul>
                        </div>

                        <!-- Cột 2 -->
                        <div class="col-md-3 mb-3">
                            <h6 class="fw-bold" style="color:#3399ff;">Độ sạch sẽ & an toàn</h6>
                            <ul class="list-unstyled small">
                                <li>Bác sỹ và y tá trực</li>
                                <li>Bảng niêm phong phòng</li>
                                <li>Có dịch vụ khử trùng</li>
                                <li>Giãn cách tối thiểu 1 mét</li>
                                <li>Khử trùng phòng nghỉ</li>
                                <li>Nước rửa tay</li>
                                <li>Phòng được vệ sinh giữa các lần ở</li>
                                <li>Sản phẩm làm sạch chống virus</li>
                                <li>Ứng dụng dịch vụ tại phòng</li>
                            </ul>
                        </div>

                        <!-- Cột 3 -->
                        <div class="col-md-3 mb-3">
                            <h6 class="fw-bold" style="color:#3399ff;">Dịch vụ & tiện nghi</h6>
                            <ul class="list-unstyled small">
                                <li>Đơn hàng tạp hóa</li>
                                <li>Giữ hành lý</li>
                                <li>Khu vực hút thuốc</li>
                                <li>Nhân viên chăm sóc khách hàng</li>
                                <li>Phòng chờ chung</li>
                                <li>Sân hiên</li>
                                <li>Thang máy</li>
                                <li>Tiện nghi cho khách khuyết tật</li>
                            </ul>

                            <h6 class="fw-bold mt-3" style="color:#3399ff;">Dành cho trẻ em</h6>
                            <ul class="list-unstyled small">
                                <li>Phòng gia đình</li>
                                <li>Thích hợp cho gia đình/trẻ em</li>
                            </ul>

                            <h6 class="fw-bold mt-3" style="color:#3399ff;">Dễ tiếp cận</h6>
                            <ul class="list-unstyled small">
                                <li>Bàn tiếp tân 24h</li>
                                <li>Bảo vệ 24h</li>
                                <li>CCTV bên ngoài</li>
                                <li>CCTV trong khu vực chung</li>
                                <li>Thiết bị báo cháy</li>
                                <li>Trạm an toàn/bảo mật</li>
                            </ul>
                        </div>

                        <!-- Cột 4 -->
                        <div class="col-md-3 mb-3">
                            <h6 class="fw-bold" style="color:#3399ff;">Trong phòng</h6>
                            <ul class="list-unstyled small">
                                <li>Bàn làm việc</li>
                                <li>Bình chữa cháy</li>
                                <li>Dép đi trong nhà</li>
                                <li>Điều hòa</li>
                                <li>Điện thoại</li>
                                <li>Đồ dùng nhà tắm</li>
                                <li>Khăn tắm</li>
                                <li>Máy sấy tóc</li>
                                <li>Nước uống đóng chai</li>
                                <li>Thảm</li>
                                <li>Tủ quần áo</li>
                                <li>Vòi sen</li>
                            </ul>

                            <h6 class="fw-bold mt-3" style="color:#3399ff;">Đi lại</h6>
                            <ul class="list-unstyled small">
                                <li>Bãi đỗ xe (miễn phí)</li>
                                <li>Ô tô cho thuê</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hotel-rules">
                <h3>Quy định của khách sản</h3>

                <div class="rule-item">
                    <h5>Giờ nhận – trả phòng</h5>
                    <p>Nhận phòng từ <b>14:00</b>, trả phòng trước <b>12:00</b>.</p>
                </div>

                <div class="rule-item">
                    <h5>Hủy đặt phòng</h5>
                    <p>Chính sách hủy và thanh toán trước tùy theo loại phòng.
                        Vui lòng kiểm tra chi tiết khi đặt phòng.</p>
                </div>

                <div class="rule-item">
                    <h5>Trẻ em và giường phụ</h5>
                    <p>Tất cả trẻ em đều được chào đón.</p>
                    <p>Giường phụ tùy thuộc vào loại phòng bạn chọn. Vui lòng liên hệ với chỗ nghỉ để biết thêm chi tiết.</p>
                </div>

                <div class="rule-item">
                    <h5>Thú cưng</h5>
                    <p>Không được phép mang theo thú cưng.</p>
                </div>

                <div class="rule-item">
                    <h5>Thanh toán</h5>
                    <p>Chỗ nghỉ chấp nhận thanh toán bằng tiền mặt và các loại thẻ tín dụng phổ biến.</p>
                </div>

                <div class="rule-item">
                    <h5>Quy định khác</h5>
                    <ul>
                        <li>Khi đặt trên 5 phòng, chính sách và điều khoản bổ sung có thể được áp dụng.</li>
                        <li>Khách cần xuất trình giấy tờ tùy thân có ảnh và thẻ tín dụng khi nhận phòng.</li>
                    </ul>
                </div>
            </div>
        </div>
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
                            @if($i <=floor($review->rating ?? 0))
                            <i class="icon_star"></i>
                            @elseif($i == ceil($review->rating ?? 0) && ($review->rating ?? 0) - floor($review->rating ?? 0) >= 0.5)
                            <i class="icon_star_half"></i>
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
        @if (!session()->has('user'))
        <div class="review-add mt-4">
            <h4><i class="fa fa-edit me-2"></i>Viết đánh giá</h4>
            <form method="POST" action="{{ route('client.p_review', ['id' => $room->id]) }}" class="ra-form">
                @csrf
                <div class="row">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h5 class="rating-title">Chọn kiểu đánh giá:</h5>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex justify-content-around gap-4">
                                <div class="form-check">
                                    <input style="position: absolute; margin-top: 2.3rem; margin-left: -6.25rem;" type="radio" name="rate" value="star" id="rate_star" checked>
                                    <label class="form-check-label" style="margin-top: 1.8rem;" for="rate_star">Đánh giá bằng sao</label>
                                </div>
                                <div class="form-check">
                                    <input style="position: absolute; margin-top: 2.3rem; margin-left: -6.25rem;" type="radio" name="rate" value="number" id="rate_number">
                                    <label class="form-check-label" style="margin-top: 1.8rem;" for="rate_number">Đánh giá bằng số</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div id="star-rating-wrapper" class="rating-input-section">
                            <h6>Đánh giá bằng sao:</h6>
                            <div id="star-rating" class="mb-3"></div>
                            <input type="hidden" id="rating-value" name="rating" value="">
                        </div>
                        <div id="number-rating-wrapper" class="rating-input-section d-none">
                            <h6>Đánh giá bằng số (1 - 5):</h6>
                            <div class="number-input-wrapper">
                                <input type="number" name="rating_number" min="1" max="5" step="0.1"
                                    class="form-control" placeholder="Nhập điểm từ 1-5">
                            </div>
                        </div>
                    </div>





                    <div class="col-lg-12 mt-3">
                        <textarea required placeholder="Nội dung đánh giá..." name="comment"
                            class="form-control" rows="4"></textarea>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <button type="submit" class="btn btn-primary" onclick="return validateReviewForm()">
                            <i class="fa fa-paper-plane me-2"></i>Gửi đánh giá
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
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
    // Khởi tạo form với dữ liệu từ session nếu có
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($bookingData['guest']) && $bookingData['guest'] == 'custom')
        const customWrapper = document.getElementById('customGuestWrapper');
        if (customWrapper) {
            customWrapper.style.display = 'block';
        }
        @endif

        // Cập nhật gợi ý số phòng khi thay đổi số khách
        const guestSelect = document.getElementById('guest');
        const roomSelect = document.getElementById('room');

        if (guestSelect && roomSelect) {
            guestSelect.addEventListener('change', function() {
                updateRoomSuggestion();
            });

            // Khởi tạo gợi ý ban đầu
            updateRoomSuggestion();
        }
    });

    function updateRoomSuggestion() {
        const guestSelect = document.getElementById('guest');
        const roomSelect = document.getElementById('room');
        const suggestionText = roomSelect.nextElementSibling;

        if (!guestSelect || !roomSelect || !suggestionText) return;

        const selectedGuest = guestSelect.value;
        let suggestion = '';

        switch (selectedGuest) {
            case '2l1n':
                suggestion = 'Với 2 lớn + 1 nhỏ, bạn nên chọn <strong>1-2 phòng</strong>';
                break;
            case '4l3n':
                suggestion = 'Với 4 lớn + 3 nhỏ, bạn nên chọn <strong>2-3 phòng</strong>';
                break;
            case '6l4n':
                suggestion = 'Với 6 lớn + 4 nhỏ, bạn nên chọn <strong>3-5 phòng</strong>';
                break;
            case 'custom':
                const customGuest = document.getElementById('customGuest');
                if (customGuest && customGuest.value) {
                    const guestCount = parseInt(customGuest.value);
                    const minRooms = Math.ceil(guestCount / 3);
                    const maxRooms = Math.ceil(guestCount / 2);
                    suggestion = `Với ${guestCount} người, bạn nên chọn <strong>${minRooms}-${maxRooms} phòng</strong>`;
                } else {
                    suggestion = 'Chọn số phòng phù hợp với số lượng khách của bạn';
                }
                break;
            default:
                suggestion = 'Chọn số phòng phù hợp với số lượng khách của bạn';
        }

        suggestionText.innerHTML = `<i class="fa fa-info-circle me-1"></i><strong>Gợi ý:</strong> ${suggestion}`;
    }
</script>


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
        const customWrapper = document.getElementById("customGuestWrapper");
        const customInput = document.getElementById("customGuest");

        if (select.value === "custom") {
            customWrapper.style.display = "block";
            customInput.required = true;
            customInput.focus();
            customInput.setAttribute('aria-describedby', 'customGuestHelp');
        } else {
            customWrapper.style.display = "none";
            customInput.required = false;
            customInput.value = "";
            customInput.removeAttribute('aria-describedby');
        }

        // Cập nhật gợi ý số phòng
        updateRoomSuggestion();
    }

    // Validate custom guest input
    function validateCustomGuest() {
        const customInput = document.getElementById("customGuest");
        const value = parseInt(customInput.value);

        if (value < 1 || value > 20) {
            customInput.setCustomValidity("Số người phải từ 1-20");
            return false;
        } else {
            customInput.setCustomValidity("");
            // Cập nhật gợi ý số phòng khi thay đổi số khách
            updateRoomSuggestion();
            return true;
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

        // Star rating được xử lý bởi Raty library trong main.blade.php

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

        // Form validation đã được xử lý bởi BookingCalendarManager
        // Không cần code validation cũ nữa

        // Initialize custom guest display
        toggleCustomGuest(document.getElementById('guest'));
    });

    // Validate review form
    function validateReviewForm() {
        const rateType = document.querySelector('input[name="rate"]:checked').value;

        if (rateType === 'star') {
            const ratingValue = document.getElementById('rating-value').value;
            if (!ratingValue || ratingValue === '0') {
                alert('Vui lòng chọn số sao đánh giá!');
                return false;
            }
        } else if (rateType === 'number') {
            const ratingNumber = document.querySelector('input[name="rating_number"]').value;
            if (!ratingNumber || ratingNumber < 1 || ratingNumber > 5) {
                alert('Vui lòng nhập điểm đánh giá từ 1-5!');
                return false;
            }
        }

        return true;
    }
</script>

<style>
    .hotel-rules {
        width: 100%;
        /* full chiều ngang */
        margin: 30px 0;
        padding: 25px;
        background: #fff;
        border: 1px solid #ddd;
        /* Đóng khung */
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        font-family: Arial, sans-serif;
        line-height: 1.6;
    }

    .hotel-rules h3 {
        color: #FFD966;
        /* Vàng nhạt */
        font-size: 1.1rem;
        /* Chữ nhỏ hơn */
        font-weight: bold;
        margin-bottom: 15px;
    }

    .rule-item {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #eee;
    }

    .rule-item h5 {
        color: #0d6efd;
        /* Xanh nước biển */
        font-weight: bold;
        margin-bottom: 6px;
        font-size: 1rem;
    }

    .rule-item p,
    .rule-item ul {
        color: #444;
        font-size: 0.9rem;
        margin: 4px 0;
    }

    .rule-item ul {
        padding-left: 18px;
    }

    .room-details-item {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
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