import "./bootstrap";
import "../css/app.css";
import Alpine from "alpinejs";
import { DataTable } from "simple-datatables";
import "simple-datatables/dist/style.css";

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    const categoriesTable = document.querySelector("#categoriesTb");
    if (categoriesTable) {
        new DataTable(categoriesTable);
    }

    const productsTable = document.querySelector("#productsTb");
    if (productsTable) {
        new DataTable(productsTable);
    }

    // Logika untuk konfirmasi hapus
    document.body.addEventListener('submit', function(event) {
        if (event.target && event.target.matches('form.delete-form')) {
            event.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                event.target.submit();
            }
        }
    });
});