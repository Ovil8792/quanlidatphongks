@extends("admin.layout.main")

@section("main")
<a class="btn btn-primary" href="{{ route('admin.createhotel') }}">Thêm khách sạn</a>
          <div class="tables-wrapper">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-style mb-30">
                  <h6 class="mb-10">Danh sách khách sạn</h6>
                   <p style="display: none;" id="check">hotel</p>
                  <div class="table-wrapper table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>
                            <h6>ID</h6>
                          </th>
                          <th>
                            <h6>Tên</h6>
                          </th>
                          <th>
                            <h6>Địa chỉ</h6>
                          </th>
                          <th>
                            <h6>Số phòng</h6>
                          </th>
                          <th>
                            <h6>Ảnh</h6>
                          </th>
                            <th>
                                <h6>Mô tả</h6>
                            </th>
                          <th>
                            <h6>Hành động</h6>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($hotel as $h)
                        <tr>
                        <td>{{ $h->id }}</td>
                        <td>{{ $h->name }}</td>
                        <td>{{ $h->address }}</td>
                        <td>{{ $h->rooms}}</td>
                        <td>{{ $h->pimage }}</td>
                        <td>{{ $h->description }}</td>
                        <td>
                            <a href="{{ route('admin.edithotel',["id"=>$h->id]) }}" class="btn btn-primary">Sửa thông tin</a>

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

@endsection