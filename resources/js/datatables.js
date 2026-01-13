import { DataTable } from "simple-datatables";
import "simple-datatables/dist/style.css";

export function initDataTables() {
    const tablesToInit = [
        { selector: "#categoriesTb" },
        { selector: "#productsTb" },
        { selector: "#promosTb" },
        { selector: "#adminsTb" },
        {
            selector: "#stockTable",
            columns: [
                { select: 0, sortable: false }, // No column tidak bisa di-sort
                { select: 3, sort: "asc" }, // Default sort by Stok column (ascending)
            ],
        },
    ];

    // Konfigurasi bahasa Indonesia
    const indonesianLabels = {
        placeholder: "Cari data...",
        perPage: "data per halaman",
        noRows: "Tidak ada data tersedia",
        info: "Menampilkan {start} sampai {end} dari {rows} data",
        noResults: "Tidak ada hasil yang cocok dengan pencarian Anda",
    };

    tablesToInit.forEach((tableInfo) => {
        const tableElement = document.querySelector(tableInfo.selector);
        if (tableElement) {
            const config = {
                labels: indonesianLabels,
                perPage: 10,
                perPageSelect: [5, 10, 15, 20, 25],
                searchable: true,
                sortable: true,
            };

            // Tambahkan konfigurasi columns jika ada
            if (tableInfo.columns) {
                config.columns = tableInfo.columns;
            }

            new DataTable(tableElement, config);
        }
    });
}
