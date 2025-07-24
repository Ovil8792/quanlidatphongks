@extends('layout.main')
@section('main')
    <!-- Breadcrumb Section Begin -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>Chi tiết phòng</h2>
                        <div class="bt-option">
                            <a href="./home.html">{{ __('messages.Home') }}</a>
                            <span>{{ __('messages.Rooms') }}</span>
                        </div>
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
                        <img src="{{ asset(url('')) }}/storage/upload/{{ $room->pimage }}" alt="">
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
                                </div>
                            </div>
                            <h2>{{ number_format($room->base_price,0,",",".") }}<span>đ/Đêm</span></h2>
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="r-o">Size:</td>
                                        <td>30 ft</td>
                                    </tr>
                                    <tr>
                                        <td class="r-o">Capacity:</td>
                                        <td>Max persion 5</td>
                                    </tr>
                                    <tr>
                                        <td class="r-o">Bed:</td>
                                        <td>King Beds</td>
                                    </tr>
                                    <tr>
                                        <td class="r-o">Tiện ích:</td>
                                        <td>{{ $room->amenities }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="f-para">
                                {{ $room->description }}
                            </p>
                        </div>
                    </div>
                    <div class="imglist">
                        <h4>Ảnh</h4>
                        <div class="image-item">
                            @foreach ($imglist as $khoanh)
                                <img src="{{ asset(url('')) }}/storage/upload/{{ $khoanh->imgname }}" alt=""
                                    style="width: 100px; height: 100px; margin-right: 10px;">
                            @endforeach
                        </div>
                    </div>
                    <div class="rd-reviews">
                        <h4>Reviews</h4>
                        <div class="review-item">
                            <div class="ri-pic">
                                <img src="{{ asset(url('')) }}/img/room/avatar/avatar-1.jpg" alt="">
                            </div>
                            <div class="ri-text">
                                <span>27 Aug 2019</span>
                                <input type="hidden" name="rating" id="rating-value" value="0">
                                <div class="rating">
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star-half_alt"></i>
                                </div>
                                <a href="{{ route('dathang.form', ['id' => $room->id]) }}">Booking Now</a>
                            </div>
                        </div>
                        <h2>{{ $room->base_price}}<span>/Pernight</span></h2>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="r-o">Size:</td>
                                    <td>30 ft</td>
                                </tr>
                                <tr>
                                    <td class="r-o">Capacity:</td>
                                    <td>Max persion 5</td>
                                </tr>
                                <tr>
                                    <td class="r-o">Bed:</td>
                                    <td>King Beds</td>
                                </tr>
                                <tr>
                                    <td class="r-o">Tiện ích:</td>
                                    <td>{{ $room->amenities }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="f-para">
                            {{ $room->description }}
                        </p>
                    </div>
                </div>
                <div class="imglist">
                    <h4>Ảnh</h4>
                    <div class="image-item">
                        @foreach ($imglist as $khoanh)
                        <img src="{{ asset(url("")) }}/storage/upload/{{ $khoanh->imgname }}" alt="" style="width: 100px; height: 100px; margin-right: 10px;">

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



                    <div class="review-add">
                        <h4>Đánh giá</h4>
                        <form method="POST" action="{{ route('client.p_review', ['id' => $room->id]) }}" class=" ra-form">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4>Chọn kiểu đánh giá:</h4>
                                    <div class="rating-type">
                                        <input style="width:3%!important" type="radio" name="rate" value="star"
                                            checked><span id="changera"> Đánh giá bằng sao</span>

                                        <input style="width:3%!important" type="radio" name="rate" value="number">
                                        <span id="changera">Đánh giá bằng số</span>
                                    </div>
                                </div>
                                @php
                                    session()->put('user', 'test');
                                @endphp
                                @csrf
                                @if (session()->has('user') == false)
                                    <div class="col-lg-6">
                                        <input required type="text" placeholder="Tên*">
                                    </div>
                                    <div class="col-lg-6">
                                        <input required type="text" placeholder="Email*">
                                    </div>
                                @endif

                                <div class="col-lg-12">
                                    <div>
                                        <div class="col-lg-12" id="star-rating-wrapper">
                                            <h5>Đánh giá bằng sao:</h5>
                                            
                                            <div id="star-rating"></div>
                                        </div>
                                        <div class="col-lg-12 d-flex hidden-important" id="number-rating-wrapper">
                                            <h5>Đánh giá bằng số (1 - 5):</h5>
                                            <input style="width:10%!important;margin-bottom:0" type="number" name="rating_number" min="1" max="5"
                                                step="0.1" class="form-control">
                                        </div>
                                        <textarea required placeholder="Nội dung..." name="comment"></textarea>
                                        <button type="submit">Gửi</button>
                                    </div>



                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="room-booking">
                    <h3>Thông tin đặt phòng</h3>
                    <form action="#">
                        <div class="check-date">
                            <label for="date-in">Ngày nhận phòng:</label>
                            <input type="text" class="date-input" id="date-in">
                            <i class="icon_calendar"></i>
                        </div>
                        <div class="check-date">
                            <label for="date-out">Ngày trả phòng:</label>
                            <input type="text" class="date-input" id="date-out">
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
                        <button type="submit">Đặt phòng</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- Room Details Section End -->
@endsection
