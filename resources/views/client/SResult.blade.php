@extends('layout.main')

@section("main")
<style>
    .room-item{
        border:1px solid lightgray;
        margin-bottom: 10px!important;
        padding:5px 30px 5px 3px;
    }
    #customGuest {
        display: none;
        margin-top: 10px;
        height: 40px;
        width: 100%;
    }
    .sidebar {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
        height: fit-content;
    }
    .sidebar h6 {
        margin-bottom: 15px;
        font-weight: bold;
    }
    .sidebar .filter-group {
        margin-bottom: 20px;
    }
    .sidebar .filter-group label {
        margin-left: 10px;
    }
    .ad-section {
        background: #f1f1f1;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
    }
    .results-section {
        padding: 20px;
    }
    @media only screen and (max-width: 768px){
        #bookInfo {
        padding: 10px 5px;
        font-size: 15px;
        flex-direction: column;
        align-items: stretch;
       
    }
    #bookInfo form.d-flex {
        flex-direction: column !important;
        gap: 10px;
    }
    #bookInfo .check-date,
    #bookInfo .select-option {
        width: 100%;
        margin-bottom: 10px;
    }
    #bookInfo input,
    #bookInfo select {
        width: 100%;
        font-size: 15px;
    }
    }
    body#bd label{
        color:black!important;
        font-weight: bold;
        font-size:medium;
    }
