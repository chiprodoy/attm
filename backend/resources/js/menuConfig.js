// resources/js/menuConfig.js
export const menuConfig = {
  admin: [
    { name: "Dashboard", route: "dashboard" },
    { name: "Cuti & Izin", route: "leave.index" },
    { name: "DCU", route: "mcu.index" },
    { name: "Import", route: "mdb.upload.index" },

    { name: "User Management", route: "users.index" },
    { name: "Laporan Per Tanggal", route: "reports.attendance" },
  ],
  hrd: [
    { name: "Dashboard", route: "dashboard" },
    { name: "Cuti & Izin", route: "leaves.index" },
  ],
  petugas_medis: [
    { name: "DCU", route: "mcu.index" },
  ],
  default: [
    { name: "Dashboard", route: "dashboard" },
  ],
};
