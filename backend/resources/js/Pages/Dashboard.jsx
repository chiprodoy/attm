import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import React from 'react';
import { Head } from '@inertiajs/react';
import { PieChart, Pie, Cell, Tooltip } from 'recharts';

export default function Dashboard({ auth,summary, attendances }) {
    const data = [
        { name: 'Hadir', value: summary.hadir },
        { name: 'Terlambat', value: summary.terlambat },
        { name: 'Pulang Cepat', value: summary.pulang_cepat },
        { name: 'Belum Presensi', value: summary.belum_presensi },
    ];

    const COLORS = ['#16a34a', '#dc2626', '#f97316', '#6b7280'];
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            {/* Ringkasan */}
            <div className='container-md mt-6 px-5'>
                <div className="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-8">
                    <div className="bg-white shadow rounded p-4">
                    <h2 className="text-gray-600">Hadir</h2>
                    <p className="text-3xl font-bold text-green-600">{summary.hadir}</p>
                    </div>
                    <div className="bg-white shadow rounded p-4">
                    <h2 className="text-gray-600">Terlambat</h2>
                    <p className="text-3xl font-bold text-red-600">{summary.terlambat}</p>
                    </div>
                    <div className="bg-white shadow rounded p-4">
                    <h2 className="text-gray-600">Pulang Cepat</h2>
                    <p className="text-3xl font-bold text-orange-600">{summary.pulang_cepat}</p>
                    </div>
                    <div className="bg-white shadow rounded p-4">
                    <h2 className="text-gray-600">Belum Presensi</h2>
                    <p className="text-3xl font-bold text-gray-600">{summary.belum_presensi}</p>
                    </div>
                </div>
                <div className="bg-white shadow rounded p-4 mb-6">
                    <PieChart width={300} height={300}>
                    <Pie data={data} cx="50%" cy="50%" label outerRadius={120} dataKey="value">
                        {data.map((entry, index) => (
                        <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                        ))}
                    </Pie>
                    <Tooltip />
                    </PieChart>
                </div>
                {/* Tabel Presensi */}
                <div className="bg-white shadow rounded p-4">
                    <h2 className="text-lg font-bold mb-4">Daftar Presensi Hari Ini</h2>
                        <table className="w-full text-sm">
                        <thead className="bg-gray-200 text-left">
                            <tr>
                            <th className="p-2">NIP</th>
                            <th className="p-2">Nama</th>
                            <th className="p-2">Departemen</th>
                            <th className="p-2">Jam Presensi</th>
                            <th className="p-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {attendances.map((item, index) => (
                            <tr key={index} className="border-b">
                                <td className="p-2">{item.nip}</td>
                                <td className="p-2">{item.name}</td>
                                <td className="p-2">{item.department}</td>
                                <td className="p-2">{item.timestamp}</td>
                                <td className="p-2">
                                {item.check_log_status === 'LATE' && (
                                    <span className="text-red-600 font-bold">Terlambat</span>
                                )}
                                {item.check_log_status === 'EARLY_CHECKOUT' && (
                                    <span className="text-orange-600 font-bold">Pulang Cepat</span>
                                )}
                                {item.check_log_status === 'NORMAL' && (
                                    <span className="text-green-600 font-bold">Tepat Waktu</span>
                                )}
                                </td>
                            </tr>
                            ))}
                        </tbody>
                        </table>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
