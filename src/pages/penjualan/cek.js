// Fungsi penting dari pembayaran.js
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID').format(angka);
}

function parseRupiah(rupiahStr) {
    return parseInt(rupiahStr.replace(/\D/g, '')) || 0;
}

// Event listener untuk perhitungan pembayaran
jumlahBayarDisplay.addEventListener('input', function() {
    const value = parseRupiah(this.value);
    this.value = formatRupiah(value);
    jumlahBayarHidden.value = value;
    
    const kembalian = value - totalHarga;
    kembalianDisplay.value = formatRupiah(Math.max(0, kembalian));
    kembalianHidden.value = Math.max(0, kembalian);
    
    btnBayar.disabled = value < totalHarga;
});