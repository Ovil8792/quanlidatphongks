@extends('layout.main')

@section("main")
<style>
    .room-item {
        border: 1px solid #e0e0e0;
        margin-bottom: 18px !important;
        padding: 10px 30px 10px 10px;
        border-radius: 18px;
        box-shadow: 0 2px 16px 0 rgba(0,0,0,0.07);
        background: #fff;
        transition: box-shadow 0.2s, transform 0.2s;
        min-height: 220px;
    }
    .room-item:hover {
        box-shadow: 0 8px 32px 0 rgba(223,169,116,0.18);
        transform: translateY(-2px) scale(1.01);
    }
    .room-item .img img {
        border-radius: 14px;
        object-fit: cover;
        box-shadow: 0 2px 8px 0 rgba(0,0,0,0.08);
    }
    .room-item .info h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 6px;
        color: #dfa974;
    }
    .room-item .info h4 {
        font-size: 1.1rem;
        color: #333;
        margin-bottom: 4px;
    }
    .room-item .info h5 {
        font-size: 1rem;
        color: #666;
        margin-bottom: 4px;
    }
    .room-item .info p {
        font-size: 0.98rem;
        color: #444;
        margin-bottom: 4px;
    }
    .room-item .info .amenities-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 6px;
    }
    .room-item .info .amenity {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 2px 10px;
        font-size: 0.92rem;
        color: #dfa974;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .room-item .info .amenity i {
        font-size: 1rem;
    }
    .room-item .info .desc {
        color: #555;
        font-size: 0.97rem;
        margin-top: 4px;
        max-width: 420px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .room-item .detail-btn {
        display: inline-block;
        font-size: 14px;
        border-radius: 8px;
        font-weight: 700;
        padding: 15px 10px;
        background: linear-gradient(90deg, #dfa974 60%, #f8c07e 100%);
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        box-shadow: 0 2px 8px 0 rgba(223,169,116,0.13);
        border: none;
        transition: background 0.2s, color 0.2s;
        margin-top: 10px;
        text-align: center;
    }
    .room-item .detail-btn:hover {
        background: #dfa974;
        color: #fff;
        text-decoration: none;
    }
    /* Sticky sidebar filter */
    .sidebar {
        background: #fff;
        padding: 24px 18px 18px 18px;
        border-radius: 14px;
        box-shadow: 0 2px 12px 0 rgba(0,0,0,0.06);
        margin-bottom: 18px;
        position: sticky;
        top: 90px;
        z-index: 10;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
        transition: box-shadow 0.2s;
    }
    @media (max-width: 991px) {
        .sidebar {
            position: static;
            max-height: none;
            overflow: visible;
        }
    }
    .sidebar h6 {
        margin-bottom: 12px;
        font-weight: bold;
        color: #dfa974;
        font-size: 1.1rem;
    }
    .sidebar .filter-group {
        margin-bottom: 18px;
    }
    .sidebar .filter-group label {
        margin-left: 8px;
        font-size: 1rem;
        color: #444;
        cursor: pointer;
    }
    .sidebar input[type="checkbox"] {
        accent-color: #dfa974;
        width: 18px;
        height: 18px;
        vertical-align: middle;
        margin-right: 4px;
    }
    .sidebar .filter-group div:hover label {
        color: #dfa974;
    }
    .sidebar .btn-primary {
        background: linear-gradient(90deg, #dfa974 60%, #f8c07e 100%);
        border: none;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 8px;
        margin-top: 8px;
        transition: background 0.2s;
    }
    .sidebar .btn-primary:hover {
        background: #dfa974;
    }
    .results-section {
        padding: 0 10px;
    }
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 30px;
    }
    .room-pagination a {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 6px;
        background: #fff;
        color: #dfa974;
        font-weight: 600;
        border: 1px solid #dfa974;
        margin: 0 2px;
        transition: background 0.2s, color 0.2s;
        text-decoration: none;
    }
    .room-pagination a:hover, .room-pagination a.active {
        background: #dfa974;
        color: #fff;
    }
    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .filter-section h5 {
        color: #dfa974;
        margin-bottom: 15px;
    }
    .price-inputs {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    .price-inputs input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    @media only screen and (max-width: 768px) {
        .room-item {
            flex-direction: column !important;
            padding: 10px 5px 10px 5px;
        }
        .room-item .img {
            width: 100% !important;
        }
        .room-item .conte {
            flex-direction: column;
            width: 100% !important;
        }
        .room-item .info {
            width: 100% !important;
        }
        .room-item .detail-btn {
            width: 100%;
        }
        .sidebar {
            padding: 16px 8px;
        }
        .price-inputs {
            flex-direction: column;
        }
    }
</style>

<div class="container">
    @if(session('location_selected') || session('search_dates'))
    <div style="background-color:#dea97391!important;" id="bookInfo" class="alert alert-info booking-form">
        <form class="d-flex justify-content-center" action="{{ route('search.pending') }}" method="GET" id="searchForm">
            <div class="check-date">
                <label class="d-flex justify-content-center" for="date-in">{{ __('messages.cin') }}:</label>
                <input value="{{ session('search_dates.checkin') ?? $checkin ?? '' }}" type="date" id="date-in" name="date_in" min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="check-date">
                <label class="d-flex justify-content-center" for="date-out">{{ __('messages.cout') }}:</label>
                <input value="{{ session('search_dates.checkout') ?? $checkout ?? '' }}" type="date" id="date-out" name="date_out" min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="select-option">
                <label class="d-flex justify-content-center" for="guest">{{ __('messages.guest') }}:</label>
                <select id="guest" name="guest">
                    <option {{ (session('search_dates.people') ?? $people) == "2l1n" ? 'selected' : "" }} value="2l1n">2 lớn+1 nhỏ</option>
                    <option {{ (session('search_dates.people') ?? $people) == "4l3n" ? 'selected' : "" }} value="4l3n">4 lớn 3 nhỏ</option>
                    <option {{ (session('search_dates.people') ?? $people) == "custom" ? 'selected' : "" }} value="custom">Nhập riêng</option>
                </select>
                <input class="form-control" value="{{ session('search_dates.customGuest') ?? $customGuest }}" type="text" id="customGuest" name="custom_guest"
                    placeholder="Nhập số người" min="1" />
            </div>
            <div class="select-option">
                <label class="d-flex justify-content-center" for="room">{{ __('messages.rn') }}:</label>
                <select id="room" name="room">
                    <option {{ (session('search_dates.roomCount') ?? $roomCount) == 1 ? 'selected' : "" }} value="1">1 Phòng</option>
                    <option {{ (session('search_dates.roomCount') ?? $roomCount) == 2 ? 'selected' : "" }} value="2">2 Phòng</option>
                    <option {{ (session('search_dates.roomCount') ?? $roomCount) == 3 ? 'selected' : "" }} value="3">3 Phòng</option>
                    <option {{ (session('search_dates.roomCount') ?? $roomCount) == 4 ? 'selected' : "" }} value="4">4 Phòng</option>
                    <option {{ (session('search_dates.roomCount') ?? $roomCount) == 10 ? 'selected' : "" }} value="10">10 Phòng</option>
                </select>
            </div>
            <div>
                <button style="color:black;background-color:white;" type="submit">{{ __('messages.checkavai') }}</button>
            </div>
        </form>
    </div>
    @endif

    <div class="row">
        <!-- Sidebar (col-md-3) -->
        <div class="col-md-3">
            <div class="sidebar">
                <h6><i class="fa fa-filter me-2"></i>Bộ lọc tìm kiếm</h6>
                
                <!-- Loại phòng -->
                <div class="filter-group">
                    <h6><i class="fa fa-bed me-2"></i>Loại phòng</h6>
                    @if(session("category"))
                        @foreach (session("category") as $k)
                        <div>
                            <input type="checkbox" name="room_type[]" value="{{ $k->id }}" 
                                   id="room_type_{{ $k->id }}" 
                                   @if(request()->query('category_id') == $k->id) checked @endif>
                            <label for="room_type_{{ $k->id }}">{{ $k->name }}</label>
                        </div>
                        @endforeach
                    @else
                        <div class="text-muted">Không có loại phòng</div>
                    @endif
                </div>

                <!-- Khoảng giá -->
                <div class="filter-group">
                    <h6><i class="fa fa-money-bill me-2"></i>Khoảng giá</h6>
                    <div>
                        <input type="checkbox" name="price_range[]" value="low" id="price_low">
                        <label for="price_low">Dưới 1 triệu</label>
                    </div>
                    <div>
                        <input type="checkbox" name="price_range[]" value="medium" id="price_medium">
                        <label for="price_medium">1 - 3 triệu</label>
                    </div>
                    <div>
                        <input type="checkbox" name="price_range[]" value="high" id="price_high">
                        <label for="price_high">Trên 3 triệu</label>
                    </div>
                    <div class="price-inputs">
                        <div>
                            <label for="custom_price_min">Từ:</label>
                            <input type="number" id="custom_price_min" class="form-control" placeholder="Tối thiểu" min="0">
                        </div>
                        <div>
                            <label for="custom_price_max">Đến:</label>
                            <input type="number" id="custom_price_max" class="form-control" placeholder="Tối đa" min="0">
                        </div>
                    </div>
                </div>

                <!-- Tiện ích -->
                <div class="filter-group">
                    <h6><i class="fa fa-star me-2"></i>Tiện ích</h6>
                    <div>
                        <input type="checkbox" name="amenities[]" value="wifi" id="amenity_wifi">
                        <label for="amenity_wifi">Wifi</label>
                    </div>
                    <div>
                        <input type="checkbox" name="amenities[]" value="máy lạnh" id="amenity_ac">
                        <label for="amenity_ac">Máy lạnh</label>
                    </div>
                    <div>
                        <input type="checkbox" name="amenities[]" value="bãi đỗ xe" id="amenity_parking">
                        <label for="amenity_parking">Bãi đỗ xe</label>
                    </div>
                    <div>
                        <input type="checkbox" name="amenities[]" value="tủ lạnh" id="amenity_fridge">
                        <label for="amenity_fridge">Tủ lạnh</label>
                    </div>
                    <div>
                        <input type="checkbox" name="amenities[]" value="tivi" id="amenity_tv">
                        <label for="amenity_tv">Tivi</label>
                    </div>
                </div>

                <!-- Số người tối đa -->
                <div class="filter-group">
                    <h6><i class="fa fa-users me-2"></i>Số người tối đa</h6>
                    <div>
                        <input type="checkbox" name="max_guests[]" value="2" id="guests_2">
                        <label for="guests_2">2 người</label>
                    </div>
                    <div>
                        <input type="checkbox" name="max_guests[]" value="4" id="guests_4">
                        <label for="guests_4">4 người</label>
                    </div>
                    <div>
                        <input type="checkbox" name="max_guests[]" value="6" id="guests_6">
                        <label for="guests_6">6+ người</label>
                    </div>
                </div>

                <button class="btn btn-primary w-100" onclick="applyFilters()">
                    <i class="fa fa-search me-2"></i>Áp dụng bộ lọc
                </button>
                
                <button class="btn btn-outline-secondary w-100 mt-2" onclick="resetFilters()">
                    <i class="fa fa-refresh me-2"></i>Làm mới
                </button>
            </div>
        </div>

        <!-- Results Section (col-md-9) -->
        <div class="col-md-9 results-section">
            <div class="filter-section">
                <h5><i class="fa fa-info-circle me-2"></i>Kết quả tìm kiếm</h5>
                <p class="mb-0">
                    @if($results && count($results) > 0)
                        Tìm thấy <strong>{{ count($results) }}</strong> phòng phù hợp
                        @if(session('search_dates.checkin') && session('search_dates.checkout'))
                            từ {{ session('search_dates.checkin') }} đến {{ session('search_dates.checkout') }}
                        @endif
                    @else
                        Không tìm thấy phòng phù hợp với tiêu chí tìm kiếm
                    @endif
                </p>
            </div>

            @if($results && count($results) > 0)
                @foreach($results as $room)
                <div class="room-item d-flex px-3 shadow-sm py-3">
                    <div class="img" style="width:40%;">
                        @if($room->pimage)
                            <img style="max-height:278px;min-height:217px" width="100%" 
                                 src="{{ asset('storage/upload/' . $room->pimage) }}" alt="room_primary_image">
                        @else
                            <img style="max-height:278px;min-height:217px" width="100%" 
                                 src="{{ asset('img/room/room-1.jpg') }}" alt="default_room_image">
                        @endif
                    </div>
                    <div class="conte mt-3 d-flex justify-content-between" style="width:60%">
                        <div class="info mx-2" style="width:72%">
                            <h3>{{ $room->name }}</h3>
                            <h4>Giá: <span style="color:#dfa974; font-size:1.2rem; font-weight:700">
                                {{ number_format($room->base_price,0,",",".") }} VND</span>/đêm</h4>
                            <h5>Loại phòng: <span style="color:#dfa974">{{ $room->category->name ?? 'Không phân loại' }}</span></h5>
                            
                            @if($room->amenities)
                            <div class="amenities-list">
                                @foreach(explode(',', $room->amenities) as $amenity)
                                    @php $amenity = trim($amenity); @endphp
                                    @if($amenity)
                                        <span class="amenity">
                                            @if(stripos($amenity, 'wifi') !== false)
                                                <i class="fa fa-wifi"></i>
                                            @elseif(stripos($amenity, 'máy lạnh') !== false || stripos($amenity, 'ac') !== false)
                                                <i class="fa fa-snowflake-o"></i>
                                            @elseif(stripos($amenity, 'bãi đỗ xe') !== false || stripos($amenity, 'parking') !== false)
                                                <i class="fa fa-car"></i>
                                            @elseif(stripos($amenity, 'tủ lạnh') !== false || stripos($amenity, 'fridge') !== false)
                                                <i class="fa fa-refrigerator"></i>
                                            @elseif(stripos($amenity, 'tivi') !== false || stripos($amenity, 'tv') !== false)
                                                <i class="fa fa-tv"></i>
                                            @elseif(stripos($amenity, 'gym') !== false)
                                                <i class="fa fa-dumbbell"></i>
                                            @elseif(stripos($amenity, 'spa') !== false)
                                                <i class="fa fa-spa"></i>
                                            @else
                                                <i class="fa fa-check-circle"></i>
                                            @endif
                                            {{ $amenity }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                            
                            <p class="desc mt-1">{{ $room->description ?? 'Không có mô tả' }}</p>
                            
                            @if($room->room_area || $room->max_guests || $room->bed_count)
                            <div class="room-details mt-2">
                                @if($room->room_area)
                                    <small class="text-muted me-3"><i class="fa fa-arrows-alt me-1"></i>{{ $room->room_area }}m²</small>
                                @endif
                                @if($room->max_guests)
                                    <small class="text-muted me-3"><i class="fa fa-users me-1"></i>Tối đa {{ $room->max_guests }} người</small>
                                @endif
                                @if($room->bed_count)
                                    <small class="text-muted"><i class="fa fa-bed me-1"></i>{{ $room->bed_count }} giường</small>
                                @endif
                            </div>
                            @endif
                        </div>
                        <div style="width:28%;margin:auto 0;" class="d-flex flex-column align-items-center justify-content-center">
                             <a class="detail-btn" id="infoBTN" href="{{ route('client.roomdetail',['id'=>$room->id]) }}">Chi tiết</a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <i class="fa fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Không tìm thấy kết quả phù hợp</h4>
                    <p class="text-muted">Vui lòng thử lại với bộ lọc khác</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="py-5" style="position:relative;width:100%;">
    <div class="pagination" style="position: relative; left: 0; transform: none;">
        <div class="room-pagination">
            <a href="#" class="active">1</a>
            <a href="#">2</a>
            <a href="#">Next <i class="fa fa-long-arrow-right"></i></a>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const guestSelect = document.getElementById("guest");
    const customInput = document.getElementById("customGuest");

    // Set initial state based on the selected option
    if (guestSelect.value === "custom") {
        customInput.style.display = "block";
        customInput.required = true;
    } else {
        customInput.style.display = "none";
        customInput.required = false;
    }

    // Add event listener to toggle visibility
    guestSelect.addEventListener("change", function() {
        if (this.value === "custom") {
            customInput.style.display = "block";
            customInput.required = true;
        } else {
            customInput.style.display = "none";
            customInput.required = false;
        }
    });
});

function applyFilters(page = 1) {
    const roomTypes = Array.from(document.querySelectorAll('input[name="room_type[]"]:checked')).map(el => el.value);
    const priceRanges = Array.from(document.querySelectorAll('input[name="price_range[]"]:checked')).map(el => el.value);
    const amenities = Array.from(document.querySelectorAll('input[name="amenities[]"]:checked')).map(el => el.value);
    const maxGuests = Array.from(document.querySelectorAll('input[name="max_guests[]"]:checked')).map(el => el.value);
    const customPriceMin = document.getElementById('custom_price_min')?.value;
    const customPriceMax = document.getElementById('custom_price_max')?.value;

    const data = {
        room_types: roomTypes,
        price_ranges: priceRanges,
        amenities: amenities,
        max_guests: maxGuests,
        custom_price_min: customPriceMin,
        custom_price_max: customPriceMax,
        cin: document.getElementById("date-in")?.value,
        cout: document.getElementById("date-out")?.value,
        pple: document.getElementById("guest")?.value,
        rms: document.getElementById("room")?.value,
        cppl: document.getElementById("customGuest")?.value,
        keyword: document.getElementById("search")?.value
    };

    // Show loading
    const resultsSection = document.querySelector('.results-section');
    resultsSection.innerHTML = '<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2">Đang tìm kiếm...</p></div>';

    axios.post('/api/filter-rooms?page=' + page, data)
        .then(response => {
            const res = response.data;
            resultsSection.innerHTML = '';
            
            if (res.data && res.data.length > 0) {
                res.data.forEach(room => {
                    const roomItem = document.createElement('div');
                    roomItem.className = 'room-item d-flex px-3 shadow-sm py-3';
                    
                    const amenitiesHtml = room.amenities ? 
                        room.amenities.split(',').map(am => {
                            const amenity = am.trim();
                            let icon = '<i class="fa fa-check-circle"></i>';
                            if(amenity.toLowerCase().includes('wifi')) icon = '<i class="fa fa-wifi"></i>';
                            else if(amenity.toLowerCase().includes('máy lạnh') || amenity.toLowerCase().includes('ac')) icon = '<i class="fa fa-snowflake-o"></i>';
                            else if(amenity.toLowerCase().includes('bãi đỗ xe') || amenity.toLowerCase().includes('parking')) icon = '<i class="fa fa-car"></i>';
                            else if(amenity.toLowerCase().includes('tủ lạnh') || amenity.toLowerCase().includes('fridge')) icon = '<i class="fa fa-refrigerator"></i>';
                            else if(amenity.toLowerCase().includes('tivi') || amenity.toLowerCase().includes('tv')) icon = '<i class="fa fa-tv"></i>';
                            return `<span class="amenity">${icon} ${amenity}</span>`;
                        }).join('') : '';

                    const roomDetails = [];
                    if(room.room_area) roomDetails.push(`<small class="text-muted me-3"><i class="fa fa-arrows-alt me-1"></i>${room.room_area}m²</small>`);
                    if(room.max_guests) roomDetails.push(`<small class="text-muted me-3"><i class="fa fa-users me-1"></i>Tối đa ${room.max_guests} người</small>`);
                    if(room.bed_count) roomDetails.push(`<small class="text-muted"><i class="fa fa-bed me-1"></i>${room.bed_count} giường</small>`);
                    
                    roomItem.innerHTML = `
                        <div class="img" style="width:40%;">
                            <img style="max-height:278px;min-height:217px" width="100%" 
                                 src="/storage/upload/${room.pimage || 'default.jpg'}" alt="room_primary_image">
                        </div>
                        <div class="conte mt-3 d-flex justify-content-between" style="width:60%">
                            <div class="info mx-2" style="width:72%">
                                <h3>${room.name}</h3>
                                <h4>Giá: <span style="color:#dfa974; font-size:1.2rem; font-weight:700">${Number(room.base_price).toLocaleString()} VND</span>/đêm</h4>
                                <h5>Loại phòng: <span style="color:#dfa974">${room.category?.name || 'Không phân loại'}</span></h5>
                                <div class="amenities-list">
                                    ${amenitiesHtml}
                                </div>
                                <p class="desc mt-1">${room.description || 'Không có mô tả'}</p>
                                ${roomDetails.length > 0 ? `<div class="room-details mt-2">${roomDetails.join('')}</div>` : ''}
                            </div>
                            <div style="width:28%;margin:auto 0;" class="d-flex flex-column align-items-center justify-content-center">
                                <a class="detail-btn" id="infoBTN" href="/roomdetail/${room.id}">Chi tiết</a>
                            </div>
                        </div>
                    `;
                    resultsSection.appendChild(roomItem);
                });
                
                // Update filter info
                const filterSection = document.querySelector('.filter-section p');
                if(filterSection) {
                    filterSection.innerHTML = `Tìm thấy <strong>${res.data.length}</strong> phòng phù hợp`;
                }
            } else {
                resultsSection.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fa fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Không tìm thấy kết quả phù hợp</h4>
                        <p class="text-muted">Vui lòng thử lại với bộ lọc khác</p>
                    </div>
                `;
                
                const filterSection = document.querySelector('.filter-section p');
                if(filterSection) {
                    filterSection.innerHTML = 'Không tìm thấy phòng phù hợp với tiêu chí tìm kiếm';
                }
            }
            
            // Render pagination
            if(res.last_page > 1) {
                renderPagination(res.current_page, res.last_page);
            }
        })
        .catch(err => {
            console.error("Lỗi khi áp dụng bộ lọc", err);
            resultsSection.innerHTML = `
                <div class="text-center py-5">
                    <i class="fa fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h4 class="text-danger">Có lỗi xảy ra</h4>
                    <p class="text-muted">Vui lòng thử lại sau</p>
                </div>
            `;
        });
}

function resetFilters() {
    // Reset all checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Reset price inputs
    document.getElementById('custom_price_min').value = '';
    document.getElementById('custom_price_max').value = '';
    
    // Apply filters to show all results
    applyFilters();
}

function renderPagination(current, last) {
    const pagDiv = document.querySelector('.pagination .room-pagination');
    if (!pagDiv) return;
    
    pagDiv.innerHTML = '';
    
    // Previous button
    if (current > 1) {
        pagDiv.innerHTML += `<a href="#" onclick="applyFilters(${current-1});return false;"><i class="fa fa-chevron-left"></i> Trước</a>`;
    }
    
    // Page numbers
    for (let i = 1; i <= last; i++) {
        pagDiv.innerHTML += `<a href="#" class="${i === current ? 'active' : ''}" onclick="applyFilters(${i});return false;">${i}</a>`;
    }
    
    // Next button
    if (current < last) {
        pagDiv.innerHTML += `<a href="#" onclick="applyFilters(${current+1});return false;">Sau <i class="fa fa-chevron-right"></i></a>`;
    }
}

// Validation form tìm kiếm đã được xử lý bởi BookingCalendarManager
// Không cần code validation cũ nữa
</script>

@endsection