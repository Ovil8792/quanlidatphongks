@extends("admin.layout.main")
@section("main")
<div class="container">
    <h1>Thống kê</h1>
    <div class="row">
        <p class="title-statistical" style="text-decoration: underline;">Tổng Quan:</p>
        <form class="col-md-3" autocomplete="off" action="">
            @csrf
            <div class="col-md-6">
                <p>Từ ngày: <input type="text" id="datepicker" class="form-control"></p>
            </div>
            <div class="col-md-6">
                <p>Đến ngày: <input type="text" id="datepicker2" class="form-control"></p>
            </div>
            <div class="">
                <p> <button type="submit" id="statistical-filter" class="btn btn-primary">Tìm kiếm</button></p>
            </div>
        </form>
        <div class="col-md-9">
            <div id="result" style="height: 400px;"></div>
        </div>
    </div>

</div>
@endsection