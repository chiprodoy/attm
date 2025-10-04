import React, { useState } from 'react';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function MedicalCheckup({ auth }) {
  const [searchTerm, setSearchTerm] = useState('');
  const [employee, setEmployee] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSearch = async () => {
    setLoading(true);
    setError('');
    setEmployee(null);

    try {
      const response = await axios.get(`/api/search-employee`, {
        params: { term: searchTerm }
      });

      if (response.data && response.data.employee) {
        setEmployee(response.data.employee);
      } else {
        setError('Pegawai tidak ditemukan.');
      }
    } catch (err) {
      console.error(err);
      setError('Terjadi kesalahan saat mencari data.');
    } finally {
      setLoading(false);
    }
  };

  const handleSetMCU = async () => {
    if (!employee) return;
    try {
      await axios.post(`/api/mcu`, { id: employee.id });
      setEmployee({ ...employee, mcu_status: true });
    } catch (err) {
      console.error(err);
      alert('Gagal menyimpan status MCU.');
    }
  };

  return (
    <AuthenticatedLayout
                user={auth.user}
                header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
            >

        <div className="min-h-screen bg-gray-100 p-6">
        <h1 className="text-2xl font-bold mb-4">Medical Check Up Pegawai</h1>

        <div className="flex mb-4">
            <input
            type="text"
            placeholder="Masukkan NIP atau Nama Pegawai"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="border p-2 w-64 mr-2"
            />
            <button
            onClick={handleSearch}
            className="bg-blue-600 text-white px-4 py-2 rounded"
            >
            {loading ? 'Mencari...' : 'Cari'}
            </button>
        </div>

        {error && (
            <div className="text-red-500 mb-4">{error}</div>
        )}

        {employee && (
            <div className="bg-white shadow p-4 rounded">
            <p><strong>NIP:</strong> {employee.nip}</p>
            <p><strong>Nama:</strong> {employee.name}</p>
            <p><strong>Status MCU:</strong> {employee.mcu_status == false ? 'Belum MCU' : 'Sudah MCU'}</p>

            {employee.mcu_status == false && (
                <button
                onClick={handleSetMCU}
                className="mt-4 bg-green-600 text-white px-4 py-2 rounded"
                >
                Set MCU Selesai
                </button>
            )}
            </div>
        )}
        </div>
    </AuthenticatedLayout>

  );
}
