<!DOCTYPE html>
<html>
<head>
    <title>Data Excel</title>
</head>
<body>
    <h1>Data dari File Excel:</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Success</th>
                <th>Failed</th>
                <th>GMV</th>
                <th>Profit</th>
                <th>BABE</th>
                <th>Net Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row[0] ?? 'N/A' }}</td>
                    <td>{{ $row[1] ?? 'N/A' }}</td>
                    <td>{{ $row[2] ?? 'N/A' }}</td>
                    <td>{{ $row[3] ?? 'N/A' }}</td>
                    <td>{{ $row[4] ?? 'N/A' }}</td>
                    <td>{{ $row[5] ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
