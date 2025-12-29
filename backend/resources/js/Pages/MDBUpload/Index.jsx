import React, { useState } from "react";
import axios from "axios";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function ImportMDB() {
    const [file, setFile] = useState(null);
    const [progress, setProgress] = useState(0);
    const [status, setStatus] = useState("");

    const handleUpload = async (e) => {
        e.preventDefault();

        if (!file) {
            alert("Silakan pilih file .mdb");
            return;
        }

        let formData = new FormData();
        formData.append("mdb_file", file);

        setProgress(0);
        setStatus("Uploading...");

        try {
            const response = await axios.post(route("mdb.upload"), formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
                onUploadProgress: (event) => {
                    const percent = Math.round((event.loaded * 100) / event.total);
                    setProgress(percent);
                },
            });

            setStatus(response.data.message);
        } catch (error) {
            console.error(error);
            setStatus("Upload gagal.");
        }
    };

    return (
        <AuthenticatedLayout
                        user={auth.user}
                        header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
                    >
        <div className="p-6 bg-white rounded shadow max-w-lg mx-auto mt-8">
            <h2 className="text-xl font-bold mb-4">Import Database MS Access (.mdb)</h2>
            <div className="warning">Pastikan Mesin absensi telah diputus koneksinya dari jaringan, sebelum mengimport file</div>
            <form onSubmit={handleUpload} className="space-y-4">

                <div>
                    <label className="block mb-1">Pilih File (.mdb)</label>
                    <input
                        type="file"
                        accept=".mdb"
                        onChange={(e) => setFile(e.target.files[0])}
                        className="border p-2 w-full"
                    />
                </div>

                {progress > 0 && (
                    <div>
                        <label className="block mb-1">Progress Upload</label>
                        <div className="w-full bg-gray-200 h-3 rounded">
                            <div
                                className="bg-blue-600 h-3 rounded"
                                style={{ width: `${progress}%` }}
                            ></div>
                        </div>
                        <p className="text-sm mt-1">{progress}%</p>
                    </div>
                )}

                <button
                    type="submit"
                    className="bg-green-600 text-white px-4 py-2 rounded"
                >
                    Upload & Import
                </button>
            </form>

            {status && <p className="mt-4 text-blue-700 font-semibold">{status}</p>}
        </div>
        </AuthenticatedLayout>
    );
}
