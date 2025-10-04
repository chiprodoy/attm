import React, { useEffect, useState } from 'react';

export default function DashboardTV() {
  const [clock, setClock] = useState(new Date());
  const [lateEmployees, setLateEmployees] = useState([]);
  const [earlyEmployees, setEarlyEmployees] = useState([]);
  const [checkingEmployees, setCheckingEmployees] = useState([]);
  const [absentEmployees, setAbsentEmployees] = useState([]);
  const [permitEmployees, setPermitEmployees] = useState([]);

  const dummyDate = '';


  // Update jam setiap detik
  useEffect(() => {
    const interval = setInterval(() => setClock(new Date()), 1000);
    return () => clearInterval(interval);
  }, []);

  // Fetch data dari API setiap detik
  useEffect(() => {
    const fetchData = async () => {
      try {
        const [lateRes, earlyRes, checkRes,absentRes,permitRes] = await Promise.all([
          axios.get('http://localhost:8000/api/late?start_date='+dummyDate),
          axios.get('http://localhost:8000/api/leave_early?start_date='+dummyDate),
          axios.get('http://localhost:8000/api/presensi?start_date='+dummyDate),
          axios.get('http://localhost:8000/api/absent?start_date='+dummyDate),
          axios.get('http://localhost:8000/api/leave?date='+dummyDate),

        ]);

        setLateEmployees(lateRes.data.data);
        setEarlyEmployees(earlyRes.data.data);
        setCheckingEmployees(checkRes.data.data);
        setAbsentEmployees(absentRes.data.data);
        setPermitEmployees(permitRes.data.data);

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

  const formatTime = function (strISOdate) {
    var date = new Date(strISOdate);
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
  }
  const formatDate = function (dateObject) {
    console.log(dateObject);
    const year = dateObject.getFullYear();
    const month = (dateObject.getMonth() + 1).toString().padStart(2, '0'); // Month is 0-indexed
    const day = dateObject.getDate().toString().padStart(2, '0');
    const hours = dateObject.getHours().toString().padStart(2, '0');
    const minutes = dateObject.getMinutes().toString().padStart(2, '0');
    const seconds = dateObject.getSeconds().toString().padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    // var date = new Date(strISOdate);
    // console.log(date);
    // return date.toLocaleDateString('id-ID', {
    //   day: '2-digit',
    //   month: 'long',
    //   year: 'numeric',
    //   hour: '2-digit', minute: '2-digit', second: '2-digit'
    // });
  }

   function setStatus(statusID){
    switch (statusID) {
      case 6:
        return 'ABSEN';
      case 7:
        return 'Izin';
      case 8:
          return 'Cuti';
      case 9:
        return 'Sakit';
      case 10:
        return 'Dinas';
     default:
        return 'Unknown';
    }
   }

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
           <div>{formatDate(clock)}</div>
        </div>
      </div>
      <div className='row'>
        <div className='col-md-6'>
            <h2 className="text-yellow-400 text-xl font-semibold mb-2">PEGAWAI TERLAMBAT</h2>
            <table className="w-full text-sm">
                <thead className="text-left bg-[#0F2A4B] text-white">
                    <tr>
                    <th className="p-2">No.</th>
                    <th className="p-2">NIP</th>
                    <th className="p-2">Nama Pegawai</th>
                    <th className="p-2">Departemen</th>
                    <th className="p-2">Jam Masuk</th>
                    <th className="p-2">Status MCU</th>
                    </tr>
                </thead>
                <tbody>
                    {lateEmployees.map((emp, index) => (
                    <tr key={index} className="border-b border-gray-700">
                        <td className="p-2">{index + 1}</td>
                        <td className="p-2">{emp.employee.ssn}</td>
                        <td className="p-2">{emp.employee.Name}</td>
                        <td className="p-2">{emp.departement_name}</td>
                        <td className="p-2 text-orange-400 font-bold">
                            { formatDate(new Date(emp.check_log_in))}
                        </td>
                        <td className="p-2">{emp.has_mcu ? 'Sudah MCU' : 'Belum MCU'}</td>
                    </tr>
                    ))}
                </tbody>
            </table>
        </div>
        <div className='col-md-6'>
            <h2 className="text-yellow-400 text-xl font-semibold mb-2">PEGAWAI PULANG CEPAT</h2>
            <table className="w-full text-sm">
                <thead className="text-left bg-[#0F2A4B] text-white">
                    <tr>
                    <th className="p-2">No.</th>
                    <th className="p-2">NIP</th>
                    <th className="p-2">Nama Pegawai</th>
                    <th className="p-2">Departemen</th>
                    <th className="p-2">Jam Pulang</th>
                    <th className="p-2">Status MCU</th>
                    </tr>
                </thead>
                <tbody>
                    {earlyEmployees.map((emp, index) => (
                    <tr key={index} className="border-b border-gray-700">
                        <td className="p-2">{index + 1}</td>
                        <td className="p-2">{emp.employee.ssn}</td>
                        <td className="p-2">{emp.employee.Name}</td>
                        <td className="p-2">{emp.departement_name}</td>
                        <td className="p-2 text-orange-400 font-bold">{formatDate(new Date(emp.check_log_out))}</td>
                        <td className="p-2">{emp.has_mcu ? 'Sudah MCU' : 'Belum MCU'}</td>
                    </tr>
                    ))}
                </tbody>
            </table>
        </div>
        <div className='col-md-6'>
            <h2 className="text-yellow-400 text-xl font-semibold mb-2">PEGAWAI SEDANG MELAKUKAN PRESENSI</h2>
            <table className="w-full text-sm">
            <thead className="text-left bg-[#0F2A4B] text-white">
                <tr>
                <th className="p-2">No.</th>
                <th className="p-2">NIP</th>
                <th className="p-2">Nama Pegawai</th>
                <th className="p-2">Departemen</th>
                <th className="p-2">Jam</th>
                <th className="p-2">Status MCU</th>
                </tr>
            </thead>
            <tbody>
                {checkingEmployees.map((emp, index) => (
                <tr key={index} className="border-b border-gray-700">
                    <td className="p-2">{index + 1}</td>
                    <td className="p-2">{emp.ssn}</td>
                    <td className="p-2">{emp.Name}</td>
                    <td className="p-2">{emp.departement_name}</td>
                    <td className="p-2 text-orange-400 font-bold">{emp.CHECKTIME}</td>
                    <td className="p-2">{emp.has_mcu ? 'Sudah MCU' : 'Belum MCU'}</td>
                </tr>
                ))}
            </tbody>
            </table>
        </div>
                <div className='col-md-6'>
            <h2 className="text-yellow-400 text-xl font-semibold mb-2">PEGAWAI Izin / Cuti / Sakit / Dinas</h2>
            <table className="w-full text-sm">
            <thead className="text-left bg-[#0F2A4B] text-white">
                <tr>
                <th className="p-2">No.</th>
                <th className="p-2">NIP</th>
                <th className="p-2">Nama Pegawai</th>
                <th className="p-2">Status</th>
                {/* <th className="p-2">Departemen</th> */}
                </tr>
            </thead>
            <tbody>
                {permitEmployees.map((emp, index) => (
                <tr key={index} className="border-b border-gray-700">
                    <td className="p-2">{index + 1}</td>
                    <td className="p-2">{emp.employee.ssn}</td>
                    <td className="p-2">{emp.employee.Name}</td>
                    <td className="p-2">{setStatus(emp.checklog_status)}</td>
                    {/* <td className="p-2">{emp.employee.departement_name}</td> */}

                </tr>
                ))}
            </tbody>
            </table>
        </div>
        <div className='col-md-6'>
            <h2 className="text-yellow-400 text-xl font-semibold mb-2">PEGAWAI BELUM MELAKUKAN PRESENSI</h2>
            <table className="w-full text-sm">
            <thead className="text-left bg-[#0F2A4B] text-white">
                <tr>
                <th className="p-2">No.</th>
                <th className="p-2">NIP</th>
                <th className="p-2">Nama Pegawai</th>
                {/* <th className="p-2">Departemen</th> */}
                <th className="p-2">Status MCU</th>
                </tr>
            </thead>
            <tbody>
                {absentEmployees.map((emp, index) => (
                <tr key={index} className="border-b border-gray-700">
                    <td className="p-2">{index + 1}</td>
                    <td className="p-2">{emp.employee.ssn}</td>
                    <td className="p-2">{emp.employee.Name}</td>
                    {/* <td className="p-2">{emp.employee.departement_name}</td> */}
                    <td className="p-2">{emp.has_mcu ? 'Sudah MCU' : 'Belum MCU'}</td>
                </tr>
                ))}
            </tbody>
            </table>
        </div>

      </div>

    {/* Pegawai Sedang Presensi */}
      <div className="mt-8">

      </div>
    </div>
  );
}
