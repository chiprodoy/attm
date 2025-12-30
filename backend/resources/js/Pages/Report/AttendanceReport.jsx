import { router, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function AttendanceReport({auth}) {
    const { logs, filters } = usePage().props;

    const updateFilter = (e) => {
        router.get(
            route('reports.attendance'),
            { ...filters, [e.target.name]: e.target.value },
            { preserveState: true, replace: true }
        );
    };

    const changePage = (page) => {
        router.get(
            route('reports.attendance'),
            { ...filters, page },
            { preserveState: true, replace: true }
        );
    };
    const formatDate = (value) => {
        if (!value) return '-';
        return new Date(value).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
        });
    };

    const formatTime = (value) => {
        if (!value) return '-';
        return value.substring(11, 19); // HH:mm:ss
    };
    const safe = (val, cb) => (val ? cb(val) : '-');
    return (
                <AuthenticatedLayout
                            user={auth.user}
                            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Daftar Izin / Cuti / Sakit</h2>}
                        >
        <div className="p-6 space-y-6">
            {/* HEADER */}
            <h1 className="text-xl font-semibold text-gray-800">
                Laporan Kehadiran
            </h1>

            {/* FILTER */}
            <div className="bg-white rounded-lg shadow p-4">
                <div className="grid grid-cols-1 md:grid-cols-6 gap-3">
                    <input
                        type="date"
                        name="start_date"
                        defaultValue={filters.start_date}
                        onChange={updateFilter}
                        className="input"
                    />

                    <input
                        type="date"
                        name="end_date"
                        defaultValue={filters.end_date}
                        onChange={updateFilter}
                        className="input"
                    />

                    <select
                        name="check_type"
                        defaultValue={filters.check_type}
                        onChange={updateFilter}
                        className="input"
                    >
                        <option value="">Check Type</option>
                        <option value="0">Normal</option>
                        <option value="1">Shift</option>
                    </select>

                    <select
                        name="check_log_status"
                        defaultValue={filters.check_log_status}
                        onChange={updateFilter}
                        className="input"
                    >
                        <option value="">Status</option>
                        <option value="1">Hadir</option>
                        <option value="0">Tidak Hadir</option>
                    </select>

                    <input
                        type="text"
                        name="employee"
                        placeholder="Nama Pegawai"
                        defaultValue={filters.employee}
                        onChange={updateFilter}
                        className="input"
                    />

                    <input
                        type="text"
                        name="department"
                        placeholder="Departemen"
                        defaultValue={filters.department}
                        onChange={updateFilter}
                        className="input"
                    />
                </div>
            </div>

            {/* TABLE */}
            <div className="bg-white rounded-lg shadow overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="min-w-full text-sm text-left">
                        <thead className="bg-gray-100 text-gray-700">
                            <tr>
                                <th className="th">Tanggal</th>
                                <th className="th">Nama</th>
                                <th className="th">Departemen</th>
                                <th className="th">Check In</th>
                                <th className="th">Check Out</th>
                                <th className="th">Late</th>
                                <th className="th">OT</th>
                                <th className="th">Status</th>
                            </tr>
                        </thead>

                        <tbody className="divide-y">
                            {logs.data.map((row) => (
                                <tr key={row.id} className="hover:bg-gray-50">
                                    <td className="td">{safe(row.checklog_time, formatDate)}</td>
                                    <td className="td">{row.employee_name || '-'}</td>
                                    <td className="td">{row.departement_name}</td>
                                    <td className="td">{safe(row.check_log_in, formatTime)}</td>
                                    <td className="td">{safe(row.check_log_out, formatTime)}</td>
                                    <td className="td">{row.late} mnt</td>
                                    <td className="td">{row.overtime} mnt</td>
                                    <td className="td">
                                        <span
                                            className={`px-2 py-1 rounded-full text-xs font-semibold
                                                ${row.check_log_status
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-red-100 text-red-700'
                                                }`}
                                        >
                                            {row.check_log_status ? 'Hadir' : 'Tidak'}
                                        </span>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {/* PAGINATION */}
                <div className="flex items-center justify-between p-4">
                    <button
                        disabled={!logs.prev_page_url}
                        onClick={() => changePage(logs.current_page - 1)}
                        className="btn-secondary disabled:opacity-40"
                    >
                        Prev
                    </button>

                    <span className="text-sm text-gray-600">
                        Page {logs.current_page} / {logs.last_page}
                    </span>

                    <button
                        disabled={!logs.next_page_url}
                        onClick={() => changePage(logs.current_page + 1)}
                        className="btn-secondary disabled:opacity-40"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
        </AuthenticatedLayout>
    );
}
