import { DataTable } from "simple-datatables";
import "simple-datatables/dist/style.css";

export function initDataTables() {
    const tablesToInit = [
        { selector: "#categoriesTb" },
        { selector: "#productsTb" },
        { selector: "#promosTb" },
        { selector: "#adminsTb" },
    ];

    // Konfigurasi bahasa Indonesia
    const indonesianLabels = {
        placeholder: "Cari data...",
        perPage: "data per halaman",
        noRows: "Tidak ada data tersedia",
        info: "Menampilkan {start} sampai {end} dari {rows} data",
        noResults: "Tidak ada hasil yang cocok dengan pencarian Anda",
    };

    tablesToInit.forEach(tableInfo => {
        const tableElement = document.querySelector(tableInfo.selector);
        if (tableElement) {
            new DataTable(tableElement, {
                labels: indonesianLabels,
                perPage: 10,
                perPageSelect: [5, 10, 15, 20, 25],
                searchable: true,
                sortable: true,
            });
        }
    });
}