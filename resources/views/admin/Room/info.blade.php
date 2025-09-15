@extends("admin.layout.main")

@section("main")
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-xl-10">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
              <div class="bg-primary bg-opacity-10 rounded-circle p-3"><i class="bi bi-door-open text-primary fs-4"></i></div>
              <div>
                <h5 class="mb-1">Phòng #{{ $roominf->id }} — {{ $roominf->name }}</h5>
                <div class="text-muted">Danh mục: <strong>{{ $roominf->category_name }}</strong></div>
              </div>
            </div>
            <div>
              <span class="badge {{ $roominf->isInUse==0 ? 'bg-success' : 'bg-warning' }}">
                {{ $roominf->isInUse==0? 'Phòng trống' : 'Đang sử dụng' }}
              </span>
            </div>
          </div>
        </div>
        <div class="card-body">
          @if($roominf->isInUse == 1 && $activeBooking)
            <div class="alert alert-info mb-4">
              <div class="d-flex align-items-center">
                <i class="bi bi-calendar-event fs-4 me-3"></i>
                <div>
                  <h6 class="mb-1">Phòng đã được đặt</h6>
                  <div class="row">
                    <div class="col-md-6">
                      <small class="text-muted d-block">Khách hàng</small>
                      <div class="fw-semibold">{{ $activeBooking->bill->user->name ?? $activeBooking->bill->guest_name ?? 'Không có tên' }}</div>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Số điện thoại</small>
                      <div class="fw-semibold">{{ $activeBooking->bill->guest_phone ?? '—' }}</div>
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="col-md-6">
                      <small class="text-muted d-block">Ngày nhận phòng</small>
                      <div class="fw-semibold">
                        @if($activeBooking->bill->checkin)
                          {{ $activeBooking->bill->checkin instanceof \Carbon\Carbon ? $activeBooking->bill->checkin->format('d/m/Y H:i') : \Carbon\Carbon::parse($activeBooking->bill->checkin)->format('d/m/Y H:i') }}
                        @else
                          Không rõ
                        @endif
                      </div>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Ngày trả phòng</small>
                      <div class="fw-semibold">
                        @if($activeBooking->bill->checkout)
                          {{ $activeBooking->bill->checkout instanceof \Carbon\Carbon ? $activeBooking->bill->checkout->format('d/m/Y H:i') : \Carbon\Carbon::parse($activeBooking->bill->checkout)->format('d/m/Y H:i') }}
                        @else
                          Không rõ
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif
          <div class="row g-4">
            <div class="col-lg-5">
              <img src="{{ asset('storage/upload/'.$roominf->pimage) }}" class="img-fluid rounded w-100" style="object-fit: cover; max-height: 320px;" alt="{{ $roominf->name }}">
            </div>
            <div class="col-lg-7">
              <div class="row">
                <div class="col-sm-6 mb-3">
                  <small class="text-muted d-block">Giá gốc</small>
                  <div class="fs-5 fw-semibold text-danger">{{ number_format($roominf->base_price,0,',','.') }} VND</div>
                </div>
                <div class="col-sm-6 mb-3">
                  <small class="text-muted d-block">Diện tích</small>
                  <div class="fw-semibold">{{ $roominf->room_area ?? '—' }} m²</div>
                </div>
                <div class="col-sm-6 mb-3">
                  <small class="text-muted d-block">Giường</small>
                  <div class="fw-semibold">{{ $roominf->bed_count ?? '—' }}</div>
                </div>
                <div class="col-sm-6 mb-3">
                  <small class="text-muted d-block">Số khách tối đa</small>
                  <div class="fw-semibold">{{ $roominf->max_guests ?? '—' }}</div>
                </div>
              </div>
              <div class="mt-2">
                <small class="text-muted d-block">Tiện ích</small>
                <div class="fw-semibold">{{ $roominf->amenities }}</div>
              </div>
              <div class="mt-2">
                <small class="text-muted d-block">Mô tả</small>
                <p class="mb-0">{{ $roominf->description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Ảnh phòng</h5>
          <a href="{{ route('admin.tostorepic',['id'=>$id]) }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i> Thêm ảnh</a>
        </div>
        <div class="card-body">
          <div class="d-flex flex-wrap gap-2">
            @foreach ($imglist as $img)
              <div class="position-relative">
                <img src="{{ asset('storage/upload/'.$img->imgname) }}" class="rounded" style="width: 180px; height: 120px; object-fit: cover;">
                <a class="btn btn-sm btn-light position-absolute top-0 end-0 m-1" onclick="if(confirm('Bạn có chắc muốn xóa ảnh này?')) document.getElementById('delete-form-{{ $img->id }}').submit(); return false;">
                  <i class="bi bi-trash text-danger"></i>
                </a>
                <form id="delete-form-{{ $img->id }}" action="{{ route('storage.sdelimg', $img->id) }}" method="POST" style="display: none;">
                  @csrf
                  @method('DELETE')
                </form>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Reviews Section -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <h5 class="mb-0"><i class="bi bi-star-fill text-warning me-2"></i>Đánh giá từ khách hàng</h5>
        </div>
        <div class="card-body">
          @if(isset($reviews) && count($reviews) > 0)
            <!-- Review Statistics -->
            <div class="row mb-4">
              <div class="col-md-3">
                <div class="text-center p-3 bg-light rounded">
                  <div class="h4 text-primary mb-1">{{ count($reviews) }}</div>
                  <small class="text-muted">Tổng đánh giá</small>
                </div>
              </div>
              <div class="col-md-3">
                <div class="text-center p-3 bg-light rounded">
                  <div class="h4 text-warning mb-1">{{ number_format($reviews->avg('rating'), 1) }}</div>
                  <small class="text-muted">Điểm trung bình</small>
                </div>
              </div>
              <div class="col-md-3">
                <div class="text-center p-3 bg-light rounded">
                  <div class="h4 text-success mb-1">{{ $reviews->where('rating', 5)->count() }}</div>
                  <small class="text-muted">5 sao</small>
                </div>
              </div>
              <div class="col-md-3">
                <div class="text-center p-3 bg-light rounded">
                  <div class="h4 text-info mb-1">{{ $reviews->where('rating', '>=', 4)->count() }}</div>
                  <small class="text-muted">4+ sao</small>
                </div>
              </div>
            </div>
            
            <!-- Reviews List -->
            <div class="row g-3">
              @foreach($reviews as $review)
                <div class="col-12">
                  <div class="border rounded p-3 bg-light">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                          <i class="bi bi-person-fill text-primary"></i>
                        </div>
                        <div>
                          <div class="fw-semibold">Khách hàng #{{ $review->userid ?? 'N/A' }}</div>
                          <small class="text-muted">
                            {{ $review->created_at ? \Carbon\Carbon::parse($review->created_at)->format('d/m/Y H:i') : 'N/A' }}
                          </small>
                        </div>
                      </div>
                      <div class="text-end">
                        <div class="rating-display">
                          @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($review->rating ?? 0))
                              <i class="bi bi-star-fill text-warning"></i>
                            @elseif($i == ceil($review->rating ?? 0) && ($review->rating ?? 0) - floor($review->rating ?? 0) >= 0.5)
                              <i class="bi bi-star-half text-warning"></i>
                            @else
                              <i class="bi bi-star text-muted"></i>
                            @endif
                          @endfor
                        </div>
                        <small class="text-muted">{{ number_format($review->rating, 1) }}/5.0</small>
                      </div>
                    </div>
                    @if($review->comment)
                      <div class="mt-2">
                        <p class="mb-0 text-dark">{{ $review->comment }}</p>
                      </div>
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="text-center py-4">
              <i class="bi bi-chat-dots text-muted fs-1"></i>
              <p class="text-muted mt-2">Chưa có đánh giá nào cho phòng này</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection