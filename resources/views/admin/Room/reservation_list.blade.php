@extends("admin.layout.main")

@section("page-title", "Quản lý phòng")

@section("main")

<!-- dựa theo các trang view khác thuộc admin.room để tạo ra bảng danh sách đơn đặt phòng -->
<div class="container-fluid">
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title">
                    <h2>Danh sách đơn đặt phòng</h2>
                </div>
            </div>
        </div>
    </div>
@php
    // dd($reservations);
@endphp
    <div class="tables-wrapper mt-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style mb-30">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>Phòng</th>
                                    <th>Ngày nhận</th>
                                    <th>Ngày trả</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $reservation)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $reservation->guest_name ?? 'N/A' }}</td>
                                        <td>{{ $reservation->room_id." - ".$reservation->room->name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format("d/m/Y") }}</td>
                                        <td>{{ \Carbon\Carbon::parse($reservation->end_time)->format("d/m/Y") }}</td>
                                        <td>
                                            @if($reservation->status == 'pending')
                                                <span class="badge bg-warning">Chờ thanh toán</span>
                                            @elseif($reservation->status == 'paid')
                                                <span class="badge bg-success">Đã thanh toán</span>
                                            @elseif($reservation->status == 'cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $reservation->status }}</span>
                                            @endif
                                        </td>
                                       
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Không có đơn đặt phòng nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>


@endsection