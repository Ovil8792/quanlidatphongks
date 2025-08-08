@extends("admin.layout.main")

@section("page-title", "Chỉnh sửa tài khoản")

@section("main")
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                <i class="bi bi-person-gear text-warning fs-4"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h5 class="card-title mb-1">Chỉnh sửa tài khoản</h5>
              <p class="text-muted mb-0">Cập nhật thông tin người dùng: {{ $user->name }}</p>
            </div>
          </div>
        </div>

        <div class="card-body p-4">
          <form action="{{ route('admin.updateuser', $user->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
              <label class="form-label" for="name">Tên</label>
              <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label" for="email">Email</label>
              <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label" for="password">Mật khẩu</label>
              <input class="form-control" type="text" name="password" id="password" value="{{ old('password', $user->password) }}">
            </div>

            <div class="mb-3">
              <label class="form-label" for="role">Vai trò</label>
              <select class="form-select" name="role" id="role" required>
                <option {{ $user->role=="user"?"selected":"" }} value="user">Người dùng</option>
                <option {{ $user->role=="admin"?"selected":"" }} value="admin">Quản lí</option>
                <option {{ $user->role=="staff"?"selected":"" }} value="staff">Nhân viên</option>
              </select>
            </div>

            <div class="d-flex gap-2 justify-content-end">
              <a href="{{ route('admin.account') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
              </a>
              <button type="submit" class="btn btn-warning">
                <i class="bi bi-check-circle me-1"></i> Cập nhật
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection