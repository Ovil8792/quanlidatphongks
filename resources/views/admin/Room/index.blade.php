@extends("admin.layout.main")

@section("page-title", "Quản lý phòng")

@section("main")
{{-- this is category page --}}
<a href="{{ route("admin.addroom") }}" class="btn btn-primary">Thêm phòng</a>
          <div class="tables-wrapper">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-style mb-30">
                  <h6 class="mb-10">Danh sách phòng</h6>
                   <p style="display: none;" id="check">rooms</p>
                  <div class="table-wrapper table-responsive">
                    
                    <!-- end table -->
                  </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-door-open text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Tổng số phòng</h6>
                            <h4 class="mb-0">{{ count($roomlist) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Phòng trống</h6>
                            <h4 class="mb-0">{{ $roomlist->where('isInUse', 0)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-clock text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Đang sử dụng</h6>
                            <h4 class="mb-0">{{ $roomlist->where('isInUse', 1)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-currency-dollar text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Giá trung bình</h6>
                            <h4 class="mb-0">{{ number_format($roomlist->avg('base_price'), 0, ',', '.') }}đ</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rooms Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Danh sách phòng</h5>
                <div class="d-flex gap-2">
                    <form method="GET" class="d-flex gap-2">
                        <div class="input-group" style="width: 160px;">
                            <span class="input-group-text">ID</span>
                            <input type="number" name="search_id" value="{{ $searchId ?? '' }}" class="form-control" placeholder="ID">
                        </div>
                        <div class="input-group" style="width: 260px;">
                            <span class="input-group-text">Tên</span>
                            <input type="text" name="search_name" value="{{ $searchName ?? '' }}" class="form-control" placeholder="Tên phòng">
                        </div>
                        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                        <a href="{{ route('admin.roomlist') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="roomsTable">
                    <thead>
                        <tr>
                            <th class="border-0">#</th>
                            <th class="border-0">Tên phòng</th>
                            <th class="border-0">Danh mục</th>
                            <th class="border-0">Ảnh</th>
                            <th class="border-0">Giá (VNĐ)</th>
                            <th class="border-0">Diện tích (m²)</th>
                            <th class="border-0">Số giường</th>
                            <th class="border-0">Tối đa khách</th>
                            <th class="border-0">Trạng thái</th>
                            <th class="border-0 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roomlist as $room)
                        <tr>
                            <td class="align-middle">
                                <span class="badge bg-light text-dark">{{ $room->id }}</span>
                            </td>
                            <td class="align-middle">
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $room->name }}</h6>
                                    <small class="text-muted">{{ Str::limit($room->description, 50) }}</small>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $room->category_name }}</span>
                            </td>
                            <td class="align-middle">
                                @if($room->pimage)
                                    <img src="{{ asset('storage/upload/' . $room->pimage) }}" 
                                         alt="{{ $room->name }}" 
                                         class="rounded" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="fw-semibold text-success">{{ number_format($room->base_price, 0, ',', '.') }}đ</span>
                            </td>
                            <td class="align-middle">
                                <span class="text-muted">{{ $room->room_area ?? 'N/A' }}</span>
                            </td>
                            <td class="align-middle">
                                <span class="text-muted">{{ $room->bed_count ?? 'N/A' }}</span>
                            </td>
                            <td class="align-middle">
                                <span class="text-muted">{{ $room->max_guests ?? 'N/A' }}</span>
                            </td>
                            <td class="align-middle">
                                @if($room->isInUse == 0)
                                    <span class="badge bg-success">Phòng trống</span>
                                @else
                                    <span class="badge bg-warning">Đang sử dụng</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.showroom', ['id' => $room->id]) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.editroom', ['id' => $room->id]) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('admin.delroom', ['id' => $room->id]) }}" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Xóa"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?')">
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