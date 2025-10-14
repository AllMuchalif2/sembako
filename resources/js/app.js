import "./bootstrap";

import "../css/app.css";
import Alpine from "alpinejs";
import { DataTable } from "simple-datatables";
import "simple-datatables/dist/style.css"; // Impor CSS bawaan

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    const dataTable = new DataTable("#categoriesTb", {});
});