</style>
{{-- {{ dd($results) }} --}}
<div class="container">
    @if(session('location_selected'))
    <div style="background-color:#dea97391!important;" id="bookInfo" class="alert alert-info booking-form">
        <form class="d-flex justify-content-center" action="{{ route('search.pending') }}" method="GET">
            <div class="check-date">
                <label class="d-flex justify-content-center" for="date-in">{{ __('messages.cin') }}:</label>
                <input value="{{ $checkin }}" type="text" id="date-in" name="date_in">
                <i style="right: 18px!important; top: 48px!important;" class="icon_calendar"></i>
            </div>
            <div class="check-date">
                <label class="d-flex justify-content-center" for="date-out">{{ __('messages.cout') }}:</label>
                <input value="{{ $checkout }}" type="text" id="date-out" name="date_out">
                <i style="right: 18px!important; top: 48px!important;" class="icon_calendar"></i>
            </div>
            <div class="select-option">
                <label class="d-flex justify-content-center" for="guest">{{ __('messages.guest') }}:</label>
                <select id="guest" name="guest">
                    <option {{ $people=="2l1n"?'selected':"" }} value="2l1n">2 lớn+1 nhỏ</option>
                    <option {{ $people=="4l3n"?'selected':"" }} value="4l3n">4 lớn 3 nhỏ</option>
                    <option {{ $people=="custom"?'selected':"" }} value="custom">Nhập riêng</option>
                </select>
                <input class="form-control" value="{{ $customGuest }}" type="text" id="customGuest" name="custom_guest"
                    placeholder="Nhập số người" min="1" />
            </div>
            <div class="select-option">
                <label class="d-flex justify-content-center" for="room">{{ __('messages.rn') }}:</label>
                <select id="room" name="room">
                    <option {{ $roomCount==1?'selected':"" }} value="1">1 Phòng</option>
                    <option {{ $roomCount==2?'selected':"" }} value="2">2 Phòng</option>
                    <option {{ $roomCount==3?'selected':"" }} value="3">3 Phòng</option>
                    <option {{ $roomCount==4?'selected':"" }} value="4">4 Phòng</option>
                    <option {{ $roomCount==10?'selected':"" }} value="10">10 Phòng</option>
                </select>
            </div>
            <div class="select-option">
                <label class="d-flex justify-content-center" for="search">Vị trí:</label>
                <div style="position: relative;">
                    <input value="{{ session('location_selected') }}" id="search" name="keyword"
                        style="border-radius: 2px; border: 1px solid #ebebeb; height: 50px; line-height: 50px; outline: none; padding-left: 20px; width: 100%; float: none;"
                        type="search" class="form-control" placeholder="Tìm kiếm...">
                    <input type="hidden" name="selected_location" id="selectedLocation">
                    <ul id="suggestions"
                        style="list-style: none; margin: 0; padding: 0; border: 1px solid #ebebeb; border-top: none; max-height: 200px; overflow-y: auto; width: 100%; position: absolute; top: 100%; left: 0; background: #fff; z-index: 1000; display: none;">
                    </ul>
                </div>
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
                <h6>Bộ lọc tìm kiếm</h6>
                <div class="filter-group">
                    <h6>Loại phòng</h6>
                    @foreach (session("category") as $k)
                    <div>
                        <input type="checkbox" name="room_type[]" value="{{ $k->id }}" id="room_type_{{ $k->id }}">
                        <label for="room_type_{{ $k->id }}">{{ $k->name }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="filter-group">
                    <h6>Khoảng giá</h6>
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
                </div>
                <div class="filter-group">
                    <h6>Tiện ích</h6>
                    <div>
                        <input type="checkbox" name="amenities[]" value="wifi" id="amenity_wifi">
                        <label for="amenity_wifi">Wifi miễn phí</label>
                    </div>
                    <div>
                        <input type="checkbox" name="amenities[]" value="pool" id="amenity_pool">
                        <label for="amenity_pool">Hồ bơi</label>
                    </div>
                    <div>
                        <input type="checkbox" name="amenities[]" value="breakfast" id="amenity_breakfast">
                        <label for="amenity_breakfast">Bữa sáng miễn phí</label>
                    </div>
                </div>
                <button class="btn btn-primary w-100" onclick="applyFilters()">Áp dụng bộ lọc</button>
            </div>
        </div>
        <!-- Results Section (col-md-6) -->
        <style>
            #infoBTN:hover{
                box-shadow:0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            }
        </style>
        <div class="col-md-9 results-section">
            @if($results)
            @foreach($results as $room)
            <div class="room-item d-flex px-3 shadow-sm py-3">
                <div class="img" style="width:40%;">
                    <img style="max-height:278px;min-height:217px" width="100%" src="{{ asset(url("/storage/upload/$room->pimage")) }}" alt="room_primary_image">
                </div>
                <div class="conte mt-3 d-flex justify-content-between" style="width:60%">
                    <div class="info mx-2" style="width:72%">
                        <h3>{{ $room->name }}</h3>
                        <h4>Giá: <span style="color:#dea973;">{{ number_format($room->base_price,0,",",".") }}VND</span>/đêm</h4>
                        <h5>Hiện đang còn phòng tại: {{ $room->hotel_name }}</h5>
                        <p>Các tiện ích: {{ $room->amenities }}</p>
                        <p class="mt-1" style="max-width:400px;white-space: nowrap;overflow:hidden;text-overflow:ellipsis; ">{{ $room->description }}</p>
                    </div>
                    
                    <div style="width:28%;margin:7rem 0;" class="d-flex justify-content-around">
                        <a id="infoBTN" href="{{ route('client.roomdetail',['id'=>$room->id]) }}"
                     style="display: inline-block;  font-size: 13px;border-radius:6px;
                    font-weight: 700;padding: 15px 3px;background: #dfa974;color: #ffffff;
                    text-transform: uppercase;letter-spacing: 2px;">Chi tiết</a>
                    </div>
                
                </div>
                </div>
            @endforeach
            @else
            <span class="bg-warning-emphasis">Không tìm thấy kết quả phù hợp</span>
            @endif
        </div>

        <!-- Advertisement Section (col-md-3) -->
        {{-- <div class="col-md-3">
            <div class="ad-section">
                <h6>Quảng cáo</h6>
                <p>Đặt tour du lịch hôm nay, nhận ưu đãi 20%!</p>
                <a href="#" class="btn btn-success">Khám phá ngay</a>
            </div>
        </div> --}}
    </div>
</div>
<div class="py-5" style="position:relative;width:100%;">
    <div class="pagination" style="position: absolute;
        transform: translate(-50%, -50%);
        left: 50%;">
                <div class="room-pagination">
                    <a href="#">1</a>
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

