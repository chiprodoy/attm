import React, { useState } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Index({ logs,auth,filters,start_date  }) {
  const { flash } = usePage().props;
  const [date, setDate] = useState(filters.date || start_date);
  const [status, setStatus] = useState(filters.status || '');

  const handleFilter = (e) => {
    e.preventDefault();
    router.get(route('leave.index'), { date, status }, { preserveState: true });
  };

  return (
        <AuthenticatedLayout
                    user={auth.user}
                    header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Daftar Izin / Cuti / Sakit</h2>}
                >
            <div className="p-6 m-5 bg-white shadow rounded">

                {flash?.success && <div className="mb-4 text-green-600">{flash?.success}</div>}
                <div className='grid grid-cols-2'>
                    <div className='col-start-1 flex'>
                         {/* Filter Form */}
                        <form onSubmit={handleFilter} className="flex items-end space-x-4 mb-4">
                        <div>
                            <label className="block text-sm">Tanggal</label>
                            <input
                            type="date"
                            value={date}
                            onChange={(e) => setDate(e.target.value)}
                            className="border p-2 rounded"
                            />
                        </div>

                        <div>
                            <label className="block text-sm">Status</label>
                            <select
                            value={status}
                            onChange={(e) => setStatus(e.target.value)}
                            className="border p-2 rounded"
                            >
                            <option value="">-- Semua --</option>
                            <option value="7">Izin</option>
                            <option value="8">Cuti</option>
                            <option value="9">Sakit</option>
                            </select>
                        </div>

                        <button
                            type="submit"
                            className="bg-gray-500 text-white px-4 py-2 rounded"
                        >
                            Filter
                        </button>
                        </form>
                    </div>
                    <div className='col-start-2 flex p-7 justify-end'>
                        <Link href={route('leave.create')} className="bg-blue-600 text-white px-4 py-2 rounded">
                            Tambah Data
                        </Link>
                    </div>

                </div>


                <table className="w-full mt-4 border">
                    <thead>
                    <tr className="bg-gray-100">
                        <th className="p-2">Tanggal</th>
                        <th className="p-2">NIP</th>
                        <th className="p-2">Nama Pegawai</th>
                        <th className="p-2">Status</th>
                        <th className="p-2">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    {logs.data.length > 0 ? (
                        logs.data.map((log) => (
                            <tr key={log.id} className="border-t">
                            <td className="p-2">{log.checklog_date}</td>
                            <td className="p-2">{log.employee?.SSN}</td>
                            <td className="p-2">{log.employee?.Name}</td>
                            <td className="p-2">
                                {log.checklog_status == 7 && "Izin"}
                                {log.checklog_status == 8 && "Cuti"}
                                {log.checklog_status == 9 && "Sakit"}
                            </td>
                            <td className="p-2 space-x-2">
                                <Link
                                href={route('leave.edit', log.id)}
                                className="text-blue-600"
                                >
                                Edit
                                </Link>
                                <Link
                                href={route('leave.destroy', log.id)}
                                method="delete"
                                as="button"
                                className="text-red-600"
                                onClick={(e) => {
                                    if (!confirm("Apakah Anda yakin ingin menghapus data ini? Menghapus data ini dapat merubah status menjadi tidak hadir")) {
                                    e.preventDefault(); // batalkan delete kalau pilih Cancel
                                    }
                                }}
                                >
                                Hapus
                                </Link>
                            </td>
                            </tr>
                        ))
                    ) : (
                        <tr>
                            <td colSpan="5" className="text-center p-4">
                            Tidak ada data.
                            </td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        </AuthenticatedLayout>

  );
}
