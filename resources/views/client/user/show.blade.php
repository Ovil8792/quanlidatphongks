@extends('layout.main')

@section('main')
<div class="container mt-5">
    <style>
        .profile-card .label { color: #6b7280; font-size: 0.9rem; }
        .profile-card .value { color: #111827; font-weight: 600; }
        .profile-avatar { width: 120px; height: 120px; border-radius: 9999px; object-fit: cover; border: 4px solid #f3f4f6; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .profile-header { background: linear-gradient(135deg, #dfa974, #f2c59f); }
    </style>
    <div class="card shadow-sm profile-card">
        <div class="card-header text-white profile-header">
            <h2 class="mb-0">Thông tin tài khoản</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif
            <div class="d-flex flex-column align-items-center mb-4">
                <img class="profile-avatar" src="{{ Auth::user()->avatar ? asset(url('')) . '/upload/' . Auth::user()->avatar : asset(url('/img/avt.svg')) }}" alt="Avatar">
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-white h-100">
                        <div class="label">Họ tên</div>
                        <div class="value">{{ $user->name }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-white h-100">
                        <div class="label">Email</div>
                        <div class="value">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-white h-100">
                        <div class="label">Số điện thoại</div>
                        <div class="value">{{ $user->phone ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-white h-100">
                        <div class="label">Địa chỉ</div>
                        <div class="value">{{ $user->address ?? '—' }}</div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-4">
                <a href="{{ route('client.edit') }}" class="btn btn-primary">
                    <i class="fa fa-pen me-1"></i> Chỉnh sửa thông tin
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

