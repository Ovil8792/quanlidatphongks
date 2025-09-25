<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Sona Template">
    <meta name="keywords" content="Sona, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>River New</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,600,700&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/starability/starability-minified/starability-all.min.css" type="text/css">
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/flaticon.css" type="text/css">
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/style.css" type="text/css">
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/flatpickr.min.css" type="text/css">
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/booking-calendar.css" type="text/css">
    <link rel="stylesheet" href="{{ asset(url("")) }}/css/room-detail.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />
<script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/raty/3.1.1/jquery.raty.min.css">
<style>
.hidden-important {
    display: none !important;
    
}
/* Avatar tròn trong header */
.header-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #f3f4f6;
}
</style>
<script>
window.addEventListener("load", function(){
  window.cookieconsent.initialise({
    palette: {
      popup: { background: "#000" },
      button: { background: "#f1d600" }
    },
    theme: "classic",
    position: "bottom-right",
    content: {
      message: "Chúng tôi sử dụng cookie để cải thiện trải nghiệm người dùng.",
      dismiss: "Đã hiểu",
      link: "Tìm hiểu thêm",
      href: "{{ route('client.privacy-policy') }}" // hoặc link đến trang chính sách của bạn
    }
  })
});
</script>
<style>
    .text-readable {
text-shadow:
        -1px -1px 2px rgba(0, 0, 0, 0.8),
         1px -1px 2px rgba(0, 0, 0, 0.8),
        -1px  1px 2px rgba(0, 0, 0, 0.8),
         1px  1px 2px rgba(0, 0, 0, 0.8),
         0px  0px 4px rgba(0, 0, 0, 0.6);
    font-weight: bold;
}
/* Popup overlay */
#login-overlay {
    display: none;
    position: fixed;
    z-index: 9998;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
}

/* Popup form */
#lfo {
    display: none;
    position: fixed;
    z-index: 9999;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    padding: 32px 24px 24px 24px;
    min-width: 320px;
    max-width: 95vw;
}
#lfo .close-login {
    position: absolute;
    top: 8px; right: 16px;
    font-size: 22px;
    color: #888;
    cursor: pointer;
    background: none;
    border: none;
}
#login-title{
    text-align: center;
    margin: 20px 0px 40px 0px;
    color: #333;
}
</style>
</head>
{{-- {{ dd(Auth::user()) }} --}}
<body id="bd">

<!-- login -->
    <div id="login-overlay"></div>
<div id="lfo" style="display:none;">
    <button class="close-login" aria-label="Đóng">&times;</button>
<div class="dangnhap">
    <h2 id="login-title">Đăng nhập</h2>
