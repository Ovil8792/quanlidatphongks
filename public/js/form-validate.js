(function(){
    function attachValidation(form){
        if (!form) return;
        form.addEventListener('submit', function(e){
            const setErr = (name, msg) => { const el = form.querySelector(`[data-error-for="${name}"]`); if (el) { el.textContent = msg || ''; el.classList.toggle('d-none', !msg); } };
            const getVal = (name) => (form.querySelector(`[name="${name}"]`)?.value || '').trim();
            const clear = () => form.querySelectorAll('[data-error-for]').forEach(el => { el.textContent=''; el.classList.add('d-none'); });
            clear();

            let hasErr = false;
            const name = getVal('name');
            if (form.querySelector('[name="name"]')){
                if (!name){ setErr('name', 'Họ tên là bắt buộc'); hasErr = true; }
                else if (name.length > 255){ setErr('name', 'Họ tên không được quá 255 ký tự'); hasErr = true; }
            }
            const phone = getVal('phone');
            if (form.querySelector('[name="phone"]')){
                if (!phone){ setErr('phone', 'Số điện thoại là bắt buộc'); hasErr = true; }
                else if (!/^0[0-9]{9,10}$/.test(phone)){ setErr('phone', 'Số điện thoại không hợp lệ (định dạng: 0xxxxxxxxx)'); hasErr = true; }
            }
            const email = getVal('email');
            if (form.querySelector('[name="email"]')){
                if (!email){ setErr('email', 'Email là bắt buộc'); hasErr = true; }
                else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){ setErr('email', 'Email không hợp lệ'); hasErr = true; }
            }
            const dateIn = getVal('date_in');
            const dateOut = getVal('date_out');
            if (form.querySelector('[name="date_in"]')){
                if (!dateIn){ setErr('date_in', 'Ngày nhận phòng là bắt buộc'); hasErr = true; }
                else if (!/^\d{4}-\d{2}-\d{2}$/.test(dateIn)){ setErr('date_in', 'Ngày nhận phòng không hợp lệ'); hasErr = true; }
            }
            if (form.querySelector('[name="date_out"]')){
                if (!dateOut){ setErr('date_out', 'Ngày trả phòng là bắt buộc'); hasErr = true; }
                else if (!/^\d{4}-\d{2}-\d{2}$/.test(dateOut)){ setErr('date_out', 'Ngày trả phòng không hợp lệ'); hasErr = true; }
            }
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateIn) && /^\d{4}-\d{2}-\d{2}$/.test(dateOut)){
                if (!(new Date(dateOut) > new Date(dateIn))){ setErr('date_out', 'Ngày trả phòng phải sau ngày nhận phòng'); hasErr = true; }
            }
            const roomCount = getVal('room_count');
            if (form.querySelector('[name="room_count"]')){
                if (!roomCount){ setErr('room_count', 'Số phòng là bắt buộc'); hasErr = true; }
                else { const n = parseInt(roomCount,10); if (!(n>=1 && n<=10)){ setErr('room_count','Số phòng phải từ 1 đến 10'); hasErr = true; } }
            }
            if (hasErr) e.preventDefault();
        });
    }
    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('form').forEach(attachValidation);
    });
})();


