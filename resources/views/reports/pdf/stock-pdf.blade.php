<!DOCTYPE html>
<html>
<head>
    <title>{{ $cardHeader }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $cardHeader }}</h2>
    </div>
    <p>Total Stock In Qty: {{ $total_stock_in_qty }}</p>
    <p>Total Available Stock Qty: {{ $total_available_stock_qty }}</p>
    <p>Total Available Stock Sell Price: {{ number_format($total_available_stock_sell_price, 2) }}</p>
    <p>Total Stock In Value: {{ number_format($total_stock_in_value, 2) }}</p>
    <p>Total Sold Qty: {{ $total_sold_qty }}</p>
    <p>Total Available Stock Profit: {{ number_format($total_available_stock_profit, 2) }}</p>
    <p>Total Available Stock Purchase Price: {{ number_format($total_available_stock_purchase_price, 2) }}</p>
</body>
</html>
