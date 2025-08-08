@extends("admin.layout.main")

@section("page-title", "Quản lý tài khoản")

@section("main")
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <div class="d-flex justify-content-between align-items-center">
            <div class="flex-shrink-0">
              <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                <i class="bi bi-people text-primary fs-4"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h5 class="card-title mb-1">Danh sách người dùng</h5>
              <p class="text-muted mb-0">Quản lý tài khoản trong hệ thống</p>
            </div>
            <form method="GET" class="d-flex gap-2">
              <div class="input-group" style="width: 160px;">
                <span class="input-group-text">ID</span>
                <input type="number" name="search_id" value="{{ $searchId ?? '' }}" class="form-control" placeholder="ID">
              </div>
              <div class="input-group" style="width: 260px;">
                <span class="input-group-text">Tên</span>
                <input type="text" name="search_name" value="{{ $searchName ?? '' }}" class="form-control" placeholder="Tên người dùng">
              </div>
              <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
              <a href="{{ route('admin.account') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
            </form>
          </div>
        </div>

        <div class="card-body p-4">
          <style>
            .table-header-custom th {
              background: linear-gradient(90deg, #f8fafc 0%, #eef2ff 100%);
              color: #334155;
              font-weight: 600;
              text-transform: uppercase;
              letter-spacing: 0.02em;
              border-bottom: 2px solid var(--border-color);
              position: sticky;
              top: 0;
              z-index: 1;
              padding: 1.25rem 1.25rem; /* dãn khoảng cách trong th */
              line-height: 1.4;
            }
            .table-header-custom th:first-child { border-top-left-radius: 0.75rem; }
            .table-header-custom th:last-child { border-top-right-radius: 0.75rem; }
          </style>
          <p style="display: none;" id="check">acc</p>
          <div class="table-responsive">
            <table class="table table-hover align-middle bg-white">
              <thead class="table-header-custom">
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Tên</th>
                  <th scope="col">Email</th>
                  <th scope="col">Vai trò</th>
                  <th scope="col">Mật khẩu</th>
                  <th scope="col">Ngày tạo</th>
                  <th scope="col" class="text-center">Hành động</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($user as $key => $value)
                <tr>
                  <th scope="row">{{ $value->id }}</th>
                  <td>{{ $value->name }}</td>
                  <td>{{ $value->email }}</td>
                  <td>{{ $value->role }}</td>
                  <td>{{ $value->password }}</td>
                  <td>{{ $value->created_at }}</td>
                  <td>
                    <a href="{{ route('admin.edituser', $value->id) }}" class="btn btn-sm btn-primary">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="{{ route('admin.deleteuser', $value->id) }}" class="btn btn-sm btn-danger">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection