@extends("admin.layout.main")

@section("main")
          <div class="tables-wrapper">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-style mb-30">
                  <h6 class="mb-10">Danh sách đánh giá của phòng {{ $id.".".$rn->name }}</h6>
                   <p style="display: none;" id="check">review</p>
                  <div class="table-wrapper table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>
                            <h6>ID</h6>
                          </th>
                          <th>
                            <h6>Bình luận</h6>
                          </th>
                          <th>
                            <h6>Người dùng</h6>
                          </th>
                          <th>
                            <h6>Phòng</h6>
                          </th>
                          <th>
                            <h6>Đánh giá</h6>
                          </th>
                          <th>
                            <h6>Tạo ngày</h6>
                          </th>
                          <th>
                            <h6>Sửa ngày</h6>
                          </th>
                        <th>
                            <h6>Hành động</h6>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($rv as $h)
                        <tr>
                        <td>{{ $h->id }}</td>
                        <td>{{ $h->comment }}</td>
                        <td>{{ "$h->userid-$h->usern" }}</td>
                        <td>{{ "$h->roomid-$h->roomn" }}</td>
                        <td>{{ $h->rating }}</td>
                        <td>{{ $h->created_at }}</td>
                        <td>{{ $h->updated_at }}</td>
                        <td>
                            <a href="#" class="btn btn-primary">a</a>

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