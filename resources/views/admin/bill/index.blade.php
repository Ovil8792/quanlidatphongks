@extends("admin.layout.main")

@section("page-title", "Quản lý hóa đơn")

@section("main")
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <div class="d-flex justify-content-between align-items-center">
            <div class="flex-shrink-0">
              <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                <i class="bi bi-receipt text-primary fs-4"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h5 class="card-title mb-1">Danh sách hóa đơn</h5>
              <p class="text-muted mb-0">Theo dõi trạng thái và chi tiết thanh toán</p>
            </div>
            <form method="GET" class="d-flex gap-2">
              <div class="input-group" style="width: 200px;">
                <span class="input-group-text">ID</span>
                <input type="number" name="search_id" value="{{ $searchId ?? '' }}" class="form-control" placeholder="ID hóa đơn">
              </div>
              <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
              <a href="{{ route('admin.bills.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
            </form>
          </div>
        </div>

        <div class="card-body p-4">
          <div class="table-responsive">
            <table class="table table-hover align-middle bg-white">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Khách hàng</th>
                  <th>Ngày đặt phòng</th>
                  <th>Ngày nhận phòng</th>
                  <th>Ngày trả phòng</th>
                  <th>Trạng thái</th>
                  <th>Ngày thanh toán</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                @foreach($bills as $bill)
                <tr>
                  <td>{{ $bill->id }}</td>
                  <td>{{ $bill->guest_name ?? ($bill->user->name ?? 'Không có tên') }}</td>
                  <td>
                    @if($bill->booking_date)
                      {{ $bill->booking_date instanceof \Carbon\Carbon ? $bill->booking_date->format('d/m/Y H:i') : \Carbon\Carbon::parse($bill->booking_date)->format('d/m/Y H:i') }}
                    @else
                      Không rõ
                    @endif
                  </td>
                  <td>
                    @if($bill->checkin)
                      {{ $bill->checkin instanceof \Carbon\Carbon ? $bill->checkin->format('d/m/Y H:i') : \Carbon\Carbon::parse($bill->checkin)->format('d/m/Y H:i') }}
                    @else
                      Không rõ
                    @endif
                  </td>
                  <td>
                    @if($bill->checkout)
                      {{ $bill->checkout instanceof \Carbon\Carbon ? $bill->checkout->format('d/m/Y H:i') : \Carbon\Carbon::parse($bill->checkout)->format('d/m/Y H:i') }}
                    @else
                      Không rõ
                    @endif
                  </td>
                  <td>
                    @if($bill->status == 'paid')
                      <span class="badge bg-success px-3 py-2">💰 Đã thanh toán</span>
                    @elseif($bill->status == 'cancelled')
                      <span class="badge bg-danger px-3 py-2">❌ Đã hủy</span>
                    @else
                      <form action="{{ route('admin.bills.updateStatus', $bill->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select form-select-sm w-auto d-inline-block" onchange="this.form.submit()">
                          <option value="pending" {{ $bill->status == 'pending' ? 'selected' : '' }}>🕒 Đang đặt</option>
                          <option value="paid">💰 Đã thanh toán</option>
                          <option value="cancelled">❌ Đã hủy</option>
                        </select>
                      </form>
                    @endif
                  </td>
                  <td>
                    @if ($bill->status == 'paid' && $bill->payment_date)
                      {{ $bill->payment_date instanceof \Carbon\Carbon ? $bill->payment_date->format('d/m/Y H:i') : \Carbon\Carbon::parse($bill->payment_date)->format('d/m/Y H:i') }}
                    @elseif ($bill->status == 'cancelled')
                      Không thanh toán
                    @else
                      Không rõ
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('admin.bills.show', $bill->id) }}" class="btn btn-sm btn-info">Chi tiết</a>
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