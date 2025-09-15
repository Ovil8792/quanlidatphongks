/**
 * Booking Session Manager
 * Quản lý dữ liệu đặt phòng xuyên suốt các trang
 */
class BookingSessionManager {
    constructor() {
        this.sessionKey = 'booking_data';
        this.cookieKey = 'booking_data';
        this.cookieExpiry = 7; // 7 ngày
        this.lastSavedSnapshot = null; // tránh lưu thừa
        this.init();
    }

    /**
     * Khởi tạo
     */
    init() {
        this.loadFromStorage();
        this.setupFormListeners();
        this.autoSave();
    }

    /**
     * Lưu dữ liệu vào session storage và cookie
     */
    save(data) {
        try {
            const snapshot = JSON.stringify(data || {});
            if (this.lastSavedSnapshot === snapshot) {
                return true; // Không thay đổi, bỏ qua
            }
            // Lưu vào session storage
            sessionStorage.setItem(this.sessionKey, snapshot);
            
            // Lưu vào cookie
            this.setCookie(this.cookieKey, snapshot, this.cookieExpiry);
            
            // Gửi lên server để lưu session
            this.saveToServer(data);
            
            console.log('Booking data saved:', data);
            this.lastSavedSnapshot = snapshot;
            return true;
        } catch (error) {
            console.error('Error saving booking data:', error);
            return false;
        }
    }

    /**
     * Lấy dữ liệu từ storage
     */
    load() {
        try {
            // Ưu tiên session storage trước
            let data = sessionStorage.getItem(this.sessionKey);
            if (data) {
                return JSON.parse(data);
            }

            // Nếu không có, lấy từ cookie
            data = this.getCookie(this.cookieKey);
            if (data) {
                const parsed = JSON.parse(data);
                // Cập nhật lại session storage
                sessionStorage.setItem(this.sessionKey, data);
                return parsed;
            }

            return {};
        } catch (error) {
            console.error('Error loading booking data:', error);
            return {};
        }
    }

    /**
     * Cập nhật một phần dữ liệu
     */
    update(key, value) {
        const data = this.load();
        data[key] = value;
        this.save(data);
        return data;
    }

    /**
     * Xóa dữ liệu
     */
    clear() {
        try {
            sessionStorage.removeItem(this.sessionKey);
            this.deleteCookie(this.cookieKey);
            this.clearFromServer();
            console.log('Booking data cleared');
            return true;
        } catch (error) {
            console.error('Error clearing booking data:', error);
            return false;
        }
    }

    /**
     * Lấy dữ liệu từ server
     */
    async loadFromServer() {
        try {
            const response = await fetch('/api/booking-session/get');
            const result = await response.json();
            if (result.success) {
                this.save(result.data);
                return result.data;
            }
        } catch (error) {
            console.error('Error loading from server:', error);
        }
        return {};
    }

