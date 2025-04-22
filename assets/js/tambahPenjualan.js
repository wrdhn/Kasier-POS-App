document.addEventListener('DOMContentLoaded', function() {
    console.log('Script loaded');
    
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
        
        // Cek jika field bayar sudah terisi, hitung kembalian
        const bayar = parseFloat(document.getElementById('bayar_display').value) || 0;
        if (bayar > 0) {
            hitungKembalian();
        }
    }
    
    function hitungKembalian() {
        const total = parseFloat(document.getElementById('total_harga').value) || 0;
        const bayar = parseFloat(document.getElementById('bayar_display').value) || 0;
        const kembalian = bayar - total;
        
        document.getElementById('kembalian_display').value = formatRupiah(Math.max(0, kembalian));
        document.getElementById('kembalian_input').value = Math.max(0, kembalian);
    }
    
    function handleProdukSelect(select) {
        const row = select.closest('.produk-row');
        const option = select.options[select.selectedIndex];
        
        if (option.value) {
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
        } else {
            row.querySelector('.harga-produk').value = '';
            row.querySelector('.harga-hidden').value = '';
            row.querySelector('.stok-info').textContent = '';
            row.querySelector('.subtotal-display').value = '';
            row.querySelector('.subtotal-hidden').value = '';
            hitungTotal();
        }
    }
    
    // Event listener untuk produk select yang sudah ada
    document.querySelectorAll('.produk-select').forEach(select => {
        select.addEventListener('change', function() {
            handleProdukSelect(this);
        });
    });
    
    // Event listener untuk jumlah produk yang sudah ada
    document.querySelectorAll('.jumlah-produk').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('.produk-row');
            const stok = parseInt(row.querySelector('.produk-select').selectedOptions[0]?.dataset.stok || 0);
            
            if (parseInt(this.value) > stok) {
                this.value = stok;
                alert(`Stok maksimal: ${stok}`);
            }
            
            hitungSubtotal(row);
        });
    });
    
    // Event listener untuk hapus produk yang sudah ada
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
    
    // Event listener untuk input bayar
    const bayarInput = document.getElementById('bayar_display');
    if (bayarInput) {
        bayarInput.addEventListener('input', function() {
            console.log('Bayar input changed:', this.value);
            hitungKembalian();
        });
    }
    
    // Tambah produk baru
    const btnTambahProduk = document.getElementById('btnTambahProduk');
    if (btnTambahProduk) {
        btnTambahProduk.addEventListener('click', function() {
            const container = document.getElementById('produkContainer');
            const firstRow = document.querySelector('.produk-row');
            const newRow = firstRow.cloneNode(true);
            
            // Reset values di row baru
            newRow.querySelector('.produk-select').selectedIndex = 0;
            newRow.querySelector('.harga-produk').value = '';
            newRow.querySelector('.harga-hidden').value = '';
            newRow.querySelector('.jumlah-produk').value = 1;
            newRow.querySelector('.subtotal-display').value = '';
            newRow.querySelector('.subtotal-hidden').value = '';
            newRow.querySelector('.stok-info').textContent = '';
            
            // Add event listeners ke row baru
            newRow.querySelector('.produk-select').addEventListener('change', function() {
                handleProdukSelect(this);
            });
            
            newRow.querySelector('.jumlah-produk').addEventListener('input', function() {
                const row = this.closest('.produk-row');
                const stok = parseInt(row.querySelector('.produk-select').selectedOptions[0]?.dataset.stok || 0);
                
                if (parseInt(this.value) > stok) {
                    this.value = stok;
                    alert(`Stok maksimal: ${stok}`);
                }
                
                hitungSubtotal(row);
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
    }
    
    // Validasi form sebelum submit
    const form = document.getElementById('formPenjualan');
    if (form) {
        form.addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('.produk-row');
            const total = parseFloat(document.getElementById('total_harga').value) || 0;
            const bayar = parseFloat(document.getElementById('bayar_display').value) || 0;
            
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
            
            if (bayar < total) {
                e.preventDefault();
                alert('Pembayaran kurang! Minimal bayar: Rp ' + formatRupiah(total));
                return;
            }
            
            // Cek produk duplikat
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
    }
});