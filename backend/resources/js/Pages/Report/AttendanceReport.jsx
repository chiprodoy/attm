import { router, usePage } from '@inertiajs/react';

export default function AttendanceReport() {
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

    return (
        <div className="container py-3">
            <h4 className="mb-3">Laporan Kehadiran</h4>

            {/* FILTER */}
            <div className="card mb-3">
                <div className="card-body row g-2">
                    <div className="col-md-2">
                        <input type="date" name="start_date" className="form-control"
                            defaultValue={filters.start_date}
                            onChange={updateFilter} />
                    </div>

                    <div className="col-md-2">
                        <input type="date" name="end_date" className="form-control"
                            defaultValue={filters.end_date}
                            onChange={updateFilter} />
                    </div>

                    <div className="col-md-2">
                        <select name="check_type" className="form-control"
                            defaultValue={filters.check_type}
                            onChange={updateFilter}>
                            <option value="">Check Type</option>
                            <option value="0">Normal</option>
                            <option value="1">Shift</option>
                        </select>
                    </div>

                    <div className="col-md-2">
                        <select name="check_log_status" className="form-control"
                            defaultValue={filters.check_log_status}
                            onChange={updateFilter}>
                            <option value="">Status</option>
                            <option value="1">Hadir</option>
                            <option value="0">Tidak Hadir</option>
                        </select>
                    </div>

                    <div className="col-md-2">
                        <input type="text" name="employee" placeholder="Nama Pegawai"
                            defaultValue={filters.employee}
                            className="form-control"
                            onChange={updateFilter} />
                    </div>

                    <div className="col-md-2">
                        <input type="text" name="department" placeholder="Departemen"
                            defaultValue={filters.department}
                            className="form-control"
                            onChange={updateFilter} />
                    </div>
                </div>
            </div>

            {/* TABLE */}
            <div className="card">
                <div className="card-body table-responsive">
                    <table className="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Late</th>
                                <th>OT</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {logs.data.map(row => (
                                <tr key={row.id}>
                                    <td>{row.checklog_time}</td>
                                    <td>{row.employee_name || '-'}</td>
                                    <td>{row.departement_name}</td>
                                    <td>{row.check_log_in}</td>
                                    <td>{row.check_log_out}</td>
                                    <td>{row.late} mnt</td>
                                    <td>{row.overtime} mnt</td>
                                    <td>
                                        <span className={`badge ${row.check_log_status ? 'bg-success' : 'bg-danger'}`}>
                                            {row.check_log_status ? 'Hadir' : 'Tidak'}
                                        </span>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>

                    {/* PAGINATION */}
                    <div className="d-flex justify-content-between align-items-center">
                        <button
                            className="btn btn-sm btn-secondary"
                            disabled={!logs.prev_page_url}
                            onClick={() => changePage(logs.current_page - 1)}>
                            Prev
                        </button>

                        <span>Page {logs.current_page} / {logs.last_page}</span>

                        <button
                            className="btn btn-sm btn-secondary"
                            disabled={!logs.next_page_url}
                            onClick={() => changePage(logs.current_page + 1)}>
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}
