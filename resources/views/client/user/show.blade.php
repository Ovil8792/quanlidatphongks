@extends('layout.main')

@section('main')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Thông tin của bạn</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <p><strong>Tên:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
             <p><strong>Địa chỉ:</strong> {{ $user->address }} </p>
             <p><strong>Số điện thoại: </strong>{{ $user->phone??"" }}</p>


            <a href="{{ route('client.edit') }}" class="btn btn-outline-primary mt-3">Chỉnh sửa</a>
        </div>
    </div>
</div>
@endsection

