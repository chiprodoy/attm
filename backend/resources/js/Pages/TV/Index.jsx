import React, { useEffect, useState } from 'react';

export default function DashboardTV() {
  const [clock, setClock] = useState(new Date());
  const [lateEmployees, setLateEmployees] = useState([]);
  const [earlyEmployees, setEarlyEmployees] = useState([]);
  const [checkingEmployees, setCheckingEmployees] = useState([]);
  const dummyDate = '2025-05-26';

  // Update jam setiap detik
  useEffect(() => {
    const interval = setInterval(() => setClock(new Date()), 1000);
    return () => clearInterval(interval);
  }, []);

  // Fetch data dari API setiap detik
  useEffect(() => {
    const fetchData = async () => {
      try {
        const [lateRes, earlyRes, checkRes] = await Promise.all([
          axios.get('http://localhost:8000/api/late?start_date='+dummyDate),
          axios.get('http://localhost:8000/api/leave_early?start_date='+dummyDate),
          axios.get('http://localhost:8000/api/presensi?start_date='+dummyDate),
        ]);

        setLateEmployees(lateRes.data.data);
        setEarlyEmployees(earlyRes.data.data);
        setCheckingEmployees(checkRes.data.data);
      } catch (err) {
        console.error('Gagal fetch data:', err);
      }
    };

    // Panggil pertama kali
    fetchData();

    // Panggil setiap detik
    //const interval = setInterval(fetchData, 1000);
    const interval = setInterval(fetchData, 5000);

    return () => clearInterval(interval);
  }, []);

  const formatTime = (date) =>
    date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

  const formatDate = (date) =>
    date.toLocaleDateString('id-ID', {
      day: '2-digit',
      month: 'long',
      year: 'numeric',
    });

  return (
    <div className="min-h-screen bg-[#0C1F38] text-white px-12 py-8 font-sans">
      <div className="flex justify-between items-start mb-6">
        <div>
          <h1 className="text-3xl font-bold">
            MONITORING KETERLAMBATAN <br /> & <span className="text-yellow-400">PULANG CEPAT PEGAWAI</span>
          </h1>
        </div>
        <div className="text-right">
          <img src="/images/pertamina-logo.png" className="h-10 mb-1" alt="logo" />
          <div>{formatDate(clock)} | {formatTime(clock)} WIB</div>
        </div>
      </div>

      <div className="mb-8">
        <h2 className="text-yellow-400 text-xl font-semibold mb-2">PEGAWAI TERLAMBAT</h2>
        <table className="w-full text-sm">
          <thead className="text-left bg-[#0F2A4B] text-white">
            <tr>
              <th className="p-2">No.</th>
              <th className="p-2">NIP</th>
              <th className="p-2">Nama Pegawai</th>
              <th className="p-2">Departemen</th>
              <th className="p-2">Jam Masuk</th>
            </tr>
          </thead>
          <tbody>
            {lateEmployees.map((emp, index) => (
              <tr key={index} className="border-b border-gray-700">
                <td className="p-2">{index + 1}</td>
                <td className="p-2">{emp.employee.ssn}</td>
                <td className="p-2">{emp.employee.Name}</td>
                <td className="p-2">{emp.departement_name}</td>
                <td className="p-2 text-orange-400 font-bold">{emp.checklog_time}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <div>
        <h2 className="text-yellow-400 text-xl font-semibold mb-2">PEGAWAI PULANG CEPAT</h2>
        <table className="w-full text-sm">
          <thead className="text-left bg-[#0F2A4B] text-white">
            <tr>
              <th className="p-2">No.</th>
              <th className="p-2">NIP</th>
              <th className="p-2">Nama Pegawai</th>
              <th className="p-2">Departemen</th>
              <th className="p-2">Jam Pulang</th>
            </tr>
          </thead>
          <tbody>
            {earlyEmployees.map((emp, index) => (
              <tr key={index} className="border-b border-gray-700">
                <td className="p-2">{index + 1}</td>
                <td className="p-2">{emp.employee.ssn}</td>
                <td className="p-2">{emp.employee.Name}</td>
                <td className="p-2">{emp.departement_name}</td>
                <td className="p-2 text-orange-400 font-bold">{emp.checklog_time}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
            {/* Pegawai Sedang Presensi */}
      <div className="mt-8">
        <h2 className="text-yellow-400 text-xl font-semibold mb-2">PEGAWAI SEDANG MELAKUKAN PRESENSI</h2>
        <table className="w-full text-sm">
          <thead className="text-left bg-[#0F2A4B] text-white">
            <tr>
              <th className="p-2">No.</th>
              <th className="p-2">NIP</th>
              <th className="p-2">Nama Pegawai</th>
              <th className="p-2">Departemen</th>
              <th className="p-2">Jam</th>
            </tr>
          </thead>
          <tbody>
            {checkingEmployees.map((emp, index) => (
              <tr key={index} className="border-b border-gray-700">
                <td className="p-2">{index + 1}</td>
                <td className="p-2">{emp.employee.ssn}</td>
                <td className="p-2">{emp.employee.Name}</td>
                <td className="p-2">{emp.departement_name}</td>
                <td className="p-2 text-orange-400 font-bold">{emp.checklog_time}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
