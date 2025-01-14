<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Barcode - {{ $product->name }}</title>
    <!-- favicon link-->
    <link rel="shortcut icon" type="image/icon" href="{{ asset('images') }}/logo/{{ $website_info->fav_icon }}" />
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .actions {
            margin: 10px;
            text-align: center;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            padding: 10px;
        }

        .barcode-item {
            display: inline-block;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            width: {{$website_info->barcode_width}};
            height: {{$website_info->barcode_height}};
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .barcode-item h1 {
            margin: 0;
            font-size: 12px;
            font-weight: bold;
        } 
        .barcode-item span {
            margin: 0;
            font-size: 11px;
        }

        .barcode-item img {
            margin: 5px 0;
            max-width: 100%;
            height: auto;
        }

        .product-details span {
            display: block;
            font-size: 12px;
        }

        @media print {
            .actions {
                display: none; /* Hide buttons when printing */
            }

            body {
                background-color: white;
            }

            .container {
                box-shadow: none;
                justify-content: flex-start;
            }

            .barcode-item {
                margin: 0;
                page-break-inside: avoid;
            }
        }
        @media print {
            @page {
                size: auto; /* auto is the initial value */
                margin: 0; /* this affects the margin in the printer settings */
            }
        }
        .back-btn {
            padding: 2px 6px;
            border-radius: 3px;
            border: 1px solid gray;
            text-decoration: none;
            background: #F0F0F0;
            color: #000;
            font-size: 13.33px !important;
        }
        .barcode-item img {
            margin: 2px 0;
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <!-- Action Buttons -->
    <div class="actions">
        <form action="">
            <input type="number" name="qty" value="{{ request('qty') }}" placeholder="qty" style="width: 25%">
            <button type="submit">Submit</button>
            <button onclick="window.print()">Print Barcode</button>
            <a href="{{ route('products.index') }}" class="back-btn"> << Back </a>
        </form>
    </div>
    
    <!-- Barcode Container -->
    <div class="container">
        @for($i = 1; $i <= request('qty'); $i++)
        <div class="barcode-item">
            <!-- Company and Product Title -->
            <h1 class="company-name">{{ $website_info->name }}</h1>
            <span class="product-name">{{ $product->name }}</span>

            <!-- Barcode Section -->
            <div class="barcode-section">
                <img src="data:image/png;base64,{{ $product->barcodeBase64 }}" alt="Barcode for {{ $product->name }}" />
            </div>

            <!-- Product Details -->
            <div class="product-details">
                <span class="product-code">{{ $product->id }}</span>
                <span class="product-price">Price: {{ number_format($product->latestStockIn->sell_price, 2) }} {{ $website_info->currency }}</span>
            </div>
        </div>
        @endfor
    </div>
</body>
</html>