document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById('search');
    const suggestionsBox = document.getElementById('suggestions');
    const selectedLocation = document.getElementById('selectedLocation');
    let timeout;
    let updateCookieTimeout; // thêm timeout để trì hoãn cập nhật cookie

    // Đóng suggestions nếu click ngoài
    document.addEventListener('click', (e) => {
        if (!suggestionsBox.contains(e.target) && e.target !== searchInput) {
            suggestionsBox.style.display = 'none';
        }
    });

    searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        const query = searchInput.value.trim();

        if (query.length < 1) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.style.display = 'none';
            return;
        }

        timeout = setTimeout(() => {
            fetch(`/sapi?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsBox.innerHTML = '';
                    const { locations } = data;

                    if (locations.length === 0) {
                        suggestionsBox.style.display = 'none';
                        return;
                    }

                    const locHeader = document.createElement('li');
                    locHeader.textContent = 'Vị trí gợi ý';
                    locHeader.style.fontWeight = 'bold';
                    locHeader.style.padding = '8px';
                    suggestionsBox.appendChild(locHeader);

                    locations.forEach(loc => {
                        const li = document.createElement('li');
                        li.textContent = loc;
                        li.style.padding = '8px';
                        li.style.cursor = 'pointer';
                        li.style.borderBottom = '1px solid #eee';
                        li.addEventListener('click', () => {
                            searchInput.value = loc;
                            selectedLocation.value = loc;
                            suggestionsBox.innerHTML = '';
                            suggestionsBox.style.display = 'none';

                            // ⏱ Trì hoãn 2 giây để gửi cookie sau khi chọn gợi ý
                            clearTimeout(updateCookieTimeout);
                            updateCookieTimeout = setTimeout(() => {
                                try {
                                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

                                    axios.post('/update-booking-cookie', {
                                        selected_location: loc
                                    }).then(res => {
                                        console.log('Cookie updated:', res.data);
                                    }).catch(err => {
                                        console.error('Cookie update error:', err);
                                    });
                                } catch (err) {
                                    console.error('CSRF token not found or other error:', err);
                                }
                            }, 2000); // ⏳ sau 2 giây
                        });
                        suggestionsBox.appendChild(li);
                    });

                    suggestionsBox.style.display = 'block';
                });
        }, 300);
    });
});


function applyFilters() {
    const roomTypes = Array.from(document.querySelectorAll('input[name="room_type[]"]:checked')).map(el => el.value);
    const priceRanges = Array.from(document.querySelectorAll('input[name="price_range[]"]:checked')).map(el => el.value);
    const amenities = Array.from(document.querySelectorAll('input[name="amenities[]"]:checked')).map(el => el.value);

    const data = {
        room_types: roomTypes,
        price_ranges: priceRanges,
        amenities: amenities,
        cin: document.getElementById("date-in")?.value,
        cout: document.getElementById("date-out")?.value,
        pple: document.getElementById("guest")?.value,
        rms: document.getElementById("room")?.value,
        cppl: document.getElementById("customGuest")?.value,
        keyword: document.getElementById("search")?.value
    };

    axios.post('/api/filter-rooms', data)
        .then(response => {
            // Cập nhật danh sách kết quả
            const resultsSection = document.querySelector('.results-section');
            resultsSection.innerHTML = '';
            if (response.data.results.length > 0) {
                response.data.results.forEach(room => {
                    const roomItem = document.createElement('div');
                    roomItem.className = 'room-item';
                    roomItem.innerHTML = `
                        <p>${room.id}-${room.name}</p>
                        <a href="/client/roomdetail/${room.id}" class="btn btn-primary">Chi tiết phòng</a>
                    `;
                    resultsSection.appendChild(roomItem);
                });
            } else {
                resultsSection.innerHTML = '<span class="bg-warning-emphasis">Không tìm thấy kết quả phù hợp</span>';
            }
        })
        .catch(err => {
            console.error("Lỗi khi áp dụng bộ lọc", err);
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Không thể tải kết quả. Vui lòng thử lại!',
            });
        });
}
</script>

@endsection