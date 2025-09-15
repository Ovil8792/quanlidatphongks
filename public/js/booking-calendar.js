/**
 * Booking Calendar Manager - Quản lý lịch đặt phòng đồng bộ
 * Xử lý logic chung cho tất cả các form đặt phòng
 */

class BookingCalendarManager {
    constructor() {
        this.minAdvanceTime = 30; // Thời gian tối thiểu trước khi đặt phòng (phút)
        this.maxAdvanceDays = 365; // Số ngày tối đa có thể đặt trước
        this.init();
    }

    init() {
        this.setupDateInputs();
        this.setupValidation();
        this.setupFlatpickr();
        this.setupFormHandlers();
    }

    /**
     * Thiết lập các input ngày tháng
     */
    setupDateInputs() {
        // Tìm tất cả các input ngày trong trang
        this.dateInputs = document.querySelectorAll('input[id*="date-in"], input[id*="date-out"], input[id*="checkin"], input[id*="checkout"]');
        
        // Thiết lập min date cho tất cả input
        this.setMinDates();
        
        // Thiết lập event listeners
        this.dateInputs.forEach(input => {
            if (input.id.includes('date-in') || input.id.includes('checkin')) {
                input.addEventListener('change', (e) => this.handleCheckinChange(e));
            } else if (input.id.includes('date-out') || input.id.includes('checkout')) {
                input.addEventListener('change', (e) => this.handleCheckoutChange(e));
            }
        });
    }

    /**
     * Thiết lập ngày tối thiểu cho tất cả input
     */
    setMinDates() {
        const now = new Date();
        const minDate = new Date(now.getTime() + (this.minAdvanceTime * 60 * 1000));
        
        // Format cho input type="date"
        const minDateStr = minDate.toISOString().split('T')[0];
        // Format cho input type="datetime-local"
        const minDateTimeStr = minDate.toISOString().slice(0, 16);
        
        this.dateInputs.forEach(input => {
            if (input.type === 'date') {
                input.min = minDateStr;
            } else if (input.type === 'datetime-local') {
                input.min = minDateTimeStr;
            }
        });
    }

    /**
     * Xử lý khi thay đổi ngày nhận phòng
     */
    handleCheckinChange(event) {
        const checkinInput = event.target;
        const checkinValue = checkinInput.value;
        
        if (!checkinValue) return;
        
        // Tìm input checkout tương ứng
        const checkoutInput = this.findCheckoutInput(checkinInput);
        if (!checkoutInput) return;
        
        // Thiết lập min cho checkout
        if (checkinInput.type === 'date') {
            const nextDay = new Date(checkinValue);
            nextDay.setDate(nextDay.getDate() + 1);
            checkoutInput.min = nextDay.toISOString().split('T')[0];
        } else if (checkinInput.type === 'datetime-local') {
            const nextDay = new Date(checkinValue);
            nextDay.setDate(nextDay.getDate() + 1);
            checkoutInput.min = nextDay.toISOString().slice(0, 16);
        }
        
        // Reset checkout nếu không hợp lệ
        if (checkoutInput.value && checkoutInput.value <= checkinValue) {
            checkoutInput.value = '';
        }
        
        // Trigger change event để cập nhật UI
        checkoutInput.dispatchEvent(new Event('change'));
    }

    /**
     * Xử lý khi thay đổi ngày trả phòng
     */
    handleCheckoutChange(event) {
        const checkoutInput = event.target;
        const checkoutValue = checkoutInput.value;
        
        if (!checkoutValue) return;
        
        // Tìm input checkin tương ứng
        const checkinInput = this.findCheckinInput(checkoutInput);
        if (!checkinInput) return;
        
        const checkinValue = checkinInput.value;
        
        // Validation: checkout phải sau checkin
        if (checkinValue && checkoutValue <= checkinValue) {
            this.showError('Ngày trả phòng phải sau ngày nhận phòng!');
            checkoutInput.value = '';
            return;
        }
        
        // Validation: không được đặt quá xa trong tương lai
        const checkoutDate = new Date(checkoutValue);
        const maxDate = new Date();
        maxDate.setDate(maxDate.getDate() + this.maxAdvanceDays);
        
        if (checkoutDate > maxDate) {
            this.showError(`Không thể đặt phòng quá ${this.maxAdvanceDays} ngày trong tương lai!`);
            checkoutInput.value = '';
            return;
        }
        
        // Cập nhật UI nếu có function tính toán
        this.updateBookingInfo();
    }

    /**
     * Tìm input checkout tương ứng với checkin
     */
    findCheckoutInput(checkinInput) {
        const id = checkinInput.id;
        if (id.includes('date-in')) {
            return document.getElementById(id.replace('date-in', 'date-out'));
        } else if (id.includes('checkin')) {
            return document.getElementById(id.replace('checkin', 'checkout'));
        }
        return null;
    }

    /**
     * Tìm input checkin tương ứng với checkout
     */
    findCheckinInput(checkoutInput) {
        const id = checkoutInput.id;
        if (id.includes('date-out')) {
            return document.getElementById(id.replace('date-out', 'date-in'));
        } else if (id.includes('checkout')) {
            return document.getElementById(id.replace('checkout', 'checkin'));
        }
        return null;
    }

    /**
     * Thiết lập Flatpickr cho các input
     */
    setupFlatpickr() {
        // Kiểm tra xem Flatpickr có sẵn không
        if (typeof flatpickr === 'undefined') return;
        
        // Thiết lập cho từng loại input
        this.setupDateInputsFlatpickr();
        // Không dùng chọn thời gian, chỉ ngày
    }

