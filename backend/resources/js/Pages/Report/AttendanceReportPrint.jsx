import { useEffect } from "react";

export default function AttendanceReportPrint({ logs, filters, printedAt }) {
  useEffect(() => {
    window.print();
  }, []);

  return (
    <div className="p-6 text-sm text-black">
      {/* HEADER */}
      <div className="text-center mb-4">
        <h1 className="text-xl font-bold">LAPORAN KEHADIRAN PEGAWAI</h1>
        <p className="text-xs">
          Periode: {filters.start_date} s/d {filters.end_date}
        </p>
        <p className="text-xs">Dicetak: {printedAt}</p>
      </div>

      {/* TABLE */}
      <table className="w-full border border-black border-collapse">
        <thead>
          <tr className="bg-gray-200">
            <th className="border p-1">No</th>
            <th className="border p-1">Nama</th>
            <th className="border p-1">Departemen</th>
            <th className="border p-1">Tanggal</th>
            <th className="border p-1">Masuk</th>
            <th className="border p-1">Pulang</th>
            <th className="border p-1">Status</th>
          </tr>
        </thead>
        <tbody>
          {logs.map((row, i) => (
            <tr key={row.id}  className={row.late < 0 ? "late-row" : ""}>
              <td className="border p-1 text-center">{i + 1}</td>
              <td className="border p-1">{row.employee_name}</td>
              <td className="border p-1">{row.departement_name}</td>
              <td className="border p-1 text-center">
                {new Date(row.checklog_time).toLocaleDateString("id-ID")}
              </td>
              <td className="border p-1 text-center">
                {row.check_log_in
                  ? row.check_log_in.slice(11, 19)
                  : "-"}
              </td>
              <td className="border p-1 text-center">
                {row.check_log_out
                  ? row.check_log_out.slice(11, 19)
                  : "-"}
              </td>
              <td className="border p-1 text-center">
                {row.check_log_status ? 'Hadir' : 'Tidak'}
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* FOOTER */}
      <div className="mt-8 flex justify-end">
        <div className="text-center">
          <p>Mengetahui,</p>
          <br /><br />
          <p>______________________</p>
          <p className="text-xs">HRD</p>
        </div>
      </div>
    </div>
  );
}
