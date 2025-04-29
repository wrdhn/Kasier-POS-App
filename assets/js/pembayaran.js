document.addEventListener('DOMContentLoaded', function() {
    // Format number to Indonesian Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }
    
    // Parse Rupiah format to number
    function parseRupiah(rupiahStr) {
        return parseInt(rupiahStr.replace(/\D/g, '')) || 0;
    }
    
    const totalHarga = parseFloat(document.getElementById('total_harga').value);
    const jumlahBayarDisplay = document.getElementById('jumlah_bayar_display');
    const jumlahBayarHidden = document.getElementById('jumlah_bayar');
    const kembalianDisplay = document.getElementById('kembalian_display');
    const kembalianHidden = document.getElementById('kembalian');
    const btnBayar = document.getElementById('btn-bayar');
    
    // Handle Jumlah Bayar Input
    jumlahBayarDisplay.addEventListener('input', function() {
        const value = parseRupiah(this.value);
        
        // Format display value
        this.value = formatRupiah(value);
        
        // Set hidden input value
        jumlahBayarHidden.value = value;
        
        // Calculate kembalian
        const kembalian = value - totalHarga;
        
        kembalianDisplay.value = formatRupiah(Math.max(0, kembalian));
        kembalianHidden.value = Math.max(0, kembalian);
        
        // Enable/disable payment button
        btnBayar.disabled = value < totalHarga;
    });
    
    // Handle Nominal Buttons
    document.querySelectorAll('.nominal-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const nominal = parseInt(this.dataset.nominal);
            const currentValue = parseRupiah(jumlahBayarDisplay.value) || 0;
            
            jumlahBayarDisplay.value = formatRupiah(currentValue + nominal);
            jumlahBayarHidden.value = currentValue + nominal;
            
            // Calculate kembalian
            const kembalian = (currentValue + nominal) - totalHarga;
            
            kembalianDisplay.value = formatRupiah(Math.max(0, kembalian));
            kembalianHidden.value = Math.max(0, kembalian);
            
            // Enable/disable payment button
            btnBayar.disabled = (currentValue + nominal) < totalHarga;
        });
    });
    
    // Handle Uang Pas Button
    document.querySelector('.uang-pas-btn').addEventListener('click', function() {
        jumlahBayarDisplay.value = formatRupiah(totalHarga);
        jumlahBayarHidden.value = totalHarga;
        
        kembalianDisplay.value = formatRupiah(0);
        kembalianHidden.value = 0;
        
        btnBayar.disabled = false;
    });
    
    // Handle Form Submission
    document.getElementById('formPembayaran').addEventListener('submit', function(e) {
        const jumlahBayar = parseFloat(jumlahBayarHidden.value) || 0;
        
        if (jumlahBayar < totalHarga) {
            e.preventDefault();
            alert('Jumlah pembayaran kurang dari total belanja!');
            return false;
        }
    });
    
    // Set keyboard focus to the payment input field when the page loads
    jumlahBayarDisplay.focus();
});
