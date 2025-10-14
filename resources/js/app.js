import "./bootstrap";

import "../css/app.css";
import Alpine from "alpinejs";
import { DataTable } from "simple-datatables";
import "simple-datatables/dist/style.css"; // Impor CSS bawaan

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi DataTable untuk tabel kategori jika ada
    const categoriesTable = document.querySelector("#categoriesTb");
    if (categoriesTable) {
        new DataTable(categoriesTable, {
            // Opsi tambahan bisa diletakkan di sini
        });
    }

    // Inisialisasi DataTable untuk tabel produk jika ada
    const productsTable = document.querySelector("#productsTb");
    if (productsTable) {
        new DataTable(productsTable, {
            // Opsi tambahan bisa diletakkan di sini
        });
    }
});