    /**
     * Lưu dữ liệu lên server
     */
    async saveToServer(data) {
        try {
            const response = await fetch('/api/booking-session/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                console.log('Data saved to server');
                // Đồng bộ vào temp user để lưu tiếp lần sau khi chưa đăng nhập
                try {
                    await fetch('/api/booking-session/save-temp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({ booking_data: data })
                    });
                } catch (e) {
                    console.warn('Temp user sync failed', e);
                }
            }
        } catch (error) {
            console.error('Error saving to server:', error);
        }
    }

    /**
     * Xóa dữ liệu từ server
     */
    async clearFromServer() {
        try {
            await fetch('/api/booking-session/clear', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
        } catch (error) {
            console.error('Error clearing from server:', error);
        }
    }

    /**
     * Tự động lưu khi có thay đổi
     */
    autoSave() {
        // Lưu dữ liệu nhanh hơn: mỗi ~800ms nếu có thay đổi
        setInterval(() => {
            const currentData = this.getCurrentFormData();
            if (currentData && Object.keys(currentData).length > 0) {
                this.save(currentData);
            }
        }, 800);
    }

    /**
     * Lấy dữ liệu hiện tại từ form
     */
    getCurrentFormData() {
        const data = {};
        
        // Lấy dữ liệu từ các input fields
        const fields = ['date_in', 'date_out', 'guest', 'room', 'custom_guest', 'room_id'];
        fields.forEach(field => {
            const element = document.getElementById(field) || document.querySelector(`[name="${field}"]`);
            if (element && element.value) {
                data[field] = element.value;
            }
        });

        // Lấy thông tin khách hàng nếu có
        const guestFields = ['guest_name', 'guest_phone', 'guest_email'];
        guestFields.forEach(field => {
            const element = document.getElementById(field) || document.querySelector(`[name="${field}"]`);
            if (element && element.value) {
                data[field] = element.value;
            }
        });

        return data;
    }

    /**
     * Điền dữ liệu vào form
     */
    fillForm(data) {
        if (!data || typeof data !== 'object') return;

        Object.keys(data).forEach(key => {
            const element = document.getElementById(key) || document.querySelector(`[name="${key}"]`);
            if (element) {
                if (element.type === 'radio') {
                    const radio = document.querySelector(`input[name="${key}"][value="${data[key]}"]`);
                    if (radio) radio.checked = true;
                } else if (element.type === 'checkbox') {
                    element.checked = data[key] === 'true' || data[key] === true;
                } else {
                    element.value = data[key];
                }

                // Trigger change event
                element.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });

        // Xử lý custom guest
        if (data.guest === 'custom' && data.custom_guest) {
            const customWrapper = document.getElementById('customGuestWrapper');
            if (customWrapper) {
                customWrapper.style.display = 'block';
            }
        }
    }

    /**
     * Thiết lập listeners cho form
     */
    setupFormListeners() {
        // Lắng nghe sự kiện thay đổi trên các form fields
        const formFields = document.querySelectorAll('input, select, textarea');
        formFields.forEach(field => {
            if (field.name && this.isBookingField(field.name)) {
                field.addEventListener('change', (e) => {
                    this.update(e.target.name, e.target.value);
                });

                field.addEventListener('input', (e) => {
                    // Debounce input events
                    clearTimeout(this.inputTimeout);
                    this.inputTimeout = setTimeout(() => {
                        this.update(e.target.name, e.target.value);
                    }, 500);
                });
            }
        });

        // Lắng nghe form submit
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                const formData = this.getCurrentFormData();
                this.save(formData);
            });
        });
    }

    /**
     * Kiểm tra xem field có phải là field đặt phòng không
     */
    isBookingField(fieldName) {
        const bookingFields = [
            'date_in', 'date_out', 'guest', 'room', 'custom_guest',
            'room_id', 'guest_name', 'guest_phone', 'guest_email'
        ];
        return bookingFields.includes(fieldName);
    }

    /**
     * Cookie helpers
     */
    setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    }

    getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    deleteCookie(name) {
        document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;`;
    }

    /**
     * Load dữ liệu từ storage khi khởi tạo
     */
    loadFromStorage() {
        const data = this.load();
        if (data && Object.keys(data).length > 0) {
            this.fillForm(data);
        }
    }

    /**
     * Kiểm tra dữ liệu có đầy đủ không
     */
    validateData() {
        const data = this.load();
        const required = ['date_in', 'date_out', 'room'];
        const missing = [];

        required.forEach(field => {
            if (!data[field]) {
                missing.push(field);
            }
        });

        // Kiểm tra guest hoặc custom_guest
        if (!data.guest && !data.custom_guest) {
            missing.push('guest');
        }

        return {
            isValid: missing.length === 0,
            missingFields: missing,
            data: data
        };
    }

    /**
     * Hiển thị thông báo validation
     */
    showValidationMessage() {
        const validation = this.validateData();
        if (!validation.isValid) {
            const missingText = validation.missingFields.join(', ');
            console.warn(`Missing required fields: ${missingText}`);
            return false;
        }
        return true;
    }
}

// Khởi tạo khi DOM ready
document.addEventListener('DOMContentLoaded', function() {
    window.bookingManager = new BookingSessionManager();
});

// Export để sử dụng ở nơi khác
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BookingSessionManager;
}
