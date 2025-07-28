@extends("admin.layout.main")

@section("main")
<form action="{{ route('admin.updatehotel',['id'=>$hotel->id]) }}" method="POST" class="form">
    @csrf
    @method("PUT")
    <label class="form-label" for="name">Tên</label>
    <input class="form-control" type="text" value="{{ $hotel->name }}" name="name" id="name">
    <label class="form-label" for="address">Địa chỉ</label>
    <input class="form-control" type="text" value="{{ $hotel->address }}" name="address" id="address">
    <label class="form-label" for="rooms">Số lượng phòng</label>
    <input class="form-control" type="number" value="{{ $hotel->rooms }}" name="rooms" id="rooms">
    <label class="form-label" for="pimage">Ảnh</label>
    <input class="form-control" type="file" name="pimage" id="pimage">
    <label class="form-label" for="descr">Mô tả</label>
    <textarea class="form-control" name="descr" id="descr">{{ $hotel->description }}</textarea>
    <input type="submit" class="btn btn-primary" value="Sửa">
</form>

@endsection