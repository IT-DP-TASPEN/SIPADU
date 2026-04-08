<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Export User SIPADU</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th colspan="12">Daftar User SIPADU</th>
            </tr>
            <tr>
                <th colspan="12">Generated at: {{ $generatedAt->format('Y-m-d H:i:s') }}</th>
            </tr>
            <tr>
                <th>Nama</th>
                <th>Username</th>
                <th>ID Karyawan</th>
                <th>Email</th>
                <th>Nomor HP</th>
                <th>Jabatan</th>
                <th>Unit</th>
                <th>Divisi</th>
                <th>Tipe Kantor</th>
                <th>Kode Cabang</th>
                <th>Nama Cabang</th>
                <th>Admin Portal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->employee_id }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->title }}</td>
                    <td>{{ $user->unit_name }}</td>
                    <td>{{ $user->division_name }}</td>
                    <td>{{ $user->office_type }}</td>
                    <td>{{ $user->branch_code }}</td>
                    <td>{{ $user->branch_name }}</td>
                    <td>{{ $user->isAdmin() ? 'Ya' : 'Tidak' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
