@extends("admin.layout.main")
@section("main")
<p style="display: none;" id="check">ameni</p>
<a href="#" class="btn btn-primary">Thêm tiện ích</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Cr at</th>
            <th>UPD at</th>
        </tr>
    </thead>
    <tbody>
        @foreach($amenities as $amenity)
        <tr>
            <td>{{ $amenity->id }}</td>
            <td>{{ $amenity->name }}</td>
            <td>{{ $amenity->created_at }}</td>
            <td>{{ $amenity->updated_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection