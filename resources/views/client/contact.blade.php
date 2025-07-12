@extends("layout.main")

@section("main")
     <!-- Contact Section Begin -->
    <section class="contact-section spad">
         <p style="display:none;" id="current">contact</p>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="contact-text">
                        <h2>Thông tin liên hệ</h2>
                        <p>Quý khách vui lòng nhập thông tin liên hệ để chúng tôi có thể kịp thời hỗ trợ</p>
                        
                        <p>Hoặc bạn có thể trực tiếp đến với River New của chúng tôi theo địa chỉ dưới đây.</p>
                        <p>Chúng tôi luôn có mặt hỗ trợ bạn 24/7!</p>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="c-o">Địa chỉ:</td>
                                    <td>271 Lê Thánh Tông, Máy Chai, Ngô Quyền, Hải Phòng</td>
                                </tr>
                                <tr>
                                    <td class="c-o">SĐT:</td>
                                    <td>(12) 345 67890</td>
                                </tr>
                                <tr>
                                    <td class="c-o">Email:</td>
                                    <td>info.colorlib@gmail.com</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-7 offset-lg-1">
                    <form action="{{ route("client.postcontact") }}" method="POST" class="contact-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <input name="name" type="text" placeholder="Tên của bạn">
                            </div>
                            <div class="col-lg-6">
                                <input name="email" type="text" placeholder="Email của bạn">
                            </div>
                            <div class="col-lg-12">
                                <textarea name="message" placeholder="Tin nhắn..."></textarea>
                                <button type="submit">Gửi ngay</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d398.95900443214293!2d106.70914103081836!3d20.865508881776762!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314a7bf67d69c9c7%3A0x4341c6cef1813f18!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e1!3m2!1svi!2s!4v1750663705008!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->
@endsection
