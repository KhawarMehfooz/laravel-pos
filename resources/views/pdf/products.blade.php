<!DOCTYPE html>
<html>

<head>
    <title>Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Products</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Barcode</th>
                <th>Stock</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{!! DNS1D::getBarcodeHTML($product->barcode, 'C128') !!}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->category->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>