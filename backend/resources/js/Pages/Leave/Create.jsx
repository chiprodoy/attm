import React, { useState } from 'react';
import { useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Create({ employees,auth }) {
  const { data, setData, post, processing, errors } = useForm({
    employee_USERID: '',
    checklog_date: '',
    checklog_status: '7',
  });

  const submit = (e) => {
    e.preventDefault();
    post(route('leave.store'));
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
                <select
                    value={data.employee_USERID}
                    onChange={(e) => setData('employee_USERID', e.target.value)}
                    className="border p-2 w-full"
                >
                    <option value="">-- Pilih Pegawai --</option>
                    {employees.map((emp) => (
                    <option key={emp.USERID} value={emp.USERID}>
                        {emp.name}
                    </option>
                    ))}
                </select>
                {errors.employee_USERID && <div className="text-red-600">{errors.employee_USERID}</div>}
                </div>

                {/* Tanggal Mulai */}
                <div>
                <label className="block mb-1 font-semibold">Tanggal Mulai</label>
                <input
                    type="date"
                    value={data.start_date}
                    onChange={(e) => setData('start_date', e.target.value)}
                    className="border p-2 w-full rounded"
                />
                {errors.start_date && (
                    <div className="text-red-600">{errors.start_date}</div>
                )}
                </div>

                {/* Tanggal Berakhir */}
                <div>
                <label className="block mb-1 font-semibold">Tanggal Berakhir</label>
                <input
                    type="date"
                    value={data.end_date}
                    onChange={(e) => setData('end_date', e.target.value)}
                    className="border p-2 w-full rounded"
                />
                {errors.end_date && (
                    <div className="text-red-600">{errors.end_date}</div>
                )}
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
