@extends('layout.main')
@section('main')
<style>
    #customGuest{
        display: none; 
        margin-top: 10px; 
        height: 40px; 
        width: 100%;
    }
</style>
    @if (session('error'))
        <div class="alert alert-success">
            {{ session('error') }}
        </div>
    @endif
    <p style="display:none;" id="current">home</p>
    <!-- Hero Section Begin -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="hero-text">
                        <h1>River New - Dịch vụ đặt phòng</h1>
                        <p>Đặt phòng nhanh chóng tiện lợi, nhận nhiều ưu đãi hấp dẫn</p>
                        <!-- <a href="#" class="primary-btn">Discover Now</a> -->
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 offset-xl-2 offset-lg-1">
                    <div class="booking-form">
                        <h3>{{ __('messages.booknow') }}</h3>
                        <form action="{{ route('search.pending') }}" method="GET">
                            <div class="check-date">
                                <label for="date-in">{{ __('messages.cin') }}:</label>
                                <input type="text" class="date-input" id="date-in" name="date_in">
                                <i class="icon_calendar"></i>
                            </div>
                            <div class="check-date">
                                <label for="date-out">{{ __('messages.cout') }}:</label>
                                <input type="text" class="date-input" id="date-out" name="date_out">
                                <i class="icon_calendar"></i>
                            </div>
                            <div class="select-option">
                                <label for="guest">{{ __('messages.guest') }}:</label>
                                <select  onchange="tgg(this)" id="guest" name="guest">
                                    <option value="2l1n">2 lớn+1 nhỏ</option>
                                    <option value="4l3n">4 lớn 3 nhỏ</option>
                                    <option value="custom">Nhập riêng</option>
                                </select>
                                <input class="form-control" type="text" id="customGuest" name="custom_guest" placeholder="Nhập số người"
                                    min="1"/>
                            </div>
                            <div class="select-option">
                                <label for="room">{{ __('messages.rn') }}:</label>
                                <select id="room" name="room">
                                    <option value="1">1 Phòng</option>
                                    <option value="2">2 Phòng</option>
                                    <option value="3">3 Phòng</option>
                                    <option value="4">4 Phòng</option>
                                    <option value="10">10 Phòng</option>
                                </select>
                            </div>
                            <div class="select-option">
                                <label for="search">Vị trí:</label>
                                <div style="position: relative;">
                                    <input id="search" name="keyword"
                                        style="border-radius: 2px; border: 1px solid #ebebeb; height: 50px; line-height: 50px; outline: none; padding-left: 20px; width: 100%; float: none;"
                                        type="search" class="form-control" placeholder="Tìm kiếm...">
                                    <input type="hidden" name="selected_id" id="selectedId">

                                    <ul id="suggestions"
                                        style="list-style: none; margin: 0; padding: 0; border: 1px solid #ebebeb; border-top: none; max-height: 200px; overflow-y: auto; width: 100%; position: absolute; top: 100%; left: 0; background: #fff; z-index: 1000; display: none;">
                                        <!-- Gợi ý sẽ được chèn vào đây -->
                                    </ul>
                                </div>
                                <!-- <button type="submit"  class="input-group-text" id="visible-addon"><i class="fas fa-search"></i></button> -->
                            </div>
                            <button type="submit">{{ __('messages.checkavai') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-slider owl-carousel">
            <div class="hs-item set-bg" data-setbg="img/hero/hero-1.jpg"></div>
            <div class="hs-item set-bg" data-setbg="img/hero/hero-2.jpg"></div>
            <div class="hs-item set-bg" data-setbg="img/hero/hero-3.jpg"></div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- About Us Section Begin -->
    <!-- <section class="aboutus-section spad">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="about-text">
                                    <div class="section-title">
                                        <span>About Us</span>
                                        <h2>Intercontinental LA <br />Westlake Hotel</h2>
                                    </div>
                                    <p class="f-para">Sona.com is a leading online accommodation site. We’re passionate about
                                        travel. Every day, we inspire and reach millions of travelers across 90 local websites in 41
                                        languages.</p>
                                    <p class="s-para">So when it comes to booking the perfect hotel, vacation rental, resort,
                                        apartment, guest house, or tree house, we’ve got you covered.</p>
                                    <a href="#" class="primary-btn about-btn">Read More</a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="about-pic">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <img src="img/about/about-1.jpg" alt="">
                                        </div>
                                        <div class="col-sm-6">
                                            <img src="img/about/about-2.jpg" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> -->
    <!-- About Us Section End -->

    <!-- Services Section End -->
    <!-- <section class="services-section spad">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="section-title">
                                    <span>What We Do</span>
                                    <h2>Discover Our Services</h2>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-6">
                                <div class="service-item">
                                    <i class="flaticon-036-parking"></i>
                                    <h4>Travel Plan</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                        labore et dolore magna.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="service-item">
                                    <i class="flaticon-033-dinner"></i>
                                    <h4>Catering Service</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                        labore et dolore magna.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="service-item">
                                    <i class="flaticon-026-bed"></i>
                                    <h4>Babysitting</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                        labore et dolore magna.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="service-item">
                                    <i class="flaticon-024-towel"></i>
                                    <h4>Laundry</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                        labore et dolore magna.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="service-item">
                                    <i class="flaticon-044-clock-1"></i>
                                    <h4>Hire Driver</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                        labore et dolore magna.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="service-item">
                                    <i class="flaticon-012-cocktail"></i>
                                    <h4>Bar & Drink</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                        labore et dolore magna.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> -->
    <!-- Services Section End -->
    <br>
   
<section class="voucher-section py-5 bg-light">
    <div class="container">
        <div class="row mb-4 text-center">
            <div class="col-lg-12">
                <div class="section-title">
                    <span>Ưu đãi & Giảm giá</span>
                    <h2>Những gói quà và mã ưu đãi chất lượng dành cho bạn</h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Voucher 1 --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-bold">Giảm 20% cho đơn từ 1.000.000đ</h5>
                            <p class="card-text text-muted mb-2">HSD: 31/07/2025</p>
                            <p class="text-muted small">Áp dụng cho khách hàng mới. Không áp dụng chung với khuyến mãi khác.</p>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark px-3 py-2 border rounded-pill" id="code1">WELCOME20</span>
                            <button onclick="copyVoucher('WELCOME20')" class="btn btn-sm btn-outline-primary">Copy</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Voucher 2 --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-bold">Giảm 10% khi thanh toán qua MOMO</h5>
                            <p class="card-text text-muted mb-2">HSD: 15/08/2025</p>
                            <p class="text-muted small">Áp dụng mọi đơn hàng, không giới hạn giá trị tối thiểu.</p>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark px-3 py-2 border rounded-pill" id="code2">MOMO10</span>
                            <button onclick="copyVoucher('MOMO10')" class="btn btn-sm btn-outline-primary">Copy</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Voucher 3 --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-bold">Tặng 50k cho lần đặt phòng đầu tiên</h5>
                            <p class="card-text text-muted mb-2">HSD: 01/09/2025</p>
                            <p class="text-muted small">Chỉ áp dụng cho tài khoản đăng ký mới trong vòng 7 ngày.</p>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark px-3 py-2 border rounded-pill" id="code3">FIRST50</span>
                            <button onclick="copyVoucher('FIRST50')" class="btn btn-sm btn-outline-primary">Copy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- Home Room Section Begin -->
    <section class="hp-room-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Phòng chất lượng</span>
                        <h2>Các căn phòng được đặt nhiều nhất</h2>
                    </div>
                </div>
            </div>
            <div class="hp-room-items">
                <div class="row">
                    @foreach ($room as $k)
                        <div class="col-lg-3 col-md-6">
                            <div class="hp-room-item set-bg"
                                data-setbg="{{ asset(url('')) . "/storage/upload/$k->pimage" }}">
                                <div class="hr-text">
                                    <h3 class="text-readable">{{ $k->name }}</h3>
                                    <h2 class="text-readable">{{ $k->base_price }}VNĐ<span class="text-readable">/Pernight</span></h2>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="r-o text-readable">Size:</td>
                                                <td class="text-readable">30 ft</td>
                                            </tr>
                                            <tr>
                                                <td class="text-readable r-o">Capacity:</td>
                                                <td class="text-readable">Max persion 5</td>
                                            </tr>
                                            <tr>
                                                <td class="r-o text-readable">Bed:</td>
                                                <td class="text-readable">King Beds</td>
                                            </tr>
                                            <tr>
                                                <td class="r-o text-readable">Services:</td>
                                                <td class="text-readable">Wifi, Television, Bathroom,...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <a style="  text-decoration: underline;" class="text-readable"
                                        href="{{ route('client.roomdetail', ['id' => $k->id]) }}"
                                        class="primary-btn">More
                                        Details</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Home Room Section End -->

    <!-- Testimonial Section Begin -->
    <!-- <section class="testimonial-section spad">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="section-title">
                                    <span>Testimonials</span>
                                    <h2>What Customers Say?</h2>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 offset-lg-2">
                                <div class="testimonial-slider owl-carousel">
                                    <div class="ts-item">
                                        <p>After a construction project took longer than expected, my husband, my daughter and I
                                            needed a place to stay for a few nights. As a Chicago resident, we know a lot about our
                                            city, neighborhood and the types of housing options available and absolutely love our
                                            vacation at Sona Hotel.</p>
                                        <div class="ti-author">
                                            <div class="rating">
                                                <i class="icon_star"></i>
                                                <i class="icon_star"></i>
                                                <i class="icon_star"></i>
                                                <i class="icon_star"></i>
                                                <i class="icon_star-half_alt"></i>
                                            </div>
                                            <h5> - Alexander Vasquez</h5>
                                        </div>
                                        <img src="img/testimonial-logo.png" alt="">
                                    </div>
                                    <div class="ts-item">
                                        <p>After a construction project took longer than expected, my husband, my daughter and I
                                            needed a place to stay for a few nights. As a Chicago resident, we know a lot about our
                                            city, neighborhood and the types of housing options available and absolutely love our
                                            vacation at Sona Hotel.</p>
                                        <div class="ti-author">
                                            <div class="rating">
                                                <i class="icon_star"></i>
                                                <i class="icon_star"></i>
                                                <i class="icon_star"></i>
                                                <i class="icon_star"></i>
                                                <i class="icon_star-half_alt"></i>
                                            </div>
                                            <h5> - Alexander Vasquez</h5>
                                        </div>
                                        <img src="img/testimonial-logo.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> -->
    <!-- Testimonial Section End -->

    <!-- Blog Section Begin -->
    <section class="blog-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Tin tức khách sạn</span>
                        <h2>Tin tức mới nhất về các khách sạn của chúng tôi</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-1.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Travel Trip</span>
                            <h4><a href="#">Tremblant In Canada</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 15th April, 2019</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-2.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Camping</span>
                            <h4><a href="#">Choosing A Static Caravan</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 15th April, 2019</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-3.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Event</span>
                            <h4><a href="#">Copper Canyon</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 21th April, 2019</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="blog-item small-size set-bg" data-setbg="img/blog/blog-wide.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Event</span>
                            <h4><a href="#">Trip To Iqaluit In Nunavut A Canadian Arctic City</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 08th April, 2019</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item small-size set-bg" data-setbg="img/blog/blog-10.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Travel</span>
                            <h4><a href="#">Traveling To Barcelona</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 12th April, 2019</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->

<section class="top-hotel-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <span>Khách sạn hàng đầu</span>
                    <h2>Những khách sạn được săn đón nhất</h2>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-1.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Travel Trip</span>
                            <h4><a href="#">Tremblant In Canada</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 15th April, 2019</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-2.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Camping</span>
                            <h4><a href="#">Choosing A Static Caravan</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 15th April, 2019</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-3.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Event</span>
                            <h4><a href="#">Copper Canyon</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 21th April, 2019</div>
                        </div>
                    </div>
                </div>
                
        </div>
        <div class="row">
            <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-1.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Travel Trip</span>
                            <h4><a href="#">Tremblant In Canada</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 15th April, 2019</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-2.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Camping</span>
                            <h4><a href="#">Choosing A Static Caravan</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 15th April, 2019</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-3.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Event</span>
                            <h4><a href="#">Copper Canyon</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 21th April, 2019</div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-1.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Travel Trip</span>
                            <h4><a href="#">Tremblant In Canada</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 15th April, 2019</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-2.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Camping</span>
                            <h4><a href="#">Choosing A Static Caravan</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 15th April, 2019</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-3.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Event</span>
                            <h4><a href="#">Copper Canyon</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 21th April, 2019</div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
            const customInput = document.getElementById("customGuest");
    // console.log(customInput);
function tgg(select){

   
        if (select.value == "custom") {
            customInput.style.display = "block";
            customInput.required = true;
        } else {
            customInput.style.display = "none";
            customInput.required = false;
        }
    
}

        document.addEventListener("DOMContentLoaded", () => {
 


            const searchInput = document.getElementById('search');
            const suggestionsBox = document.getElementById('suggestions');
            const selectedId = document.getElementById('selectedId');
            let timeout;

            document.addEventListener('click', (e) => {
                if (!suggestionsBox.contains(e.target) && e.target !== searchInput) {
                    suggestionsBox.style.display = 'none';
                }
            });

            searchInput.addEventListener('input', () => {
                clearTimeout(timeout);
                const query = searchInput.value.trim();

                if (query.length < 1) {
                    suggestionsBox.innerHTML = '';
                    suggestionsBox.style.display = 'none';
                    return;
                }

                timeout = setTimeout(() => {
                    fetch(`/sapi?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                suggestionsBox.innerHTML = '';

                const { locations, rooms } = data;

                if (locations.length === 0 && rooms.length === 0) {
                    suggestionsBox.style.display = 'none';
                    return;
                }

                if (locations.length > 0) {
                    const locHeader = document.createElement('li');
                    locHeader.textContent = 'Vị trí gợi ý';
                    locHeader.style.fontWeight = 'bold';
                    locHeader.style.padding = '8px';
                    suggestionsBox.appendChild(locHeader);

                    locations.forEach(loc => {
                        const li = document.createElement('li');
                        li.textContent = loc;
                        li.style.padding = '8px';
                        li.style.cursor = 'pointer';
                        li.style.borderBottom = '1px solid #eee';
                        li.addEventListener('click', () => {
                            searchInput.value = loc;
                            suggestionsBox.innerHTML = '';
                            suggestionsBox.style.display = 'none';
                        });
                        suggestionsBox.appendChild(li);
                    });
                }

                if (rooms.length > 0) {
                    const roomHeader = document.createElement('li');
                    roomHeader.textContent = 'Phòng gợi ý';
                    roomHeader.style.fontWeight = 'bold';
                    roomHeader.style.padding = '8px';
                    suggestionsBox.appendChild(roomHeader);

                    rooms.forEach(room => {
                        const li = document.createElement('li');
                        li.textContent = room.name;
                        li.style.padding = '8px';
                        li.style.cursor = 'pointer';
                        li.style.borderBottom = '1px solid #eee';
                        li.addEventListener('click', () => {
                            searchInput.value = room.name;
                            selectedId.value = room.id; // nếu muốn dùng
                            suggestionsBox.innerHTML = '';
                            suggestionsBox.style.display = 'none';
                        });
                        suggestionsBox.appendChild(li);
                    });
                }

                suggestionsBox.style.display = 'block';
            });
    }, 300);
});
        });
    </script>
@endsection