    /**
     * Thiết lập Flatpickr cho input type="date"
     */
    setupDateInputsFlatpickr() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        
        dateInputs.forEach(input => {
            const isCheckin = input.id.includes('date-in') || input.id.includes('checkin');
            const isCheckout = input.id.includes('date-out') || input.id.includes('checkout');
            
            if (isCheckin || isCheckout) {
                const config = {
                    // Giá trị input giữ chuẩn Y-m-d để backend nhận đúng
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d-m-Y",
                    locale: "vn",
                    minDate: this.getMinDate(),
                    maxDate: this.getMaxDate(),
                    disableMobile: false,
                    allowInput: true,
                    clickOpens: true,
                    onChange: (selectedDates, dateStr, instance) => {
                        if (isCheckin) {
                            this.handleCheckinChange({ target: input });
                        } else if (isCheckout) {
                            this.handleCheckoutChange({ target: input });
                        }
                    }
                };
                
                flatpickr(input, config);
            }
        });
    }

    /**
     * Lấy ngày tối thiểu có thể chọn
     */
    getMinDate() {
        const now = new Date();
        return new Date(now.getTime() + (this.minAdvanceTime * 60 * 1000));
    }

    /**
     * Lấy ngày tối đa có thể chọn
     */
    getMaxDate() {
        const now = new Date();
        return new Date(now.getTime() + (this.maxAdvanceDays * 24 * 60 * 60 * 1000));
    }

    /**
     * Thiết lập validation cho form
     */
    setupValidation() {
        const forms = document.querySelectorAll('form[id*="searchForm"], form[id*="bookingForm"]');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => this.validateForm(e));
        });
    }

    /**
     * Validation form trước khi submit
     */
    validateForm(event) {
        const form = event.target;
        const checkinInput = form.querySelector('input[id*="date-in"], input[id*="checkin"]');
        const checkoutInput = form.querySelector('input[id*="date-out"], input[id*="checkout"]');
        
        if (!checkinInput || !checkoutInput) return true;
        
        const checkinValue = checkinInput.value;
        const checkoutValue = checkoutInput.value;
        
        // Kiểm tra đã chọn đủ ngày chưa
        if (!checkinValue || !checkoutValue) {
            this.showError('Vui lòng chọn đầy đủ ngày nhận phòng và ngày trả phòng!');
            event.preventDefault();
            return false;
        }
        
        // Kiểm tra checkout có sau checkin không
        if (checkoutValue <= checkinValue) {
            this.showError('Ngày trả phòng phải sau ngày nhận phòng!');
            event.preventDefault();
            return false;
        }
        
        // Kiểm tra thời gian tối thiểu
        const now = new Date();
        const minTime = new Date(now.getTime() + (this.minAdvanceTime * 60 * 1000));
        const checkinDate = new Date(checkinValue);
        
        if (checkinDate < minTime) {
            this.showError(`Phải đặt phòng trước ít nhất ${this.minAdvanceTime} phút!`);
            event.preventDefault();
            return false;
        }
        
        return true;
    }

    /**
     * Thiết lập handlers cho form
     */
    setupFormHandlers() {
        // Tự động cập nhật min date khi thay đổi checkin
        document.addEventListener('change', (e) => {
            if (e.target.id && (e.target.id.includes('date-in') || e.target.id.includes('checkin'))) {
                this.handleCheckinChange(e);
            }
        });
        
        // Tự động cập nhật UI khi thay đổi checkout
        document.addEventListener('change', (e) => {
            if (e.target.id && (e.target.id.includes('date-out') || e.target.id.includes('checkout'))) {
                this.handleCheckoutChange(e);
            }
        });
    }

    /**
     * Cập nhật thông tin đặt phòng
     */
    updateBookingInfo() {
        // Tìm và cập nhật các element hiển thị thông tin
        const infoElements = document.querySelectorAll('[id*="nights_display"], [id*="total_price_display"]');
        
        if (infoElements.length > 0) {
            // Nếu có function calculateTotal, gọi nó
            if (typeof calculateTotal === 'function') {
                calculateTotal();
            }
        }
    }

    /**
     * Hiển thị thông báo lỗi
     */
    showError(message) {
        // Sử dụng SweetAlert2 nếu có
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: message,
                confirmButtonText: 'Đóng'
            });
        } else {
            // Fallback về alert thông thường
            alert(message);
        }
    }

    /**
     * Hiển thị thông báo thành công
     */
    showSuccess(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: message,
                confirmButtonText: 'Đóng'
            });
        } else {
            alert(message);
        }
    }

    /**
     * Reset tất cả input ngày
     */
    resetAllDates() {
        this.dateInputs.forEach(input => {
            input.value = '';
        });
        this.setMinDates();
    }

    /**
     * Lấy thông tin đặt phòng hiện tại
     */
    getCurrentBookingInfo() {
        const info = {};
        
        this.dateInputs.forEach(input => {
            if (input.id.includes('date-in') || input.id.includes('checkin')) {
                info.checkin = input.value;
            } else if (input.id.includes('date-out') || input.id.includes('checkout')) {
                info.checkout = input.value;
            }
        });
        
        return info;
    }
}

// Khởi tạo khi DOM ready
document.addEventListener('DOMContentLoaded', function() {
    window.bookingCalendar = new BookingCalendarManager();
});

// Export để sử dụng ở nơi khác
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BookingCalendarManager;
}
