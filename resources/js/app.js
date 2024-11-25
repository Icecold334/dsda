import "./bootstrap";
import "flowbite";
import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;
import Swal from "sweetalert2";
window.Swal = Swal;
window.formatRupiah = function (value) {
    let stringValue = value.toString();
    let split = stringValue.split(",");
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] ? rupiah + "," + split[1] : rupiah;
    return rupiah;
};

window.rupiah = function (angka) {
    const numberString = angka.toString();
    const sisa = numberString.length % 3;
    let rupiah = numberString.substr(0, sisa);
    const ribuan = numberString.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        const separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }
    return "Rp " + rupiah + ",00";
};

window.confirmRemove = function (message, callback) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: message || "Data akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            // Callback function to execute after confirmation
            if (typeof callback === "function") {
                callback();
            }
        }
    });
};
window.feedback = function (title, message, icon) {
    let timerInterval;
    Swal.fire({
        title: title,
        html: message,
        icon: icon,
        timer: 1500,
        timerProgressBar: true,
        showConfirmButton: false,
        willClose: () => {
            clearInterval(timerInterval);
        },
    }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log("I was closed by the timer");
        }
    });
};
