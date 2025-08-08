@extends("admin.layout.main")

@section("page-title", "Quản lý danh mục")

@section("main")
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Danh sách danh mục</h2>
                    <p class="text-muted mb-0">Quản lý tất cả danh mục trong hệ thống</p>
                </div>
                <div>
                    <a href="{{ route('admin.createcat') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Thêm danh mục
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Danh sách danh mục</h5>
                <div class="d-flex gap-2">
                    <form method="GET" class="d-flex gap-2">
                        <div class="input-group" style="width: 160px;">
                            <span class="input-group-text">ID</span>
                            <input type="number" name="search_id" value="{{ $searchId ?? '' }}" class="form-control" placeholder="ID">
                        </div>
                        <div class="input-group" style="width: 260px;">
                            <span class="input-group-text">Tên</span>
                            <input type="text" name="search_name" value="{{ $searchName ?? '' }}" class="form-control" placeholder="Tên danh mục">
                        </div>
                        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                        <a href="{{ route('admin.category') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="categoriesTable">
                    <thead>
                        <tr>
                            <th class="border-0">#</th>
                            <th class="border-0">Tên danh mục</th>
                            <th class="border-0">Ảnh</th>
                            <th class="border-0">Tạo ngày</th>
                            <th class="border-0">Sửa ngày</th>
                            <th class="border-0 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cat as $k)
                        <tr>
                            <td class="align-middle">
                                <span class="badge bg-light text-dark">{{ $k->id }}</span>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0 fw-semibold">{{ $k->name }}</h6>
                            </td>
                            <td class="align-middle">
                                @if ($k->image)
                                    <img src="{{ asset('storage/upload/' . $k->image) }}" alt="{{ $k->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle text-muted">
                                {{ $k->created_at }}
                            </td>
                            <td class="align-middle text-muted">
                                {{ $k->updated_at ?? 'Chưa sửa lần nào' }}
                            </td>
                            <td class="align-middle text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.editcat', ['id' => $k->id]) }}" class="btn btn-sm btn-outline-warning" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('admin.delcat', ['id' => $k->id]) }}" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa không?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script></script>
@endsection