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
                        <a href="./home.html">{{ __("messages.Home") }}</a>
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
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="row g-0">
                            <div class="col-md-5">
                                <img src="{{ asset('storage/upload/' . $room->pimage) }}" class="img-fluid rounded-start h-100 w-100" style="object-fit: cover;" alt="{{ $room->name }}">
                            </div>
                            <div class="col-md-7 d-flex">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h3 class="card-title mb-0">{{ $room->name }}</h3>
                                            <a class="btn btn-primary" href="{{ route('dathang.form', ['id' => $room->id]) }}">
                                                <i class="bi bi-calendar-check me-1"></i> Đặt ngay
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center text-warning mb-3">
                                            <i class="bi bi-star-fill me-1"></i>
                                            <i class="bi bi-star-fill me-1"></i>
                                            <i class="bi bi-star-fill me-1"></i>
                                            <i class="bi bi-star-fill me-1"></i>
                                            <i class="bi bi-star-half"></i>
                                        </div>
                                        <h4 class="text-danger">
                                            {{ number_format($room->base_price, 0, ',', '.') }}
                                            <small class="text-muted">VND/Đêm</small>
                                        </h4>
                                        <div class="row mt-3">
                                            <div class="col-sm-6 mb-2">
                                                <small class="text-muted d-block">Kích thước</small>
                                                <span class="fw-semibold">{{ $room->room_area ?? '30 m²' }}</span>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <small class="text-muted d-block">Sức chứa</small>
                                                <span class="fw-semibold">{{ $room->max_guests ?? '5' }} khách</span>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <small class="text-muted d-block">Giường</small>
                                                <span class="fw-semibold">{{ $room->bed_count ?? '1' }} giường</span>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <small class="text-muted d-block">Khách sạn</small>
                                                <span class="fw-semibold">{{ $room->hotel->name ?? '—' }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <small class="text-muted d-block">Tiện ích</small>
                                            <div class="fw-semibold">{{ $room->amenities }}</div>
                                        </div>
                                        <div class="mt-3">
                                            <small class="text-muted d-block">Mô tả</small>
                                            <p class="mb-0">{{ $room->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="imglist">
                    <h4 class="mb-3">Ảnh</h4>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($imglist as $khoanh)
                        <img src="{{ asset('storage/upload/' . $khoanh->imgname) }}" alt="{{ $room->name }}" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                        @endforeach
                    </div>
                </div>
                <div class="rd-reviews">
                    <h4>Reviews</h4>
                    <div class="review-item">
                        <div class="ri-pic">
                            <img src="{{ asset(url("")) }}/img/room/avatar/avatar-1.jpg" alt="">
                        </div>
                        <div class="ri-text">
                            <span>27 Aug 2019</span>
                            <div class="rating">
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star-half_alt"></i>
                            </div>
                            <h5>Brandon Kelley</h5>
                            <p>Neque porro qui squam est, qui dolorem ipsum quia dolor sit amet, consectetur,
                                adipisci velit, sed quia non numquam eius modi tempora. incidunt ut labore et dolore
                                magnam.</p>
                        </div>
                    </div>
                    <div class="review-item">
                        <div class="ri-pic">
                            <img src="{{ asset(url("")) }}/img/room/avatar/avatar-2.jpg" alt="">
                        </div>
                        <div class="ri-text">
                            <span>27 Aug 2019</span>
                            <div class="rating">
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star-half_alt"></i>
                            </div>
                            <h5>Brandon Kelley</h5>
                            <p>Neque porro qui squam est, qui dolorem ipsum quia dolor sit amet, consectetur,
                                adipisci velit, sed quia non numquam eius modi tempora. incidunt ut labore et dolore
                                magnam.</p>
                        </div>
                    </div>
                </div>
                <div class="review-add">
                    <h4>Add Review</h4>
                    <form action="#" class="ra-form">
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="text" placeholder="Name*">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" placeholder="Email*">
                            </div>
                            <div class="col-lg-12">
                                <div>
                                    <h5>You Rating:</h5>
                                    <div class="rating">
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star-half_alt"></i>
                                    </div>
                                </div>
                                <textarea placeholder="Your Review"></textarea>
                                <button type="submit">Submit Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="room-booking">
                    <h3>Thông tin đặt phòng</h3>
                    <form action="#">
                        <div class="check-date">
                            <label for="date-in">Ngày đến:</label>
                            <input type="text" id="date-in">
                            <i class="icon_calendar"></i>
                        </div>
                        <div class="check-date">
                            <label for="date-out">Ngày đi:</label>
                            <input type="text" id="date-out">
                            <i class="icon_calendar"></i>
                        </div>
                        <div class="select-option">
                            <label for="guest">Số người:</label>
                            <select id="guest">
                                <option value="">3 Adults</option>
                            </select>
                        </div>
                        <div class="select-option">
                            <label for="room">Số phòng:</label>
                            <select id="room">
                                <option value="">1 Room</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Room Details Section End -->
@endsection