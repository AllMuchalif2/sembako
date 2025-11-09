import { DataTable } from "simple-datatables";
import "simple-datatables/dist/style.css";

export function initDataTables() {
    const tablesToInit = [
        { selector: "#categoriesTb" },
        { selector: "#productsTb" },

    ];

    tablesToInit.forEach(tableInfo => {
        const tableElement = document.querySelector(tableInfo.selector);
        if (tableElement) {
            new DataTable(tableElement);
        }
    });
}
