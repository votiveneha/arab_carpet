@extends('web.common.layout') @section('content')
<style>
     .shop-detail-add.shop-product-detail.shop-product-list-add .select2-results__option,
        .select2-selection__rendered {
          text-transform: uppercase;
        }
.shop-detail-add.shop-product-detail.shop-product-list-add .card {
    height: 100%;
    border: 1px solid #ccc !important;
    border-radius: 12px;
    padding: 10px;
    position: relative;
    margin: 10px;
    width: 93%;
    margin-top: 15px;
}

       .shop-detail-add.shop-product-detail.shop-product-list-add .qr {
            position: absolute;
            right: 40px;
            top: 40px;
            width: 160px;
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

        .card.bar-code-add h1 {
            font-size: 18px;
            margin: 0 0 5px;
            color: #002439;
        }

        .card.bar-code-add .location {
            font-size: 13px;
            color: #555;
        }

        .card.bar-code-add .info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 8px;
            font-size: 15px;
            color: #222;
        }
.card.bar-code-add .xyz {
    width: 90%;
    line-height: 1.7;
    font-size: 12px;
}

        .card.bar-code-add .qr-img {
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

.card.bar-code-add .footer {
    margin-top: 10px;
    font-weight: bold;
    font-size: 14px;
    color: #002439;
}

.card.bar-code-add .icons {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-top: 0px;
}
      .card.bar-code-add .new-add-right-icon {
            display: flex;
            gap: 16px;
            font-size: 18px;
        }

        .card.bar-code-add .shop-link-add {
            font-size: 13px;
            color: #002439;
            word-break: break-word;
            margin-top: 10px;
            display: flex;
            gap: 6px;
        }

.card.bar-code-add .shop-url {
    max-width: 90%;
    font-size: 12px;
}

        .card.bar-code-add i.bi {
            vertical-align: middle;
        }


.bar-code-img-add img {
    width: 100%;
    float: right;
    margin-top: -18px;
}

.bar-code-img-add {
    width: 25%;
}
button.down-card-btn {
    background-color: #0f4432;
    border: none;
    color: #fff;
    width: 100%;
    padding: 11px 0;
    border-radius: 5px;
}


.shop-detail-add .contact-info .social-icons.mt-2 {
    align-items: center;
}


</style>
    <div class="breadcrumb">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="#">DIRECTORY</a> / </li>
                <li><a href="{{ route('shopDetail', ['city' => $user->city_name, 'shop' => $shop->shop_name]) }}">
                {{$shop->shop_name}}</a></li>
            </ul>
        </div>
    </div>

    <div class="shop-detail-add shop-product-detail shop-product-list-add">
        <div class="container mt-4">
            <div class="row shop-inner-detail-content shop-product-inner">
            <!-- Sidebar Filters -->
            <div class="col-md-3 filter-box shop-filters-left-side custom-select-wrapper">
                <form method="get" action="{{ route('sellerMiniPage', ['id' => $user->id]) }}">
                    @csrf
                    <input type="hidden" name="user_latitude" id="user_latitude">
                    <input type="hidden" name="user_longitude" id="user_longitude">

                    <h6 class="text-uppercase font-weight-bold">{{__('messages.filter_head1')}}</h6>
                    <select class="form-control mb-2 custom-select select2" name="year" id="year">
                        <option value="">{{__('messages.YEAR')}}</option>
                        @for ($year = date('Y'); $year >= 1960; $year--)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <select class="form-control mb-2 custom-select select2" id="brand" name="brand_id">
                        <option value="">{{__('messages.MAKE')}}</option>
                            @if($brand)
                            @foreach($brand as $brands)
                                <option value="{{$brands->id}}" {{ request('brand_id') == $brands->id ? 'selected' : '' }}>{{$brands->brand_name}}</option>
                            @endforeach
                            @endif
                    </select>
                    <select class="form-control mb-2 custom-select select2" id="model" name="model_id">
                        <option value="">{{__('messages.MODEL')}}</option>
                            @if($model)
                            @foreach($model as $models)
                                <option value="{{$models->id}}" {{ request('model_id') == $models->id ? 'selected' : '' }}>{{$models->model_name}}</option>
                            @endforeach
                            @endif
                    </select>
                    <select class="form-control mb-2 custom-select select2"  id="category" name="category_id">
                        <option value="">{{__('messages.PART TYPE')}}</option>
                        @if($category)
                        @foreach($category as $categorys)
                            <option value="{{$categorys->id}}" {{ request('category_id') == $categorys->id ? 'selected' : '' }}>{{$categorys->category_name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <select class="form-control mb-2 custom-select select2"  id="subcategory" name="subcategory_id">
                        <option value="">{{__('messages.PART')}}</option>
                        @if($subcategory)
                        @foreach($subcategory as $subcategorys)
                            <option value="{{$subcategorys->id}}" {{ request('subcategory_id') == $subcategorys->id ? 'selected' : '' }}>{{$subcategorys->subcat_name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <!-- <select class="form-control mb-2 custom-select select2" id="country" name="country_id">
                        <option value="">ALL Country</option>
                                @if($country)
                                    @foreach($country as $countrys)
                                        <option value="{{ $countrys->country_id }}">{{ $countrys->country_name }}</option>
                                    @endforeach
                                @endif
                    </select> -->
                    <select class="form-control mb-2 custom-select select2"  id="city" name="city_id">
                        <option value="">{{__('messages.sector')}}</option>
                            @if($city)
                                @foreach($city as $citys)
                                    <option value="{{ $citys->city_id }}" {{ request('city_id') == $citys->city_id ? 'selected' : '' }}>{{ $citys->city_name }}</option>
                                @endforeach
                            @endif
                    </select>
                    <button class="btn btn-dark btn-block shop-search-btn">{{__('messages.search')}}</button>
                    <!-- <button class="btn btn-outline-secondary btn-block mt-2 shop-reset-btn">RESET</button> -->
                    </form>
            </div>


            <!-- Main Content -->
            <div class="col-md-6 shop-center-detail">
                <h3 class="font-weight-bold">{{$shop->shop_name}}</h3>
                @if($shop->shop_banner)
                <img src="{{ asset('/public/uploads/shop_image/' . $shop->shop_banner) }}" alt="Shop" class="img-fluid mb-3" />
                @else
                    <img src="{{ asset('/public/web_assets/images/shop-img.png') }}" alt="Shop" class="img-fluid mb-3" />
                @endif
                <p>
                    {{$shop->about_shop}}
                </p>
                <!-- <a href="#" class="read-more-add">READ MORE</a> -->
            
            </div>

            <!-- Contact Info -->
            <div class="col-md-3 contact-info">





           <div class="card bar-code-add" id="business_card" style="width: 308px; background: white; padding: 20px; border-radius: 0px;">
                <h1>{{ $shop->shop_name }}</h1>
                <div class="location">{{ $user->city_name }}, {{ $user->country_name }}</div>
        
                <div class="info">
                    <div class="xyz">
                        <div>📞 {{ $user->mobile }}</div>
                        <div>💬 WhatsApp</div>
                        <div class="shop-link-add">
                            🔗
                            <span class="shop-url">
                             {{$shop_url}} </span>
                        </div>
                    </div>
                    <div class="bar-code-img-add">
                        <img src="{{ asset('/public/uploads/qr_code/'. $shop->qr_code) }}" alt="Parts Rack" class="img-fluid mb-3" />
                        
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





                
                
                
                <div class="info-text">
                    <p class="mb-1 font-weight-bold address-text-add">{{$user->address1.' , '.$user->address2.' , '.$user->zip_code.' , '.$user->city_name.' , '.$user->country_name}}</p>

                    <p class="mb-1 phone-text">PHONE: <a href="tel:+{{$user->mobile}}" class="phone-no-add" target="_blank">{{$user->mobile}}</a></p>
                    <p class="mb-1 phone-text">WHATSAPP: <a href="https://wa.me/{{$user->mobile}}" class="phone-no-add" target="_blank">{{$user->mobile}}</a></p>
                    <div class="social-icons mt-2">
                        <a href="https://wa.me/{{$user->mobile}}" target="_blank"><i class="bi bi-whatsapp"></i></a>
                        <a href="tel:+{{$user->mobile}}"><i class="bi bi-telephone-fill"></i></a>
                        <a href="#"><i class="bi bi-geo-alt-fill"></i></a>
                        <button onclick="downloadCard()" class="down-card-btn">Download</button>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
</div>

@endsection @push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    function downloadCard() {
        const card = document.getElementById('business_card');
        html2canvas(card, {
            scale: 2,  // higher quality
            useCORS: true
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'business_card.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            // placeholder: "Select an option",
            // allowClear: true
        });
    });
</script>
<script>
    /*
    document.addEventListener("DOMContentLoaded", function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    console.log("Detected location:", lat, lng);

                    document.getElementById("user_latitude").value = lat;
                    document.getElementById("user_longitude").value = lng;
                },
                function (error) {
                    console.warn("Geolocation error:", error.message);

                    // Show user-friendly alert
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            alert("Please allow location access to use this feature.");
                            break;
                        case error.POSITION_UNAVAILABLE:
                            alert("Location information is unavailable.");
                            break;
                        case error.TIMEOUT:
                            alert("Location request timed out.");
                            break;
                        default:
                            alert("An unknown error occurred while getting your location.");
                    }
                }
            );
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    });
*/
    $(document).ready(function () {
    $(document).on('change', '#brand', function () {
       
        var cid = this.value;   //let cid = $(this).val(); we cal also write this.
        $.ajax({
        url: "{{url('/admin/getModel')}}",
        type: "POST",
        datatype: "json",
        data: {
            brand_id: cid,
            '_token':'{{csrf_token()}}'
        },
        success: function(result) {
            $('#model').html('<option value="">{{ __("messages.MODEL") }}</option>');
            $.each(result.city, function(key, value) {
            $('#model').append('<option value="' +value.id+ '">' +value.model_name+ '</option>');
            });
        },
        errror: function(xhr) {
            console.log(xhr.responseText);
            }
        });
    });

    $("#country").change(function () {
            var sid = this.value;
            $.ajax({
                url: "{{url('/admin/getcityByCountry')}}",
                type: "POST",
                datatype: "json",
                data: {
                    country_id: sid,
                    _token: "{{csrf_token()}}",
                },
                success: function (result) {
                    console.log(result);
                    $("#city").html('<option value="">ALL Location</option>');
                    $.each(result.city, function (key, value) {
                        $("#city").append('<option value="' + value.city_id + '">' + value.city_name + "</option>");
                    });
                },
                errror: function (xhr) {
                    console.log(xhr.responseText);
                },
            });
        });

        $('#category').change(function () {
            var sid = this.value;
            $.ajax({
            url: "{{url('/admin/getSubcategory')}}",
            type: "POST",
            datatype: "json",
            data: {
                category_id: sid,
                '_token':'{{csrf_token()}}'
            },
            success: function(result) {
                console.log(result);
                $('#subcategory').html('<option value="">{{ __("messages.PART") }}</option>');
                $.each(result.subcat, function(key, value) {
                $('#subcategory').append('<option value="' +value.id+ '">' +value.subcat_name+ '</option>')
                });
            },
            errror: function(xhr) {
                console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endpush
