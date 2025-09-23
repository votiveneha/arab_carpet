@extends('web.common.layout') @section('content')
    <style>
        .select2-results__option,
        .select2-selection__rendered {
            text-transform: uppercase;
        }
    </style>
    {{-- <div class="breadcrumb">
        <div class="container">
            <ul class="breadcrumb-list" id="breadcrumbList">
                <li><a href="#"> {{ $product->brand_name }} {{ $product->model_name }}</a> &gt; </li>
                <li><a href="#">{{ $product->category_name }}</a> &gt; </li>
                <li><a href="#">{{ $product->subcategory_name }}</a></li>
            </ul>
            <!-- Scroll Arrow -->
            <div class="scroll-arrow" id="scrollArrow">›</div>
        </div>
    </div> --}}

    <div class="shop-detail-add shop-product-detail shop-product-list-add">
        <div class="container mt-4">
            <div class="row shop-inner-detail-content shop-product-inner text-center">
                <!-- Sidebar Filters -->
                {{-- <div class="col-md-3 filter-box shop-filters-left-side custom-select-wrapper">
                    <form method="get" action="{{ route('productList') }}">
                        @csrf
                        <input type="hidden" name="user_latitude" id="user_latitude">
                        <input type="hidden" name="user_longitude" id="user_longitude">

                        <h6 class="text-uppercase font-weight-bold">{{ $product->shop_name }}</h6>
                        <select class="form-control mb-2 custom-select select2" name="year" id="year">
                            <option value="">{{ __('messages.ALL_YEAR') }}</option>
                            @for ($year = date('Y'); $year >= 1960; $year--)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endfor
                        </select>
                        <select class="form-control mb-2 custom-select select2" id="brand" name="brand_id">
                            <option value="">{{ __('messages.ALL_MAKE') }}</option>
                            @if ($brand)
                                @foreach ($brand as $brands)
                                    <option value="{{ $brands->id }}"
                                        {{ $product->brand_id == $brands->id ? 'selected' : '' }}>
                                        {{ $brands->brand_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <select class="form-control mb-2 custom-select select2" id="model" name="model_id">
                            <option value="">{{ __('messages.ALL_MODEL') }}</option>
                            @if ($model)
                                @foreach ($model as $models)
                                    <option value="{{ $models->id }}"
                                        {{ $product->model_id == $models->id ? 'selected' : '' }}>
                                        {{ $models->model_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <select class="form-control mb-2 custom-select select2" id="category" name="category_id">
                            <option value="">{{ __('messages.ALL_PART_TYPE') }}</option>
                            @if ($category)
                                @foreach ($category as $categorys)
                                    <option value="{{ $categorys->id }}"
                                        {{ $product->category_id == $categorys->id ? 'selected' : '' }}>
                                        {{ $categorys->category_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <select class="form-control mb-2 custom-select select2" id="subcategory" name="subcategory_id">
                            <option value="">{{ __('messages.ALL_PART') }}</option>
                            @if ($subcategory)
                                @foreach ($subcategory as $subcategorys)
                                    <option value="{{ $subcategorys->id }}"
                                        {{ $product->subcategory_id == $subcategorys->id ? 'selected' : '' }}>
                                        {{ $subcategorys->subcat_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <!-- <select class="form-control mb-2 custom-select select2" id="country" name="country_id">
                                                                <option value="">ALL Country</option>
                                                                        @if ($country)
                    @foreach ($country as $countrys)
                    <option value="{{ $countrys->country_id }}">{{ $countrys->country_name }}</option>
                    @endforeach
                    @endif
                                                            </select> -->
                        {{-- <select class="form-control mb-2 custom-select select2" id="city" name="city_id">
                            <option value="">{{ __('messages.sector') }}</option>
                            @if ($city)
                                @foreach ($city as $citys)
                                    <option value="{{ $citys->city_id }}"
                                        {{ $user->city_id == $citys->city_id ? 'selected' : '' }}>{{ $citys->city_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select> --}}
                {{-- <button class="btn btn-dark btn-block shop-search-btn">{{ __('messages.search') }}</button> --}}
                <!-- <button class="btn btn-outline-secondary btn-block mt-2 shop-reset-btn">RESET</button> -->
                {{-- </form>
                </div> --}}

                <!-- Main Content -->
                <div class="col-md-12 shop-center-detail product-detail">
                    <div class="product-card">
                        <div class="pro_dtl">

                            <div class="inner-price-set">
                                <div class="card-header">
                                    {{-- <span class="status not-working">{{ $product->product_type == 1 ? 'EXCELLENT' : ($product->product_type == 2 ? 'GOOD' : 'NOT WORKING') }}</span> --}}



                                    <div class="price">
                                        <div class="pd_sty">
                                            {{ $product->brand_name }} {{ $product->model_name }}
                                            <span>{{ $product->subcategory_name }}</span>
                                        </div>
                                        <div class="pr_amd">
                                        @if ($product->product_price > 0) {{ $product->product_price }}
                                            <span>AED</span>
                                        @endif
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="seller-info">

                                    <div class="icons">
                                        {{-- <a href="https://wa.me/{{ $user->mobile }}?text={{ urlencode('Hello, I found your part on ARABCARPART: ' . $product->brand_name . '> ' . $product->model_name . '> ' . $product->category_name . '> ' . $product->subcategory_name . ' Stock #' . $product->stock_number . ' Is it still available?') }}"
                                        target="_blank">
                                        <i class="bi bi-whatsapp"></i></a> --}}

                                        <button class="s_icon"
                                            onclick="openWhatsApp(
                                    '{{ $product->mobile }}',
                                    '{{ $product->id }}',
                                    '{{ $product->brand_name }}',
                                    '{{ $product->model_name }}',
                                    '{{ $product->category_name }}',
                                    '{{ $product->subcategory_name }}',
                                    '{{ $product->stock_number }}',event)">
                                            <i class="bi bi-whatsapp"></i></button>
                                        {{-- First check + icon have or not --}}
                                        @php
                                            $phone = $user->mobile;
                                            if ($phone && substr($phone, 0, 1) !== '+') {
                                                $phone = '+' . $phone;
                                            }
                                        @endphp

                                        <a class="s_icon" href="tel:{{ $phone }}"><i
                                                class="bi bi-telephone"></i></a>

                                        <button class="s_icon" style="border: none;"
                                            onclick="openMap('{{ $product->user_lat }}','{{ $product->user_long }}', event)">
                                            <i class="bi bi-geo-alt"></i>
                                        </button>
                                    </div>
                                    <div class="mt-3 text-center ">
                                        <a
                                            href="{{ route('shopDetail', ['city' => $user->city_name, 'shop' => $product->shop_name_en]) }}">
                                            <div style="color:#000000; font-weight: 700;" class="vendor-name"">
                                                {{ $product->shop_name }}
                                            </div>
                                        </a>
                                        <p class="loc-add-text text-center">{{ $user->city_name }}</p>

                                    </div>
                                </div>
                            </div>

                            <div class="product-img-part">
                                <div class="description">
                                    <h4 class="title">{{ $product->product_note }}</h4>

                                    <p><strong>{{ __('messages.Description') }} </strong></p>
                                    <p>
                                        {{ $product->product_description }}
                                    </p>
                                </div>

                                <div class="product-body">
                                    <img src="{{ asset('/public/uploads/product_image/' . $product->product_image) }}"
                                        alt="product" />
                                </div>
                            </div>
                        </div>

                        <div class="features">
                            @if ($service)
                                @foreach ($service as $services)
                                    @if ($services->service_id == 1)
                                        <div class="feature">
                                            <span>{{ $services->service_name }}</span>
                                            <i class="bi bi-truck"></i>
                                        </div>
                                    @elseif($services->service_id == 2)
                                        <div class="feature">
                                            <span>{{ $services->service_name }}</span>
                                            <i class="bi bi-globe"></i>
                                        </div>
                                    @elseif($services->service_id == 3)
                                        <div class="feature">
                                            <span>{{ $services->service_name }}</span>
                                            <i class="bi bi-wrench"></i>
                                        </div>
                                    @else
                                        <div class="feature">
                                            <span>{{ $services->service_name }}</span>
                                            <i class="bi bi-award"></i>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="fits" style="border-bottom: 1px solid #eee;">
                            <h4 class="title" style="text-align: left;"> {{ __('messages.THIS_FITS') }}</h4>
                            {{-- <p><span class="year">{{ $product->start_year }} - {{ $product->end_year }}</span> <span
                                    class="brand">{{ $product->brand_name }}</span> <span
                                    class="part">{{ $product->model_name }}</span></p> --}}

                            <div class="fit_txt">
                                {{ $product->brand_name }} {{ $product->model_name }}
                                <span class="year">( {{ $product->start_year }} - {{ $product->end_year }})
                                </span>
                            </div>
                        </div>


                        @if ($similarProducts->isNotEmpty())
                            <div class="fits mt-3 shop-product-list-right" style="border-bottom: 1px solid #eee;">
                                <div class="product-listing shop-mini-product-add">
                                    <h4 class="title" style="text-align: left;"> {{ __('messages.ALSO_THIS_FITS') }} </h4>
                                    @foreach ($similarProducts as $product)
                                        <div class="listing-item"
                                            onclick="redirectToDetail('{{ route('productDetail', $product->id) }}')"
                                            style="cursor:pointer;">
                                            <div class="new-ven-add">
                                                <div class="vendor-info">
                                                    <div class="vendor-name">
                                                        <a href="{{ route('productDetail', $product->id) }}"
                                                            class="track-product-click"
                                                            data-product-id="{{ $product->id }}"
                                                            data-seller-id="{{ $product->seller_id }}"
                                                            data-admin-product-id="{{ $product->admin_product_id }}">
                                                            {{ $product->brand_name }} {{ $product->model_name }}
                                                            <span>{{ $product->subcat_name }}</span>
                                                        </a>
                                                    </div>
                                                    <span class="name-part-add">{{ $product->category_name }}</span>
                                                    <div class="vendor-icons">
                                                        <div class="vendor-city">{{ $user->city_name }}
                                                            @if ($product->product_image != '')
                                                                <a href="{{ asset('/public/uploads/product_image/' . $product->product_image) }}"
                                                                    target="_blank">
                                                                    <i class="bi bi-camera-fill"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-details">
                                                <p>{{ $product->product_note }}</p>
                                            </div>
                                            <div class="product-status">
                                                {{-- {{ $product->product_type == 1 ? 'EXCELLENT' : ($product->product_type == 2 ? 'GOOD' : 'NOT WORKING') }} --}}
                                            </div>
                                            <div class="contact-options">
                                                <div class="product-price">
                                                    @if ($product->product_price > 0)
                                                        {{ $product->product_price }} AED
                                                    @endif
                                                </div>
                                                <div class="icons-part-add">
                                                    {{-- <a href="https://wa.me/{{ $user->mobile }}?text={{ urlencode('Hello, I found your part on ARABCARPART: ' . $product->brand_name . '> ' . $product->model_name . '> ' . $product->category_name . '> ' . $product->subcat_name . ' Stock #' . $product->stock_number . ' Is it still available?') }}"
                                                        target="_blank">
                                                        <button><i class="bi bi-whatsapp"></i></button>
                                                    </a> --}}
                                                    <button class="s_icon"
                                                        onclick="openWhatsApp(
                                                            '{{ $product->mobile }}',
                                                            '{{ $product->id }}',
                                                            '{{ $product->brand_name }}',
                                                            '{{ $product->model_name }}',
                                                            '{{ $product->category_name }}',
                                                            '{{ $product->subcategory_name }}',
                                                            '{{ $product->stock_number }}',event)">
                                                        <i class="bi bi-whatsapp"></i></button>
                                                    <a class="s_icon" href="tel:{{ $user->mobile }}">
                                                        <button><i class="bi bi-telephone"></i></button>
                                                    </a>
                                                    <button class="s_icon"
                                                        onclick="openMap('{{ $product->user_lat }}','{{ $product->user_long }}', event)">
                                                        <i class="bi bi-geo-alt"></i>
                                                    </button>
                                                    {{-- <button><i class="bi bi-geo-alt"></i></button> --}}

                                                    <a
                                                        href="{{ route('shopDetail', ['city' => $user->city_name, 'shop' => $product->shop_name_en]) }}">
                                                        <div style="color:#000000" class="vendor-name"">
                                                            {{ $product->shop_name }}
                                                        </div>
                                                    </a>
                                                    <div class="vendor-city city-add-text">
                                                        {{ $user->city_name }}
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection @push('scripts')
    <script>
        function openWhatsApp(mobile, id, brand, model, category, subcategory, stockNumber, event) {
            event.stopPropagation();
            event.preventDefault();
            const productLink = `${window.location.origin}/productDetail/${id}`;
            const message =
                `Hello, I found your part on ARABCARPART:
         ${brand} > ${model} > ${category} > ${subcategory}
        ${productLink}
        Is it still available?`;

            const url = `https://wa.me/${mobile}?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
        }





        function redirectToDetail(url) {
            // prevent redirect when clicking on inner <a> or <button>
            if (event.target.closest('a') || event.target.closest('button')) {
                return;
            }
            window.location.href = url;
        }
    </script>



    <script>
        $(document).ready(function() {
            $('.select2').select2({
                // placeholder: "Select an option",
                // allowClear: true
            });
        });


        // open map code
        function openMap(latitude, longitude, event) {
            event.stopPropagation();
            event.preventDefault();

            //alert("Latitude: " + latitude + " | Longitude: " + longitude);
            let url = `https://www.google.com/maps?q=${latitude},${longitude}`;
            window.open(url, '_blank');
        }
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
        $(document).ready(function() {
            $(document).on('change', '#brand', function() {

                var cid = this.value; //let cid = $(this).val(); we cal also write this.
                $.ajax({
                    url: "{{ url('/admin/getModel') }}",
                    type: "POST",
                    datatype: "json",
                    data: {
                        brand_id: cid,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#model').html(
                            '<option value="">{{ __('messages.ALL_MODEL') }}</option>');
                        $.each(result.city, function(key, value) {
                            $('#model').append('<option value="' + value.id + '">' +
                                value.model_name + '</option>');
                        });
                    },
                    errror: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $("#country").change(function() {
                var sid = this.value;
                $.ajax({
                    url: "{{ url('/admin/getcityByCountry') }}",
                    type: "POST",
                    datatype: "json",
                    data: {
                        country_id: sid,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(result) {
                        console.log(result);
                        $("#city").html('<option value="">ALL Location</option>');
                        $.each(result.city, function(key, value) {
                            $("#city").append('<option value="' + value.city_id + '">' +
                                value.city_name + "</option>");
                        });
                    },
                    errror: function(xhr) {
                        console.log(xhr.responseText);
                    },
                });
            });

            $('#category').change(function() {
                var sid = this.value;
                $.ajax({
                    url: "{{ url('/admin/getSubcategory') }}",
                    type: "POST",
                    datatype: "json",
                    data: {
                        category_id: sid,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        console.log(result);
                        $('#subcategory').html(
                            '<option value="">{{ __('messages.ALL_PART') }}</option>');
                        $.each(result.subcat, function(key, value) {
                            $('#subcategory').append('<option value="' + value.id +
                                '">' + value.subcat_name + '</option>')
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
