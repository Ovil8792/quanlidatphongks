@extends('layout.main')

@section('main')

<div class="container mt-5">
    <style>
        .profile-edit-header { background: linear-gradient(135deg, #dfa974, #f2c59f); }
    </style>
    <div class="card shadow-sm">
        <div class="card-header text-white profile-edit-header">
            <h2 class="mb-0">Chỉnh sửa thông tin cá nhân</h2>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('client.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 d-flex align-items-center gap-3 mb-2">
                        <div>
                            <img src="{{ $user->avatar ? asset(url('')) . '/upload/' . $user->avatar : asset(url('/img/avt.svg')) }}" alt="Avatar" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid #f3f4f6;">
                        </div>
                        <div class="flex-grow-1">
                            <label for="avatar" class="form-label">Ảnh đại diện</label>
                            <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                            <small class="text-muted">Định dạng: jpeg, png, jpg, webp, gif. Tối đa 2MB.</small>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label">Họ tên</label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" placeholder="Nhập họ tên">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" id="email"
                               value="{{ old('email', $user->email) }}" placeholder="name@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" name="phone" id="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}" placeholder="VD: 0987654321">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="diachi" class="form-label">Địa chỉ</label>
                        <input type="text" name="diachi" id="diachi"
                               class="form-control @error('diachi') is-invalid @enderror"
                               value="{{ old('diachi', $user->address) }}" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành">
                        @error('diachi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">
                <div class="row g-3">
                    <div class="col-12">
                        <h5>Đổi mật khẩu</h5>
                        <small class="text-muted">Bỏ qua phần này nếu bạn không muốn đổi mật khẩu.</small>
                    </div>
                    <div class="col-md-4">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Mật khẩu hiện tại">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="new_password" class="form-label">Mật khẩu mới</label>
                        <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Ít nhất 6 ký tự">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>
                    <a href="{{ route('client.show') }}" class="btn btn-outline-secondary px-4">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
