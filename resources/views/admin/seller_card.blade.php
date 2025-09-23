<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 530px;
            height: 300px;
            background: #ffffff;
            font-family: Arial, sans-serif;
        }

        .card.bar-code-add {
            width: 460px;
            padding: 30px;
            background: #f9f9f9;
            border: 2px solid #ccc;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 5px auto;
            position: relative;
        }

        h1 {
            font-size: 22px;
            margin: 0 0 5px;
            color: #002439;
        }

        .location {
            font-size: 14px;
            color: #555;
        }

        .info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 20px;
            font-size: 15px;
            color: #222;
        }

        .xyz {
            width: 65%;
            line-height: 1.7;
        }

        .qr-img {
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .footer {
            margin-top: 20px;
            font-weight: bold;
            font-size: 16px;
            color: #002439;
        }

        .icons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
        }

        .new-add-right-icon {
            display: flex;
            gap: 16px;
            font-size: 18px;
        }

        .shop-link-add {
            font-size: 13px;
            color: #002439;
            word-break: break-word;
            margin-top: 10px;
            display: flex;
            gap: 6px;
        }

        .shop-url {
            max-width: 90%;
        }

        i.bi {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="card bar-code-add">
        <h1>{{ $shop->shop_name }}</h1>
        <div class="location">{{ $user->city_name }}, {{ $user->country_name }}</div>

        <div class="info">
            <div class="xyz">
                <div>📞 {{ $user->mobile }}</div>
                <div>💬 WhatsApp</div>
                <div class="shop-link-add">
                    🔗
                    <span class="shop-url">
                        {{ $shop_url }}
                    </span>
                </div>
            </div>
            <div>
                <img class="qr-img" src="{{ $qrPath }}" alt="QR Code">
                {{--<img class="qr-img" src="{{ asset('public/uploads/qr_code/qr_sagar_1753443975.png') }}" alt="QR Code">--}}
            </div>
        </div>

        <div class="icons">
            <div class="footer">ArabCarPart</div>
            <div class="new-add-right-icon">
                 @if($service)
                @foreach($service as $services)
                    @if($services->service_id==1)
                        <i class="bi bi-truck" data-bs-toggle="tooltip" title="Delivery inside country"></i>
                    @elseif($services->service_id==2)
                        <i class="bi bi-globe" data-bs-toggle="tooltip" title="Delivery outside country"></i>
                    @elseif($services->service_id==3)
                        <i class="bi bi-wrench" data-bs-toggle="tooltip" title="Installation available"></i>
                    @else
                        <i class="bi bi-award" data-bs-toggle="tooltip" title="Warranty provided"></i>
                    @endif
                @endforeach
                @endif
            </div>
        </div>
    </div>
</body>
</html>
