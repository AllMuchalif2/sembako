import "./bootstrap";
import "../css/app.css";
import Alpine from "alpinejs";

import { initDataTables } from './datatables.js';
import { initConfirmDelete } from './ui.js';
import { initCartEvents } from './cart.js';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi DataTables untuk halaman admin
    initDataTables();

    // Inisialisasi konfirmasi hapus untuk semua form .delete-form
    initConfirmDelete();

    // Inisialisasi semua event listener untuk keranjang belanja
    initCartEvents();
});
