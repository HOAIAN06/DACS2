/* ====== LOCATION SELECTOR - CHECKOUT ====== */

document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province-select');
    const wardSelect = document.getElementById('ward-select');

    // Load provinces on page load
    loadProvinces();

    // When province changes, load wards
    provinceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const provinceCode = selectedOption.dataset.code; // Lấy code từ dataset
        
        if (provinceCode) {
            loadWards(provinceCode);
        } else {
            resetWardSelect();
        }
    });

    /**
     * Load all provinces from API
     */
    function loadProvinces() {
        fetch('/api/provinces')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateProvinceSelect(data.data);
                }
            })
            .catch(error => {
                console.error('Error loading provinces:', error);
            });
    }

    /**
     * Populate province select with data
     */
    function populateProvinceSelect(provinces) {
        provinceSelect.innerHTML = '<option value="">-- Chọn Tỉnh/Thành Phố --</option>';
        
        provinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province.name; // Dùng name thay vì code
            option.textContent = province.name;
            option.dataset.code = province.code; // Giữ code để load wards
            provinceSelect.appendChild(option);
        });
    }

    /**
     * Load wards by province code
     */
    function loadWards(provinceCode) {
        // Show loading state
        wardSelect.innerHTML = '<option value="">Đang tải...</option>';
        wardSelect.disabled = true;

        fetch(`/api/wards/${provinceCode}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateWardSelect(data.data);
                }
            })
            .catch(error => {
                console.error('Error loading wards:', error);
                wardSelect.innerHTML = '<option value="">Lỗi khi tải dữ liệu</option>';
            });
    }

    /**
     * Populate ward select with data
     */
    function populateWardSelect(wards) {
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
        
        if (wards.length === 0) {
            wardSelect.innerHTML = '<option value="">Không có dữ liệu</option>';
            wardSelect.disabled = true;
            return;
        }

        wards.forEach(ward => {
            const option = document.createElement('option');
            option.value = ward.full_name; // Dùng full_name thay vì code
            option.textContent = ward.full_name;
            wardSelect.appendChild(option);
        });

        wardSelect.disabled = false;
    }

    /**
     * Reset ward select to initial state
     */
    function resetWardSelect() {
        wardSelect.innerHTML = '<option value="">-- Chọn Tỉnh/Thành Phố trước --</option>';
        wardSelect.disabled = true;
    }
});
