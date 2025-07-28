@extends('layout.main')
@section('main')


    {{-- Nút thanh toán --}}
    {{-- <div class="text-center"> --}}
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Chọn phương thức thanh toán:</h2>
                </div>
                <div class="col-md-3">

                </div>
                <div class="col-md-2">
                    <form action="{{ route('vnpay-payment') }}" method="POST">
                    @csrf
                    <input type="hidden" name="total" value="{{ $total }}">
                    <input type="submit" name="redirect" class="px-5 py-2">
                </form>
                </div>
                
                <div class="col-md-2"><button class="btn btn-primary">MOMO</button></div>
                <div class="col-md-2"><button class="btn btn-primary">Tiền mặt</button></div>
                <div class="col-md-3">

                </div>
            </div>
            
        </div>
        
    {{-- </div> --}}
{{-- </div> --}}
@endsection