</div>
<form style="margin-top: 20px;" action="{{ route('postLogin') }}" method="POST">
    @csrf
    @if ($errors->has('wrong'))
        <div class="alert alert-danger" role="alert">
            {{ $errors->first('wrong') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    <div class="input-group form-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
        </div>
        <input type="text" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
        @if ($errors->has('email'))
            <span class="text-danger">{{ $errors->first('email') }}</span>
        @endif
    </div>
    <div class="input-group form-group" style="margin-bottom: 20px;">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-key"></i></span>
        </div>
        <input type="password" class="form-control" placeholder="Mật khẩu" name="password">
        @if ($errors->has('password'))
            <span class="text-danger">{{ $errors->first('password') }}</span>
        @endif
    </div>
    <div class="form-group d-flex justify-content-center">
        <input type="submit" value="Đăng nhập" class="btn login_btn" style="background-color: #dfa974; color: white;">
    </div>
</form>
<div class="d-flex justify-content-center links mt-auto">
    Không có tài khoản?<a href="#" id="show-register-link">Đăng kí</a>
</div>
</div>

<!-- register -->
 <div id="register-overlay" style="display:none;position:fixed;z-index:9998;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);"></div>
<div id="register-popup" style="display:none;position:fixed;z-index:9999;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:10px;box-shadow:0 8px 32px rgba(0,0,0,0.2);padding:32px 24px 24px 24px;min-width:320px;max-width:95vw;">
    <button class="close-register" aria-label="Đóng" style="position:absolute;top:8px;right:16px;font-size:22px;color:#888;cursor:pointer;background:none;border:none;">&times;</button>
    <div class="dangky">
        <h2 id="register-title" style="text-align:center;margin:20px 0 40px 0;color:#333;">Đăng ký</h2>
    </div>
    <form action="{{ route('postRegister') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($errors->has('register_error'))
            <div class="alert alert-danger" role="alert" style="margin-bottom:12px;">
                {{ $errors->first('register_error') }}
            </div>
        @endif
        <div class="input-group form-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Tên" name="name" value="{{ old('name') }}" required>
        </div>
        @error('name')
            <div class="text-danger" style="margin-top:-8px; margin-bottom:8px;">{{ $message }}</div>
        @enderror
        <div class="input-group form-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
            <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>
        </div>
        @error('email')
            <div class="text-danger" style="margin-top:-8px; margin-bottom:8px;">{{ $message }}</div>
        @enderror
        <div class="input-group form-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Số điện thoại" name="phone" value="{{ old('phone') }}">
        </div>
        @error('phone')
            <div class="text-danger" style="margin-top:-8px; margin-bottom:8px;">{{ $message }}</div>
        @enderror
        <div class="input-group form-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Địa chỉ" name="address" value="{{ old('address') }}">
        </div>
        @error('address')
            <div class="text-danger" style="margin-top:-8px; margin-bottom:8px;">{{ $message }}</div>
        @enderror
        <div class="input-group form-group" style="margin-bottom: 20px;">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-key"></i></span>
            </div>
            <input type="password" class="form-control" placeholder="Mật khẩu" name="password" required>
        </div>
        @error('password')
            <div class="text-danger" style="margin-top:-8px; margin-bottom:8px;">{{ $message }}</div>
        @enderror
        <div class="input-group form-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-image"></i></span>
            </div>
            <input type="file" class="form-control" name="avatar" accept="image/*">
        </div>
        @error('avatar')
            <div class="text-danger" style="margin-top:-8px; margin-bottom:8px;">{{ $message }}</div>
        @enderror
        <div class="form-group d-flex justify-content-center">
            <input type="submit" value="Đăng ký" class="btn login_btn" style="background-color: #dfa974; color: white;">
        </div>
    </form>
    <div class="d-flex justify-content-center links mt-auto">
        Đã có tài khoản? <a href="#" id="show-login-link" style="margin-left:5px;">Đăng nhập</a>
    </div>
</div>

    {{-- {{ dd(session("active")) }} --}}
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Section Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="canvas-open">
        <i class="icon_menu"></i>
    </div>
    <div class="offcanvas-menu-wrapper">
        <div class="canvas-close">
            <i class="icon_close"></i>
        </div>
        <!-- <div class="search-icon  search-switch">
            <i class="icon_search"></i>
        </div> -->
        <div class="header-configure-area">
            @if (session("locale") == "vi")
                <div class="language-option">
                    <img src="{{ asset(url("")) }}/img/vnflag.png" alt="">
                    <span>VI<i class="fa fa-angle-down"></i></span>
                    <div class="flag-dropdown">
                        <ul>
                            <li><a href="/lang/vi">Vi</a></li>
                            <li><a href="/lang/en">En</a></li>
                        </ul>
                    </div>
                </div>
            @elseif (session("locale") == "en")
                <div class="language-option">
                    <img src="{{ asset(url("")) }}/img/flag.jpg" alt="">
                    <span>EN<i class="fa fa-angle-down"></i></span>
                    <div class="flag-dropdown">
                        <ul>
                            <li><a href="/lang/vi">Vi</a></li>
                            <li><a href="/lang/en">En</a></li>
                        </ul>
                    </div>

                </div>
            @endif
            <a href="#" class="bk-btn">Book Now!</a>
        </div>
        <nav class="mainmenu mobile-menu">
            <ul>
                <li class="linkcheck" id="home"><a href="#"> {{ __("messages.Home") }} </a></li>
                <li class="linkcheck" id="room"><a href="#">{{ __("messages.Rooms") }}</a></li>
                <li class="linkcheck" id="about"><a href="#">{{ __("messages.AboutUs") }}</a></li>
                <li class="linkcheck" id="other"><a href="#">{{ __("messages.Pages") }}</a>
                    <ul class="dropdown">
                        <!-- <li><a href="#">Room Details</a></li> -->
                        <li><a href="#">Phòng Thượng Hạng</a></li>
                        <li><a href="#">Phòng gia đình</a></li>
                        <li><a href="#">Phòng Tổng Thống</a></li>
                    </ul>
                </li>
                <li class="linkcheck" id="contact"><a href="">Contact</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>
        <div class="top-social">
            <a href="https://www.facebook.com/tran.duy.768495/"><i class="fa-brands fa-facebook"></i></a>
            <a href="#"><i class="fa-brands fa-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-youtube"></i></a>
        </div>
        <ul class="top-widget">
            <li><i class="fa fa-phone"></i> 0865091023</li>
            <li><i class="fa fa-envelope"></i> tamuon00@gmail.com</li>
        </ul>
    </div>
    <!-- Offcanvas Menu Section End -->
    {{-- {{ App::getLocale() }} --}}
    <!-- Header Section Begin -->
    <header class="header-section">
        <div class="top-nav">
            <div class=" d-flex justify-content-around" style="width:100%;margin: auto!important;padding:0px 80px">
                <div class="row" style="width:85%;margin: auto!important;">
                    <div class="col-lg-6">
                        <ul class="tn-left">
                            <li><i class="fa fa-phone"></i> 0865091023</li>
                            <li><i class="fa fa-envelope"></i> tamuon00@gmail.com</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <div class="tn-right me-3">
                            <div class="top-social">
                                <a target="_blank" href="https://www.facebook.com/tran.duy.768495/"><i class="fa-brands fa-facebook"></i></a>
                                <a target="_blank" href="https://x.com/DuyProMax1925"><i class="fa-brands fa-x-twitter"></i></a>
                                <a target="_blank" href="#"><i class="fa-brands fa-instagram"></i></a>
                                <a target="_blank" href="https://www.youtube.com/@KrackMarcV"><i class="fa-brands fa-youtube"></i></a>
                            </div>
                            <a href="#" class="bk-btn">Booking Now</a>
                            @if (session("locale") == "vi" || session("locale") == null || session("locale") == "")
                                <div class="language-option">
                                    <img src="{{ asset(url("")) }}/img/vnflag.png" alt="">
                                    <span>VI<i class="fa fa-angle-down"></i></span>
                                    <div class="flag-dropdown">
                                        <ul>
                                            <li><a href="/lang/vi">Vi</a></li>
                                            <li><a href="/lang/en">En</a></li>
                                        </ul>
                                    </div>
                                </div>
                            @elseif (session("locale") == "en")
                                <div class="language-option">
                                    <img src="{{ asset(url("")) }}/img/flag.jpg" alt="">
                                    <span>EN<i class="fa fa-angle-down"></i></span>
                                    <div class="flag-dropdown">
                                        <ul>
                                            <li><a href="/lang/vi">Vi</a></li>
                                            <li><a href="/lang/en">En</a></li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            </div>
                            
                            {{-- <a href="{{ route('logout') }}"></a> --}}
                    </div>
                    
                </div>
@if(empty(Auth::user())||Auth::user() == [])
<div class="user-dropdown" style="padding:5px;margin:auto; width: 15%; text-align: center; position: relative;">
<a href="#" id="login-p"><img src="{{ asset(url('')) }}/img/avt.svg" alt="Avatar" id="avatarToggle" class="header-avatar" style="cursor: pointer;"></a>
</div>
@else
                <div class="user-dropdown" style="padding:5px;margin:auto; width: 15%; text-align: center; position: relative;">
                   {{-- điều kiện nếu người dùng không có avatar --}}
                    @if(empty(Auth::user()->avatar) || Auth::user()->avatar==="" )
    <img src="{{ asset(url('')) }}/img/avt.svg" alt="Avatar" id="avatarToggle" class="header-avatar" style="cursor: pointer;">
   {{-- Điều kiện nếu có avatar --}}
    @else
        <img src="{{ asset(url("")) }}/upload/{{ Auth::user()->avatar }}" alt="Avatar" id="avatarToggle" class="header-avatar" style="cursor: pointer;">
    @endif
    <div class="user-menu" id="userMenu" style="
        display: none;
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        min-width: 120px;
        z-index: 1000;
    ">
        <ul style="list-style: none; margin: 0; padding: 10px 0;">
            <li><a href="{{ route('client.show') }}" class="dropdown-item">Thông tin</a></li>
            <li><a href="{{ route('logout') }}" class="dropdown-item">Đăng xuất</a></li>
        </ul>
    </div>
</div>
@endif
            </div>
        </div>
        <div class="menu-item">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="logo">
                            <a href="{{ route("client.index") }}">
                                <img width="50%" src="{{ asset(url("")) }}/img/logo.png" alt="">
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-lg-10">
                        <div class="nav-menu">
                            <nav class="mainmenu">
                                <ul>
                                    <li class="linkcheck" id="home"><a href="{{ route("client.index") }}">{{ __("messages.Home") }}</a></li>
                                    <li class="linkcheck" id="room"><a href="{{ route("client.rooms") }}"> {{ __("messages.Rooms") }} </a>
                                    <ul class="dropdown">
                                            @foreach (session("category") as $k)
                                            <li><a href="{{ route("client.roomlist",["id"=>$k->id]) }}">{{ $k->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li class="linkcheck" id="about"><a href="{{ route("client.about") }}">{{ __("messages.AboutUs") }}</a></li>
                                    <li class="linkcheck" id="contact"><a href="{{ route("client.contact") }}">{{ __("messages.Contact") }}</a></li>
                                    <li class="linkcheck" id="other"><a href="#">{{ __("messages.Pages") }}</a></li>
                                </ul>
                            </nav>
                            <!-- <div class="nav-right search-switch">
                                <i class="icon_search"></i>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->



    @yield("main")





    <!-- Footer Section Begin -->
    <footer class="footer-section" style="margin-top:10px">
        <div class="container">
            <div class="footer-text">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="ft-about">
                            <div class="logo">
                                <a href="#">
                                    <img width="60%" src="{{ asset(url("")) }}/img/footer-logo.png" alt="">
                                </a>
                            </div>
                            <p>Chúng tôi truyền cảm hứng và tiếp cận hàng triệu <br />du khách bằng những dịch vụ chăm sóc khách hàng
                            <br /> và những trải nghiệm tuyệt vời nhất.</p>
                            <p>River New cung cấp các dịch vụ khách sạn, nhà hàng và
                                du lịch hàng đầu, mang đến cho bạn những trải nghiệm đáng nhớ.</p>
                            <p>Chúng tôi cam kết mang đến cho bạn những dịch vụ tốt nhất,
                                từ việc đặt phòng khách sạn đến các tour du lịch độc đáo.</p>
                            </p>
                            <div class="fa-social">
                                <a href="https://www.facebook.com/tran.duy.768495/"><i class="fa-brands fa-facebook"></i></a>
                                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 offset-lg-1">
                        <div class="ft-contact">
                            <h6>Liên hệ với chúng tôi</h6>
                            <ul>
                                <li>SĐT: 0865091023</li>
                                <li>Email: tamuon00@gmail.com</li>
                                <li>Địa chỉ: 127 Lê Thánh Tông, Máy Chai, Ngô Quyền, Hải Phòng</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 offset-lg-1">
                        <div class="ft-newslatter">
                            <h6>Nhận tin mới</h6>
                            <p>Hãy để chúng tôi cập nhật tin tức và ưu đãi mới nhất cho bạn.</p>
                            <form action="#" class="fn-form">
                                <input type="text" placeholder="Email của bạn">
                                <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-option">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <ul>
                            <li><a href="{{ route("client.contact") }}">Liên hệ</a></li>
                            <li><a href="#">Terms of use</a></li>
                            <li><a href="{{ route("client.privacy-policy") }}">Chính sách</a></li>
                            <li><a href="#">Environmental Policy</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-5">
                        <div class="co-text">
                            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                Bản quyền &copy;
                                <script>document.write(new Date().getFullYear());</script> thuộc về River New <i class="fa fa-heart" aria-hidden="true"></i> và <a
                                    href="https://colorlib.com" target="_blank">Colorlib</a>
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Search model Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch"><i class="icon_close"></i></div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search model end -->

    <!-- Js Plugins -->
    <script>
//form login 
// Toggle login popup
function showLoginPopup(show = true) {
    const overlay = document.getElementById('login-overlay');
    const lfo = document.getElementById('lfo');
    if (show) {
        overlay.style.display = 'block';
        lfo.style.display = 'block';
        document.body.style.overflow = 'hidden';
    } else {
        overlay.style.display = 'none';
        lfo.style.display = 'none';
        document.body.style.overflow = '';
    }
}
document.getElementById("login-p")?.addEventListener("click", function(e){
    e.preventDefault();
    showLoginPopup(true);
});

// Đóng popup khi bấm overlay hoặc nút đóng
document.getElementById('login-overlay').addEventListener('click', function() {
    showLoginPopup(false);
});
document.querySelector('#lfo .close-login').addEventListener('click', function() {
    showLoginPopup(false);
});

// Đóng popup khi bấm ESC
document.addEventListener('keydown', function(e){
    if(e.key === "Escape") showLoginPopup(false);
});

//register
function showRegisterPopup(show = true) {
    const overlay = document.getElementById('register-overlay');
    const popup = document.getElementById('register-popup');
    if (show) {
        overlay.style.display = 'block';
        popup.style.display = 'block';
        document.body.style.overflow = 'hidden';
    } else {
        overlay.style.display = 'none';
        popup.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Khi bấm "Đăng kí" trong popup đăng nhập
document.getElementById('show-register-link')?.addEventListener('click', function(e){
    e.preventDefault();
    showLoginPopup(false);
    showRegisterPopup(true);
});

// Khi bấm "Đăng nhập" trong popup đăng ký
document.getElementById('show-login-link')?.addEventListener('click', function(e){
    e.preventDefault();
    showRegisterPopup(false);
    showLoginPopup(true);
});

// Đóng popup đăng ký khi bấm overlay hoặc nút đóng
document.getElementById('register-overlay').addEventListener('click', function() {
    showRegisterPopup(false);
});
document.querySelector('#register-popup .close-register').addEventListener('click', function() {
    showRegisterPopup(false);
});

// Đóng popup đăng ký khi bấm ESC
document.addEventListener('keydown', function(e){
    if(e.key === "Escape") {
        showRegisterPopup(false);
        showLoginPopup(false);
    }
});

// Tự động mở popup theo session flag (auth_modal)
document.addEventListener('DOMContentLoaded', function(){
    try {
        const modalFlag = @json(session('auth_modal'));
        if (modalFlag === 'login') {
            showLoginPopup(true);
        } else if (modalFlag === 'register') {
            showRegisterPopup(true);
        }
    } catch (e) {}
});


//menu user
    document.addEventListener('DOMContentLoaded', function () {
        const avatar = document.getElementById('avatarToggle');
        const menu = document.getElementById('userMenu');
        if(avatar && menu){
        avatar.addEventListener('click', function (e) {
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            e.stopPropagation(); // tránh đóng khi click chính nó
        });

        // Đóng menu khi click bên ngoài
        document.addEventListener('click', function () {
            menu.style.display = 'none';
        });
    }
    });
</script>

        <script>

            
          //  document.addEventListener("DOMContentLoaded", function() {
        const checkactive = @json(session("active"));
        const current = document.getElementById("current");
        if (current) {
            if(checkactive.includes(current.innerText)){
                let linklist = document.querySelectorAll(".linkcheck");
                linklist.forEach(link => {
                    if(link.id === current.innerText){
                        link.classList.add("active");
                    }
                });
            }
        }
    // });
    </script>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raty/3.1.1/jquery.raty.min.js"></script>
    <script src="{{ asset(url("")) }}/js/bootstrap.min.js"></script>
    <script src="{{ asset(url("")) }}/js/jquery.magnific-popup.min.js"></script>
    <script src="{{ asset(url("")) }}/js/jquery.nice-select.min.js"></script>
    <script src="{{ asset(url("")) }}/js/jquery-ui.min.js"></script>
    <script src="{{ asset(url("")) }}/js/jquery.slicknav.js"></script>
    <script src="{{ asset(url("")) }}/js/owl.carousel.min.js"></script>
    <script src="{{ asset(url("")) }}/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset(url("")) }}/js/form-validate.js"></script>

    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: @json(session('success')),
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: @json(session('error')),
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        });
    </script>
    @endif

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let timeout;

        const saveBookingData = () => {
            const data = {
                cin: document.getElementById("date-in")?.value,
                cout: document.getElementById("date-out")?.value,
                pple: document.getElementById("guest")?.value,
                rms: document.getElementById("room")?.value,
                cppl: document.getElementById("customGuest")?.value,
            };

            // Chỉ gửi khi đầy đủ dữ liệu cơ bản
            if (data.cin && data.cout && data.rms) {
                if(data.pple || data.cppl){
                axios.post('/api/booking-cookies', data)
                    .then(() => console.log("Cookie bookingdata đã lưu."))
                    .catch(err => console.error("Lỗi khi lưu cookie bookingdata", err));
            }
        }
        };

        ['cin', 'cout', 'pple', 'rms', 'cppl'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('change', saveBookingData); // đổi từ 'input' thành 'change'
        }
    });
    </script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script src="{{ asset(url('')) }}/js/flatpickr.min.js"></script>
    <script src="{{ asset(url('')) }}/js/langvn.js"></script>
    <script src="{{ asset(url('')) }}/js/booking-calendar.js"></script>
    <script src="{{ asset(url('')) }}/js/booking-session.js"></script>
<script>
function copyVoucher(code) {
    navigator.clipboard.writeText(code).then(() => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Đã sao chép mã: ' + code,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    });
}
</script>

<script>
    $(document).ready(function() {
        $('#star-rating').raty({
            half: true, // Bật hỗ trợ nửa sao
            scoreName: 'rating', // Gán giá trị vào input hidden
            starHalf: 'https://cdnjs.cloudflare.com/ajax/libs/raty/3.1.1/images/star-half.png', // Icon nửa sao
            starOn: 'https://cdnjs.cloudflare.com/ajax/libs/raty/3.1.1/images/star-on.png', // Icon sao đầy
            starOff: 'https://cdnjs.cloudflare.com/ajax/libs/raty/3.1.1/images/star-off.png', // Icon sao rỗng
            hints: ['0.5', '1', '1.5', '2', '2.5', '3', '3.5', '4', '4.5', '5'], // Gợi ý cho từng mức
            click: function(score, evt) {
                $('#rating-value').val(score); // Cập nhật giá trị vào input hidden
                console.log('Rating selected:', score); // Debug log
            }
        });
        // Khi đổi lựa chọn kiểu đánh giá
    $('input[name="rate"]').on('change', function () {
    const type = $(this).val();
    if (type === 'star') {
        $('#number-rating-wrapper').addClass('hidden-important');
        $('#star-rating-wrapper').removeClass('hidden-important');
    } else {
        $('#star-rating-wrapper').addClass('hidden-important');
        $('#number-rating-wrapper').removeClass('hidden-important');
    }
});

    });
</script>

</body>

</html>