// resources/js/menuConfig.js
export const menuConfig = {
  admin: [
    { name: "Dashboard", route: "dashboard" },
    { name: "Cuti & Izin", route: "leave.index" },
    { name: "Medical Checkup", route: "mcu.index" },
    { name: "User Management", route: "users.index" },
   // { name: "Reports", route: "reports.index" },
  ],
  hrd: [
    { name: "Dashboard", route: "dashboard" },
    { name: "Cuti & Izin", route: "leaves.index" },
  ],
  petugas_medis: [
    { name: "Medical Checkup", route: "mcu.index" },
  ],
  default: [
    { name: "Dashboard", route: "dashboard" },
  ],
};
