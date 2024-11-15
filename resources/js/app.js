import "./bootstrap";
import "flowbite";
import ApexCharts from 'apexcharts';
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

window.rupiah = function(angka) {
    const numberString = angka.toString();
    const sisa = numberString.length % 3;
    let rupiah = numberString.substr(0, sisa);
    const ribuan = numberString.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        const separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    return 'Rp ' + rupiah + ',00';
}
