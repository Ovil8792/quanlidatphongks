@extends("admin.layout.main")

@section("page-title", "Chi tiết hóa đơn")

@section("main")
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="bg-info bg-opacity-10 rounded-circle p-3">
                <i class="bi bi-file-text text-info fs-4"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h5 class="card-title mb-1">Chi tiết hóa đơn #{{ $bill->id }}</h5>
              <p class="text-muted mb-0">
                <span class="me-3">Ngày đặt: <strong>
                  @if($bill->booking_date)
                    {{ $bill->booking_date instanceof \Carbon\Carbon ? $bill->booking_date->format('d/m/Y H:i') : \Carbon\Carbon::parse($bill->booking_date)->format('d/m/Y H:i') }}
                  @else
                    Không rõ
                  @endif
                </strong></span>
                <span class="me-3">Ngày nhận: <strong>
                  @if($bill->checkin)
                    {{ $bill->checkin instanceof \Carbon\Carbon ? $bill->checkin->format('d/m/Y H:i') : \Carbon\Carbon::parse($bill->checkin)->format('d/m/Y H:i') }}
                  @else
                    Không rõ
                  @endif
                </strong></span>
                <span>Ngày trả: <strong>
                  @if($bill->checkout)
                    {{ $bill->checkout instanceof \Carbon\Carbon ? $bill->checkout->format('d/m/Y H:i') : \Carbon\Carbon::parse($bill->checkout)->format('d/m/Y H:i') }}
                  @else
                    Không rõ
                  @endif
                </strong></span>
              </p>
            </div>
          </div>
        </div>

        <div class="card-body p-4">
          <h6 class="fw-semibold mb-3 text-primary">
            <i class="bi bi-journal-text me-2"></i>Thông tin đặt phòng
          </h6>
          <div class="table-responsive">
            <table class="table table-hover align-middle bg-white">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Số phòng</th>
                  <th>Loại phòng</th>
                  <th>Giá mỗi đêm</th>
                  <th>Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                @php $tong = 0; @endphp
                @forelse ($bill->details as $index => $detail)
                  @php
                    $thanhtien = $detail->room_rate * $detail->quantity;
                    $tong += $thanhtien;
                  @endphp
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->room->name ?? 'N/A' }}</td>
                    <td>{{ $detail->room->category->name ?? 'Không rõ' }}</td>
                    <td>{{ number_format($detail->room_rate) }} đ</td>
                    <td>{{ number_format($thanhtien) }} đ</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-muted">Không có chi tiết phòng nào.</td>
                  </tr>
                @endforelse
              </tbody>
              @if ($bill->details->isNotEmpty())
              <tfoot>
                <tr>
                  <td colspan="4" class="text-end"><strong>Tổng thành tiền:</strong></td>
                  <td><strong>{{ number_format($tong) }} đ</strong></td>
                </tr>
              </tfoot>
              @endif
            </table>
          </div>

          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('admin.bills.index') }}" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách hóa đơn
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection