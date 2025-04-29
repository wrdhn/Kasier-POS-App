document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');

deleteButtons.forEach(function (button) { 
    button.addEventListener('click', function(e) {
        e.preventDefault();

        const href = this.getAttribute('data-href');

        Swal.fire({
            title: "Anda Yakin?",
            text: "Anda tidak dapat membatalkan aksi ini, setelah dilakukan",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#dc3545",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
            Swal.fire({
                title: "Berhasil dihapus!",
                text: "Data berhasil dihapus.",
                icon: "success",
            }).then(() => {
                window.location.href = href;
            });
            }
        });
    });
});
});