@extends('layout.main')

@section('main')

<div class="container mt-5">
    <div class="card shadow-sm">
        <div style="background-color:#dfa974" class="card-header text-dark">
            <h2 class="mb-0">Chỉnh sửa thông tin cá nhân</h2>
        </div>
        <div class="card-body">
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form action="{{ route('client.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Họ tên</label>
                    <input type="text" name="name" id="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <label for="diachi" class="form-label">Địa chỉ</label>
    <input type="text"
           name="diachi"
           id="diachi"
           class="form-control @error('diachi') is-invalid @enderror"
           value="{{ old('diachi', $user->address) }}">
    @error('diachi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
                
                  <label for="phone" class="form-label">Số điện thoại</label>
    <input type="tel"
           name="phone" max="11" min="10"
           id="phone"
           class="form-control @error('phone') is-invalid @enderror"
           value="{{ old('phone', $user->phone) }}">
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    
                <label class="form-label" for="email">Email</label>
                <input class="form-control" value="{{ old("email")??$user->email }}" type="email" name="email" id="email">
    @endif
                </div>
                <div class="buttons d-flex justify-content-center">
                     <button type="submit" class="btn btn-primary px-2">Lưu</button>
                <a href="{{ route('client.show') }}" class="btn btn-warning px-2">Hủy</a>
                </div>
               
            </form>
        </div>
    </div>
</div>
@endsection
