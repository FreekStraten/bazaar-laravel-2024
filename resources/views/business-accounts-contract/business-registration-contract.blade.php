<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Registration Contract</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        h1, h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>Business Registration Contract</h1>

<h2>User Details</h2>
<table>
    <tr>
        <th>Name</th>
        <td>{{ $user->name }}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{ $user->email }}</td>
    </tr>
    <tr>
        <th>User Type</th>
        <td>{{ $user->user_type }}</td>
    </tr>
    <tr>
        <th>User Id</th>
        <td>{{ $user->id }}</td>
    </tr>
    @if ($user->address)
        <tr>
            <th>Address</th>
            <td>
                {{ $user->address->street }} {{ $user->address->house_number }}<br>
                {{ $user->address->zip_code }} {{ $user->address->city }}
            </td>
        </tr>
    @endif
</table>

<p>By signing this contract, you agree to our terms and conditions.</p>

<p>Signature: _______________________________________</p>

<p>Date: {{ now()->format('Y-m-d') }}</p>
</body>
</html>
