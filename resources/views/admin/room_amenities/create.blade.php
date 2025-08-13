@extends("admin.layout.main")
@section("main")
@php
    $roomAmenityIds = isset($room) ? $room->amenities->pluck('id')->toArray() : [];
@endphp

@foreach($amenities as $amenity)
<div>
        
<label></label>
        <input class="form-control"
            type="checkbox"
            name="amenities[]"
            value="{{ $amenity->id }}"
            {{ in_array($amenity->id, $roomAmenityIds) ? 'checked' : '' }}
        >
        {{ $amenity->name }}
    <br>
</div>
@endforeach

@endsection