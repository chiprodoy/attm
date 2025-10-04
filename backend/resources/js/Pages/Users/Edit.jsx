import React from "react";
import { useForm, Link } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Edit({ user, auth }) {
  const { data, setData, put, processing, errors } = useForm({
    name: user.name || "",
    email: user.email || "",
    password: "",
    role: user.role || "user",
  });

  const submit = (e) => {
    e.preventDefault();
    put(route("users.update", user.id));
  };

  return (
    <AuthenticatedLayout user={auth.user} header={<h2>Edit User</h2>}>
      <form onSubmit={submit} className="p-6 bg-white shadow rounded space-y-4">
        <div>
          <label>Nama</label>
          <input
            type="text"
            value={data.name}
            onChange={(e) => setData("name", e.target.value)}
            className="border p-2 w-full"
          />
          {errors.name && <div className="text-red-600">{errors.name}</div>}
        </div>

        <div>
          <label>Email</label>
          <input
            type="email"
            value={data.email}
            onChange={(e) => setData("email", e.target.value)}
            className="border p-2 w-full"
          />
          {errors.email && <div className="text-red-600">{errors.email}</div>}
        </div>

        <div>
          <label>Password (kosongkan jika tidak diubah)</label>
          <input
            type="password"
            value={data.password}
            onChange={(e) => setData("password", e.target.value)}
            className="border p-2 w-full"
          />
          {errors.password && <div className="text-red-600">{errors.password}</div>}
        </div>

        <div>
          <label>Role</label>
          <select
            value={data.role}
            onChange={(e) => setData("role", e.target.value)}
            className="border p-2 w-full"
          >
            <option value="2">Admin</option>
            <option value="3">Petugas Medis</option>
            <option value="4">HRD</option>
          </select>
        </div>

        <div>
          <button
            type="submit"
            disabled={processing}
            className="bg-blue-600 text-white px-4 py-2 rounded"
          >
            Update
          </button>
          <Link href={route("users.index")} className="ml-2 underline">
            Batal
          </Link>
        </div>
      </form>
    </AuthenticatedLayout>
  );
}
