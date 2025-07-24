@extends('layout.main')
@section('main')

<div class="container mt-5">
    <h2 class="mb-4">Xác nhận đặt phòng</h2>
    <div class="row">
        {{-- Cột thông tin khách hàng --}}
        <div class="col-md-6">
            <div class="card shadow rounded-3 p-3 mb-4">
                <h4 class="mb-3">1. Thông tin khách hàng</h4>
                <div class="mb-2">
                    <label class="form-label"><strong>Họ và tên</strong>*:</label>
                    <input type="text" name="name" class="form-control">
                </div>
                <div class="mb-2">
                    <label class="form-label"><strong>Email</strong>*:</label>
                    <input type="email" name="email" class="form-control" value="{{ Auth::check() ? Auth::user()->email : '' }}">

                </div>
                <div class="mb-2">
                    <label class="form-label"> <strong>Số điện thoại</strong>*:</label>
                    <input type="text" name="phone-number" class="form-control">

                </div>
                <div class="mb-2">
                    <label class="form-label"><strong>Số định danh cá nhân</strong>:</label>
                    <input type="text" name="personal-identification-number" class="form-control">

                </div>
            </div>
        </div>

        {{-- Cột thông tin đặt phòng --}}
        @php
        function format_date($date) {
        return \Carbon\Carbon::parse($date)->format('d-m-Y');
        }
        @endphp

        <div class="col-md-6">
            <div class="card shadow rounded-3 p-3 mb-4">
                <h4 class="mb-3">2. Thông tin đặt phòng</h4>

                <div class="mb-2"><strong>Khách sạn:</strong></div>
                <div class="mb-2"><strong>Loại phòng:</strong></div>

                <div class="mb-2">
                    <strong>Số lượng phòng:</strong>
                    <input type="hidden" name="rooms" class="form-control-plaintext" readonly style="border: none;" value="{{ $booking['room'] }}">
                    <span>{{ $booking['room'] }}</span>
                </div>

                <div class="mb-2">
                    <strong>Ngày nhận phòng:</strong>
                    <input type="hidden" name="check_in" class="form-control-plaintext" readonly style="border: none;" value="{{ format_date($booking['check_in']) }}">
                    <span>{{ format_date($booking['check_in']) }}</span>
                </div>

                <div class="mb-2">
                    <strong>Ngày trả phòng:</strong>
                    <input type="hidden" name="check_out" class="form-control-plaintext" readonly style="border: none;" value="{{ format_date($booking['check_out']) }}">
                    <span>{{ format_date($booking['check_out']) }}</span>
                </div>

                <div class="mb-2">
                    <strong>Giá mỗi đêm: </strong>
                    <input type="hidden" name="ppn" class="form-control-plaintext" readonly style="border: none;" value="{{ number_format($booking['base_price'], 0, ',', '.') }} VNĐ">
                    <span>{{ number_format($booking['base_price'], 0, ',', '.') }} VNĐ</span>
                </div>

                <div class="mb-2">
                    <strong>Số đêm:</strong>
                    <input type="hidden" name="nights" class="form-control-plaintext" readonly style="border: none;" value="{{ $nights }}">
                    <span>{{ $nights }}</span>
                </div>

                <div class="mb-2">
                    <strong>Tổng tiền:</strong>
                    <input type="hidden" name="total" class="form-control-plaintext text-danger" readonly style="border: none;" value="{{ number_format($total, 0, ',', '.') }} VND">
                    <span><strong style="color: red;text-decoration: underline;">{{ number_format($total, 0, ',', '.') }} VND</strong></span>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label"><strong>Phương thức thanh toán:</strong></label>
                    <div class="form-control bg-light">
                        <img src="{{ uri('./img/logo_pttt/LogoMoMoSquare.png') }}" alt="Momo" style="height: 24px; margin-right: 8px;">
                        Thanh toán qua ví Momo
                    </div>
                    <input type="hidden" name="payment_method" value="momo">
                </div>
            </div>
        </div>
    </div>

    {{-- Nút thanh toán --}}
    <div class="text-center">
        <form action="{{ route('momo-payment') }}" method="POST">
            @csrf
            <input type="hidden" name="total" value="{{ $total }}">
            <button type="submit" class="btn btn-primary px-5 py-2">Thanh toán ngay</button>
        </form>
    </div>
</div>
@endsection