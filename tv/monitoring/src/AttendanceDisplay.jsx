import React, { useEffect, useState } from 'react';

export default function AttendanceDisplay() {
  const [employeesLate, setEmployeesLate] = useState([]);
  const [employeesEarlyLeave, setEmployeesEarlyLeave] = useState([]);
  const [currentTime, setCurrentTime] = useState(new Date());

  const fetchData = async () => {
    try {
      const lateRes = await fetch('/api/late');
      const earlyRes = await fetch('/api/early');
      const lateData = await lateRes.json();
      const earlyData = await earlyRes.json();
      setEmployeesLate(lateData.data || []);
      setEmployeesEarlyLeave(earlyData.data || []);
    } catch (error) {
      console.error('Fetch error:', error);
    }
  };

  useEffect(() => {
    fetchData();
    const interval = setInterval(() => {
      setCurrentTime(new Date());
      fetchData();
    }, 1000);
    return () => clearInterval(interval);
  }, []);

  const formatTime = (date) => {
    return date.toLocaleTimeString('id-ID', { hour12: false });
  };

  const formatDate = (date) => {
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
  };

  return (
    <div className="bg-[#0B1E34] text-white min-h-screen p-8 font-sans">
      <div className="flex justify-between items-center mb-6">
        <div>
          <h1 className="text-3xl font-bold">MONITORING KETERLAMBATAN</h1>
          <h2 className="text-2xl text-yellow-400 font-semibold">& PULANG CEPAT PEGAWAI</h2>
        </div>
        <div className="text-right text-sm">
          <p>{formatDate(currentTime)}</p>
          <p>{formatTime(currentTime)} WIB</p>
        </div>
      </div>

      {/* Pegawai Terlambat */}
      <h3 className="text-yellow-400 text-xl font-semibold mb-2">PEGAWAI TERLAMBAT</h3>
      <table className="w-full text-left mb-8">
        <thead>
          <tr className="border-b border-gray-600">
            <th className="py-2">No.</th>
            <th>NIP</th>
            <th>Nama Pegawai</th>
            <th>Departemen</th>
            <th>Perusahaan</th>
            <th>Jam Masuk</th>
          </tr>
        </thead>
        <tbody>
          {employeesLate.map((emp, index) => (
            <tr key={emp.id || index} className="border-b border-gray-700">
              <td className="py-2">{index + 1}</td>
              <td>{emp.nip}</td>
              <td>{emp.name}</td>
              <td>{emp.dept}</td>
              <td>{emp.company}</td>
              <td className="text-orange-400 font-bold">{emp.time}</td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* Pegawai Pulang Cepat */}
      <h3 className="text-yellow-400 text-xl font-semibold mb-2">PEGAWAI PULANG CEPAT</h3>
      <table className="w-full text-left">
        <thead>
          <tr className="border-b border-gray-600">
            <th className="py-2">No.</th>
            <th>NIP</th>
            <th>Nama Pegawai</th>
            <th>Departemen</th>
            <th>Perusahaan</th>
            <th>Jam Pulang</th>
          </tr>
        </thead>
        <tbody>
          {employeesEarlyLeave.map((emp, index) => (
            <tr key={emp.id || index} className="border-b border-gray-700">
              <td className="py-2">{index + 1}</td>
              <td>{emp.nip}</td>
              <td>{emp.name}</td>
              <td>{emp.dept}</td>
              <td>{emp.company}</td>
              <td className="text-orange-400 font-bold">{emp.time}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
