import React from "react";
import { Link, usePage, router } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Index({ users, auth }) {
  const { flash } = usePage().props;

  return (
    <AuthenticatedLayout user={auth.user} header={<h2>Manajemen User</h2>}>
      <div className="p-6 bg-white shadow rounded">
        {flash?.success && (
          <div className="p-2 mb-4 bg-green-200 text-green-800 rounded">
            {flash?.success}
          </div>
        )}
        <div className="flex justify-between mb-4">
          <h1 className="text-xl font-bold">Daftar User</h1>
          <Link
            href={route("users.create")}
            className="bg-blue-600 text-white px-4 py-2 rounded"
          >
            Tambah User
          </Link>
        </div>
        <table className="w-full border">
          <thead>
            <tr className="bg-gray-100">
              <th className="p-2 border">ID</th>
              <th className="p-2 border">Nama</th>
              <th className="p-2 border">Email</th>
              <th className="p-2 border">Role</th>
              <th className="p-2 border">Aksi</th>
            </tr>
          </thead>
          <tbody>
            {users.data.map((user) => (
              <tr key={user.id}>
                <td className="border p-2">{user.id}</td>
                <td className="border p-2">{user.name}</td>
                <td className="border p-2">{user.email}</td>
                <td className="border p-2">{user.role}</td>
                <td className="border p-2 space-x-2">
                  <Link
                    href={route("users.edit", user.id)}
                    className="text-blue-600"
                  >
                    Edit
                  </Link>
                  <button
                    className="text-red-600"
                    onClick={() => {
                      if (confirm("Yakin ingin menghapus user ini?")) {
                        router.delete(route("users.destroy", user.id));
                      }
                    }}
                  >
                    Hapus
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </AuthenticatedLayout>
  );
}
