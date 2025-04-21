document.addEventListener('DOMContentLoaded', function() {
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }
    
    function hitungSubtotal(row) {
        const harga = parseFloat(row.querySelector('.harga-hidden').value) || 0;
        const jumlah = parseInt(row.querySelector('.jumlah-produk').value) || 0;
        const subtotal = harga * jumlah;
        
        row.querySelector('.subtotal-display').value = formatRupiah(subtotal);
        row.querySelector('.subtotal-hidden').value = subtotal;
        
        hitungTotal();
    }
    
    function hitungTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-hidden').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        
        document.getElementById('total_display').value = formatRupiah(total);
        document.getElementById('total_harga').value = total;
    }
    
    function handleProdukSelect(select) {
        const row = select.closest('.produk-row');
        const option = select.options[select.selectedIndex];
        const harga = option.dataset.harga;
        const stok = option.dataset.stok;
        
        row.querySelector('.harga-produk').value = formatRupiah(harga);
        row.querySelector('.harga-hidden').value = harga;
        row.querySelector('.stok-info').textContent = `Tersedia: ${stok}`;
        
        const jumlahInput = row.querySelector('.jumlah-produk');
        jumlahInput.max = stok;
        
        if (parseInt(jumlahInput.value) > parseInt(stok)) {
            jumlahInput.value = stok;
        }
        
        hitungSubtotal(row);
    }
    
    document.querySelectorAll('.produk-select').forEach(select => {
        select.addEventListener('change', function() {
            handleProdukSelect(this);
        });
    });
    
    document.querySelectorAll('.jumlah-produk').forEach(input => {
        input.addEventListener('input', function() {
            hitungSubtotal(this.closest('.produk-row'));
        });
    });
    
    document.querySelectorAll('.hapus-produk').forEach(button => {
        button.addEventListener('click', function() {
            if (document.querySelectorAll('.produk-row').length > 1) {
                this.closest('.produk-row').remove();
                hitungTotal();
            } else {
                alert('Minimal harus ada 1 produk!');
            }
        });
    });
    
    document.getElementById('btnTambahProduk').addEventListener('click', function() {
        const container = document.getElementById('produkContainer');
        const newRow = document.querySelector('.produk-row').cloneNode(true);
        
        newRow.querySelector('.produk-select').selectedIndex = 0;
        newRow.querySelector('.harga-produk').value = '';
        newRow.querySelector('.harga-hidden').value = '';
        newRow.querySelector('.jumlah-produk').value = 1;
        newRow.querySelector('.subtotal-display').value = '';
        newRow.querySelector('.subtotal-hidden').value = '';
        newRow.querySelector('.stok-info').textContent = '';
        
        newRow.querySelector('.produk-select').addEventListener('change', function() {
            handleProdukSelect(this);
        });
        
        newRow.querySelector('.jumlah-produk').addEventListener('input', function() {
            hitungSubtotal(this.closest('.produk-row'));
        });
        
        newRow.querySelector('.hapus-produk').addEventListener('click', function() {
            if (document.querySelectorAll('.produk-row').length > 1) {
                this.closest('.produk-row').remove();
                hitungTotal();
            } else {
                alert('Minimal harus ada 1 produk!');
            }
        });
        
        container.appendChild(newRow);
    });
    
    document.getElementById('formPenjualan').addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('.produk-row');
        const total = parseFloat(document.getElementById('total_harga').value) || 0;
        
        if (rows.length === 0) {
            e.preventDefault();
            alert('Harap tambahkan minimal 1 produk!');
            return;
        }
        
        if (total <= 0) {
            e.preventDefault();
            alert('Total harga harus lebih dari 0!');
            return;
        }
            
        const productIds = [];
        let hasDuplicate = false;
        
        rows.forEach(row => {
            const select = row.querySelector('.produk-select');
            const productId = select.value;
            
            if (productId && productIds.includes(productId)) {
                hasDuplicate = true;
            } else if (productId) {
                productIds.push(productId);
            }
        });
        
        if (hasDuplicate) {
            e.preventDefault();
            alert('Terdapat produk duplikat! Harap gabungkan jumlahnya dalam satu baris.');
            return;
        }
    });
});
