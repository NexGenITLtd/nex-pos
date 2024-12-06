<!DOCTYPE html>
<html>
<head>
  <title>Product Barcode - {{ $product->name }}</title>
  <!-- favicon link-->
  <link rel="shortcut icon" type="image/icon" href="{{ asset('images') }}/logo/{{ $website_info->fav_icon }}" />
    <style>
        p.inline {
            font-family: Arial, Helvetica, sans-serif;
            display: inline-block;
            margin: 0;
        }
        span {
            font-size: 14px;
        }
        font {
            font-weight: bold;
            font-size: 14px;
        }
        @media print {
            @page {
                size: auto; /* auto is the initial value */
                margin: 0; /* this affects the margin in the printer settings */
            }
        }
        @media print {
            .actions {
                display: none; /* Hide buttons when printing */
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
<body onload="window.print();">
	<!-- Action Buttons -->
    <div class="actions">
        <form action="">
            <input type="number" name="qty" value="{{ request('qty') }}" placeholder="qty" style="width: 25%">
            <button type="submit">Submit</button>
            <button onclick="window.print()">Print Barcode</button>
            <a href="{{ route('products.index') }}" class="back-btn"> << Back </a>
        </form>
    </div>
    <div style="margin-left: 4%; width: 160px; height: 100px;">
        @for($i = 1; $i <= request('qty'); $i++)
            <p class="inline" 
               style="height: {{ $website_info->barcode_height }}px; 
                      width: {{ $website_info->barcode_width }}px; 
                      margin-top: 0;">
                <span>
                    <b>{{ $website_info->name }}</b><br>
                    <span style="font-size: 11px;">{{ $product->name }}</span><br>
                    <img src="data:image/png;base64,{{ $product->barcodeBase64 }}" 
                         alt="Barcode for {{ $product->name }}" /><br>
                    <b>Price: {{ number_format($product->price, 2) }} {{ $website_info->currency }}</b>
                </span>
            </p>
        @endfor
    </div>
</body>
</html>
