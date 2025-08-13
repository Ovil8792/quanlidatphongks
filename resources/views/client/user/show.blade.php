@extends('layout.main')

@section('main')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div style="background-color:#dfa974" class="card-header text-white">
            <h2 class="mb-0">Thông tin của bạn</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="avt d-flex justify-content-center">
                <div class="img" style="max-width:150px"><img width="100%" src="{{ Auth::user()->avatar??asset(url("/img/avt.svg")) }}" alt=""></div>
            </div>
<div>
               <p><strong>Tên:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
             <p><strong>Địa chỉ:</strong> {{ $user->address }} </p>
             <p><strong>Số điện thoại: </strong>{{ $user->phone??"" }}</p>
                

</div>
 
            <a href="{{ route('client.edit') }}" class="btn btn-outline-primary mt-3">Chỉnh sửa</a>
        </div>
    </div>
</div>
@endsection

