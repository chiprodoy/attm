import React, { useState } from 'react';
import { useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Edit({ employees,auth,log }) {

    const formatDate = (date) => {
        if (!date) return '';
        const d = new Date(date);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
  const { data, setData, put, processing, errors } = useForm({
    employee_USERID: log.employee_USERID,
    checklog_date: formatDate(log.checklog_date) || "",
    checklog_status: log.checklog_status || "7",
  });

  const submit = (e) => {
    e.preventDefault();
    put(route('leave.update', log.id));
  };


  return (
        <AuthenticatedLayout
                            user={auth.user}
                            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Tambah Izin / Cuti / Sakit</h2>}
                        >
            <div className="p-6 m-5 bg-white shadow rounded">
                    <div className="p-6">

                        <form onSubmit={submit} className="space-y-4">
                            <div>
                            <label>Pegawai</label>
                            <select disabled
                                value={data.employee_USERID}
                                onChange={(e) => setData('employee_USERID', e.target.value)}
                                className="border p-2 w-full"
                            >
                                <option value="">-- Pilih Pegawai --</option>
                                {employees.map((emp) => (
                                <option key={emp.USERID} value={emp.USERID}>
                                    {emp.Name}
                                </option>
                                ))}
                            </select>
                            {errors.employee_USERID && <div className="text-red-600">{errors.employee_USERID}</div>}
                            </div>

                            <div>
                            <label>Tanggal</label>
                            <input
                                readOnly
                                type="date"
                                value={data.checklog_date}
                                onChange={(e) => setData('checklog_date', e.target.value)}
                                className="border p-2 w-full"
                            />
                            {errors.checklog_date && <div className="text-red-600">{errors.checklog_date}</div>}
                            </div>

                            <div>
                            <label>Status</label>
                            <select
                                value={data.checklog_status}
                                onChange={(e) => setData('checklog_status', e.target.value)}
                                className="border p-2 w-full"
                            >
                                <option value="7">Izin</option>
                                <option value="8">Cuti</option>
                                <option value="9">Sakit</option>
                                <option value="10">Dinas</option>

                            </select>
                            </div>

                            <div>
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-blue-600 text-white px-4 py-2 rounded"
                            >
                                Simpan
                            </button>
                            <Link href={route('leave.index')} className="ml-2">
                                Batal
                            </Link>
                            </div>
                        </form>
                    </div>
            </div>

    </AuthenticatedLayout>
  );
}
