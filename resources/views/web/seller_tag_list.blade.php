@extends('web.common.layout')
@section('content')
    <style>
        .select2-results__option,
        .select2-selection__rendered {
            text-transform: uppercase;
        }

        .shop-product-list-right .vendor-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
            width: 368px;
        }

        .shop-product-list-right .vendor-icons {
            display: flex;
            justify-content: flex-start;
        }

        .ndf_sty {
            margin: 200px 50px;
            text-align: center;
        }

        .request_part_btn {
            background-color: #0f4432;
            border: none;
            border-radius: 5px;
            color: #fff;
            display: flex;
            align-items: center;
            height: 45px;
            justify-content: center;
            font-size: 20px;
            font-weight: 500;
            padding: 20px 30px;
            margin: 0 auto;
            margin-top: 40px;
        }

        @media only screen and (max-width: 767px) {
            .shop-product-list-right .vendor-info {
                display: flex;
                width: 100% !important;
            }

            .filter-box.shop-filters-left-side.custom-select-wrapper {
                width: 88%;
            }


            .shop-center-detail.shop-product-list-right {
                padding-top: 0px;
            }
        }
    </style>

    {{-- <div class="breadcrumb">
        <div class="container">
            <ul class="breadcrumb-list" id="breadcrumbList">
                <li>
                    <a href="#">
                        {{ !empty($header['year']) ? $header['year'] : __('messages.ALL_YEAR') }}
                        {{ !empty($header['brand']) ? $header['brand'] : __('messages.ALL_MAKE') }}
                        {{ !empty($header['model']) ? $header['model'] : __('messages.ALL_MODEL') }}
                    </a> &gt;
                </li>
                <li>
                    <a href="#">{{ !empty($header['category']) ? $header['category'] : __('messages.ALL_PART_TYPE') }}</a> &gt;
                </li>
                <li>
                    <a href="#">{{ !empty($header['subcatagory']) ? $header['subcatagory'] :  __('messages.ALL_PART')  }}</a>
                </li>
            </ul>
            <!-- Scroll Arrow -->
            <div class="scroll-arrow" id="scrollArrow">›</div>
        </div>
    </div> --}}



    <div class="shop-detail-add shop-product-detail shop-product-list-add">
        <div class="container mt-4">
            <div class="row shop-inner-detail-content shop-product-inner">
                <!-- Sidebar Filters -->

                <div class="col-md-3 filter-box shop-filters-left-side custom-select-wrapper">



                    <form method="get" action="{{ route('sellerTagList') }}">
                        @csrf
                        <h6 class="text-uppercase font-weight-bold">{{ __('messages.filter_head1') }}</h6>

                        <input type="hidden" name="user_latitude" id="user_latitude">
                        <input type="hidden" name="user_longitude" id="user_longitude">


                        <select class="form-control mb-2 custom-select select2" name="year" id="year">
                            <option value="">{{ __('messages.ALL_YEAR') }}</option>
                            @if ($make_year)
                                @foreach ($make_year as $make_years)
                                    <option value="{{ $make_years->year_english }}"
                                        {{ request('year') == $make_years->year_english ? 'selected' : '' }}>
                                        {{ $make_years->year_english }}</option>
                                @endforeach
                            @endif
                        </select>


                        <select class="form-control mb-2 custom-select select2" name="parent_id" id="parent_id">
                            <option value="">{{ __('messages.all_car_type') }}</option>
                            @if ($parents)
                                @foreach ($parents as $parent)
                                    <option value="{{ $parent->id }}"
                                        {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->mparents_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>

                        <select class="form-control mb-2 custom-select select2" id="brand" name="brand_id">
                            <option value="">{{ __('messages.ALL_MAKE') }}</option>
                            @if ($brand)
                                @foreach ($brand as $brands)
                                    <option value="{{ $brands->id }}"
                                        {{ request('brand_id') == $brands->id ? 'selected' : '' }}>
                                        {{ $brands->brand_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>

                        <select class="form-control mb-2 custom-select select2" id="model" name="model_id">
                            <option value="">{{ __('messages.ALL_MODEL') }}</option>
                            @foreach ($models as $model)
                                <option value="{{ $model->id }}"
                                    {{ request('model_id') == $model->id ? 'selected' : '' }}>
                                    {{ $model->model_name }}
                                </option>
                            @endforeach
                        </select>

                        <select class="form-control mb-2 custom-select select2" id="category" name="category_id">
                            <option value="">{{ __('messages.ALL_PART_TYPE') }}</option>
                            @if ($category)
                                @foreach ($category as $categorys)
                                    <option value="{{ $categorys->id }}"
                                        {{ request('category_id') == $categorys->id ? 'selected' : '' }}>
                                        {{ $categorys->category_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>

                        <select class="form-control mb-2 custom-select select2" id="subcategory" name="subcategory_id">
                            <option value="">{{ __('messages.ALL_PART') }}</option>
                            @foreach ($subcategory as $subcategorys)
                                <option value="{{ $subcategorys->id }}"
                                    {{ request('subcategory_id') == $subcategorys->id ? 'selected' : '' }}>
                                    {{ $subcategorys->subcat_name }}
                                </option>
                            @endforeach
                        </select>

                        <button class="btn btn-dark btn-block shop-search-btn">{{ __('messages.search') }}</button>
                    </form>

                </div>

                <!-- Main Content -->
                <div class="col-md-9 shop-center-detail shop-product-list-right px-4">
                    <div class="product-listing">

                        {{-- {{ $sellerTagList }} --}}
                        <!-- Example static product item -->
                        @if ($sellerTagList->isNotEmpty())
                            @foreach ($sellerTagList as $seller)
                            
                            @if($seller->make_id == NULL)
                            
                            @php
                                
                                $make = DB::table('mparent_brand')
                                    ->where('mparents_id', $seller->parent_id)
                                    ->get();
                                    
                                $service = DB::table('seller_service')
                                        ->where('seller_id', $seller->seller_id)
                                        ->join('services', 'services.id', '=', 'seller_service.service_id')
                                        ->get();    
                            @endphp
                            @foreach ($make as $makes)
                            @php
                                $makes_name = DB::table('brand')
                                    ->where('id', $makes->brand_id)
                                    ->first();
                            @endphp
                            <div class="listing-item listing-pg" style="cursor: pointer;">
                                    <div class="new-ven-add">
                                        <div class="vendor-info">
                                            <div class="vendor-name">
                                                <a href="#">
                                                    <span class="brand_name">{{ $makes_name->brand_name }}</span>
                                                    <span class="model_name">{{ $seller->model_name }}</span><br>
                                                    <span class="category_name">{{ $seller->category_name }}</span>
                                                    <span class="subcategory_name">{{ $seller->subcategory_name }}</span>
                                                </a>
                                            </div>
                                            <span class="name-part-add">{{ $seller->mparents_name }}</span>

                                            <div class="vendor-icons">

                                                <div>
                                                    @if ($service)
                                                        @foreach ($service as $services)
                                                            @if ($services->service_id == 1)
                                                                <i class="bi bi-truck" data-bs-toggle="tooltip"
                                                                    title="{{ $services->service_name }}"></i>
                                                            @elseif($services->service_id == 2)
                                                                <i class="bi bi-globe" data-bs-toggle="tooltip"
                                                                    title="{{ $services->service_name }}"></i>
                                                            @elseif($services->service_id == 3)
                                                                <i class="bi bi-wrench" data-bs-toggle="tooltip"
                                                                    title="{{ $services->service_name }}"></i>
                                                            @else
                                                                <i class="bi bi-award" data-bs-toggle="tooltip"
                                                                    title="{{ $services->service_name }}"></i>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>


                                            </div>
                                        </div>
                                    </div>



                                    <div class="product-status">

                                        <button onclick="buyer_request_send(this)" class="btn btn-dark send_request_btn"
                                            data-year="" data-seller_id="{{ $seller->seller_id }}"
                                            data-parent_id="{{ $seller->parent_id }}" data-brand="{{ $seller->make_id }}"
                                            data-model="{{ $seller->model_id }}" data-category="{{ $seller->part_id }} "
                                            data-subcategory="{{ $seller->part_type_id }}">
                                            Send Request
                                        </button>
                                    </div>
                                    @if (session('success'))
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: "Success!",
                                                text: "{{ session('success') }}"
                                            });
                                        </script>
                                    @endif

                                    @if (session('error'))
                                        <script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: "Error!",
                                                text: "{{ session('error') }}"
                                            });
                                        </script>
                                    @endif


                                    <div class="contact-options">
                                        <div class="product-price">
                                            {{ $seller->first_name }} {{ $seller->last_name }}
                                        </div>
                                        <div class="icons-part-add">

                                            {{-- <!-- <a href="https://wa.me/{{ $product->mobile }}?text={{ urlencode('Hi, I am interested in your product on your website.') }}" target="_blank"> --> --}}

                                            <button class="s_icon"
                                                onclick="openWhatsApp(
                                                    '{{ $seller->mobile }}',
                                                    '{{ $seller->id }}',
                                                    '{{ $seller->brand_name }}',
                                                    '{{ $seller->model_name }}',
                                                    '{{ $seller->category_name }}',
                                                    '{{ $seller->subcategory_name }}',event)">
                                                <i class="bi bi-whatsapp"></i></button>
                                            <!-- </a> -->

                                            {{-- First check + icon have or not --}}
                                            @php
                                                $phone = trim($seller->mobile);
                                                // remove spaces, dashes, brackets
                                                $phone = preg_replace('/[^0-9+]/', '', $phone);

                                                // ensure it starts with +
                                                if ($phone && substr($phone, 0, 1) !== '+') {
                                                    $phone = '+' . $phone;
                                                }
                                            @endphp

                                            {{-- <a class="s_icon" style="color:#000000 !important"
                                                href="tel:{{ $phone }}">
                                                <i class="bi bi-telephone"></i>
                                            </a> --}}

                                            <a class="s_icon" style="color:#000000 !important"
                                                href="tel:{{ $phone }}" onclick="event.stopPropagation()">
                                                <i class="bi bi-telephone"></i>
                                            </a>

                                            <button class="s_icon"
                                                onclick="openMap('{{ $seller->user_lat }}','{{ $seller->user_long }}', event)">
                                                <i class="bi bi-geo-alt"></i>
                                            </button>

                                            <a href="#">
                                                <div style="color:#000000" class="vendor-name">{{ $seller->shop_name }}
                                                </div>
                                            </a>

                                            <div class="vendor-city city-add-text">
                                                {{ $seller->city_name }}
                                            </div>

                                        </div>


                                    </div>
                                </div>
                            @endforeach
                            @else
                                @php
                                    $service = DB::table('seller_service')
                                        ->where('seller_id', $seller->seller_id)
                                        ->join('services', 'services.id', '=', 'seller_service.service_id')
                                        ->get();
                                @endphp

                                <div class="listing-item listing-pg" style="cursor: pointer;">
                                    <div class="new-ven-add">
                                        <div class="vendor-info">
                                            <div class="vendor-name">
                                                <a href="#">
                                                    <span class="brand_name">{{ $seller->brand_name }}</span>
                                                    <span class="model_name">{{ $seller->model_name }}</span><br>
                                                    <span class="category_name">{{ $seller->category_name }}</span>
                                                    <span class="subcategory_name">{{ $seller->subcategory_name }}</span>
                                                </a>
                                            </div>
                                            <span class="name-part-add">{{ $seller->mparents_name }}</span>

                                            <div class="vendor-icons">

                                                <div>
                                                    @if ($service)
                                                        @foreach ($service as $services)
                                                            @if ($services->service_id == 1)
                                                                <i class="bi bi-truck" data-bs-toggle="tooltip"
                                                                    title="{{ $services->service_name }}"></i>
                                                            @elseif($services->service_id == 2)
                                                                <i class="bi bi-globe" data-bs-toggle="tooltip"
                                                                    title="{{ $services->service_name }}"></i>
                                                            @elseif($services->service_id == 3)
                                                                <i class="bi bi-wrench" data-bs-toggle="tooltip"
                                                                    title="{{ $services->service_name }}"></i>
                                                            @else
                                                                <i class="bi bi-award" data-bs-toggle="tooltip"
                                                                    title="{{ $services->service_name }}"></i>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>


                                            </div>
                                        </div>
                                    </div>



                                    <div class="product-status">

                                        <button onclick="buyer_request_send(this)" class="btn btn-dark send_request_btn"
                                            data-year="" data-seller_id="{{ $seller->seller_id }}"
                                            data-parent_id="{{ $seller->parent_id }}" data-brand="{{ $seller->make_id }}"
                                            data-model="{{ $seller->model_id }}" data-category="{{ $seller->part_id }} "
                                            data-subcategory="{{ $seller->part_type_id }}">
                                            Send Request
                                        </button>
                                    </div>
                                    @if (session('success'))
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: "Success!",
                                                text: "{{ session('success') }}"
                                            });
                                        </script>
                                    @endif

                                    @if (session('error'))
                                        <script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: "Error!",
                                                text: "{{ session('error') }}"
                                            });
                                        </script>
                                    @endif


                                    <div class="contact-options">
                                        <div class="product-price">
                                            {{ $seller->first_name }} {{ $seller->last_name }}
                                        </div>
                                        <div class="icons-part-add">

                                            {{-- <!-- <a href="https://wa.me/{{ $product->mobile }}?text={{ urlencode('Hi, I am interested in your product on your website.') }}" target="_blank"> --> --}}

                                            <button class="s_icon"
                                                onclick="openWhatsApp(
                                                    '{{ $seller->mobile }}',
                                                    '{{ $seller->id }}',
                                                    '{{ $seller->brand_name }}',
                                                    '{{ $seller->model_name }}',
                                                    '{{ $seller->category_name }}',
                                                    '{{ $seller->subcategory_name }}',event)">
                                                <i class="bi bi-whatsapp"></i></button>
                                            <!-- </a> -->

                                            {{-- First check + icon have or not --}}
                                            @php
                                                $phone = trim($seller->mobile);
                                                // remove spaces, dashes, brackets
                                                $phone = preg_replace('/[^0-9+]/', '', $phone);

                                                // ensure it starts with +
                                                if ($phone && substr($phone, 0, 1) !== '+') {
                                                    $phone = '+' . $phone;
                                                }
                                            @endphp

                                            {{-- <a class="s_icon" style="color:#000000 !important"
                                                href="tel:{{ $phone }}">
                                                <i class="bi bi-telephone"></i>
                                            </a> --}}

                                            <a class="s_icon" style="color:#000000 !important"
                                                href="tel:{{ $phone }}" onclick="event.stopPropagation()">
                                                <i class="bi bi-telephone"></i>
                                            </a>

                                            <button class="s_icon"
                                                onclick="openMap('{{ $seller->user_lat }}','{{ $seller->user_long }}', event)">
                                                <i class="bi bi-geo-alt"></i>
                                            </button>

                                            <a href="#">
                                                <div style="color:#000000" class="vendor-name">{{ $seller->shop_name }}
                                                </div>
                                            </a>

                                            <div class="vendor-city city-add-text">
                                                {{ $seller->city_name }}
                                            </div>

                                        </div>


                                    </div>
                                </div>
                            @endif    
                            @endforeach
                        @else
                            <div class="ndf_sty">
                                <h3>No Seller found.</h3>
                            </div>

                            <script>
                                document.getElementById('requestPartForm').addEventListener('submit', function(e) {
                                    e.preventDefault();

                                    // Get current URL params
                                    const queryParams = window.location.search; // e.g. ?year=2025&brand_id=1...

                                    // Redirect to sellerTagList with same params
                                    window.location.href = "{{ route('sellerTagList') }}" + queryParams;
                                });
                            </script>
                        @endif
                        <!-- End static product item -->

                    </div>

                    <div class="pagination">
                        {{ $sellerTagList->links('pagination::bootstrap-4') }}
                    </div>


                    {{-- <div class="pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled"><span class="page-link">Previous</span></li>
                            <li class="page-item active"><span class="page-link">1</span></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </div> --}}
                </div>

            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        // Get the 'year' parameter from URL
        // function getUrlParameter(name) {
        //     const params = new URLSearchParams(window.location.search);
        //     return params.get(name);
        // }

        function buyer_request_send(button) {
            // alert("sdsfgsg");
            let seller_id = $(button).data('seller_id');
            let parent_id = $(button).data('parent_id');
            let brand_id = $(button).data('brand');
            let model_id = $(button).data('model');
            let category_id = $(button).data('category');
            let subcategory_id = $(button).data('subcategory');
            const params = new URLSearchParams(window.location.search);
            let year = params.get('year');

            $(button).prop('disabled', true).text('Sending...');

            $.ajax({
                url: "{{ url('/BuyerSendRequest') }}",
                type: "POST",
                dataType: "json",
                data: {
                    seller_id,
                    parent_id,
                    brand_id,
                    model_id,
                    category_id,
                    subcategory_id,
                    year,
                    _token: "{{ csrf_token() }}"
                },
                success: function() {
                    // alert("Request sent successfully!");
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Request sent successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $(button).text('Request Sent').addClass('btn-success');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Something went wrong!',
                    });
                    $(button).prop('disabled', false).text('Send Request');
                }
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
        document.addEventListener("DOMContentLoaded", function() {
            const scrollArrow = document.getElementById("scrollArrow");
            const breadcrumbList = document.getElementById("breadcrumbList");

            if (scrollArrow && breadcrumbList) {
                scrollArrow.addEventListener("click", function() {
                    breadcrumbList.scrollBy({
                        left: 120, // pixels to scroll on each click
                        behavior: "smooth"
                    });
                });
            }
        });
    </script>


    <script>
        function goToMiniPage(url) {
            window.location.href = url;
        }

        function openWhatsApp(mobile, id, brand, model, category, subcategory, event) {
            event.stopPropagation();
            event.preventDefault();
            const productLink = `${window.location.origin}/productDetail/${id}`;
            const params = new URLSearchParams(window.location.search);
            let year = params.get('year');
            const message =
                `Hello, I'm searching for a part on ARABCARPART:
         ${year ? ' (' + year + ')' : ''} > ${brand} > ${model} > ${category} > ${subcategory}
         Is it available?`;

            const url = `https://wa.me/${mobile}?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
        }

        function openMap(latitude, longitude, event) {
            event.stopPropagation();
            event.preventDefault();

            //alert("Latitude: " + latitude + " | Longitude: " + longitude);
            let url = `https://www.google.com/maps?q=${latitude},${longitude}`;
            window.open(url, '_blank');
        }


        // function callNow(mobile, event) {
        //     event.stopPropagation(); // Prevent parent <a> click
        //     event.preventDefault();  // Prevent any default action

        //     window.location.href = `tel:${mobile}`; // Initiate call
        // }
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
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    <script>
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();

            fetch("{{ route('productList') }}?" + params, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('productList').innerHTML = data;
                })
                .catch(err => console.error('Filter error:', err));
        });
    </script>
    <script>
        $(document).ready(function() {

            // When Parent changes → Load Brands
// When Parent changes → Load Brands + Models
$('#parent_id').on('change', function() {

    let parentId = $(this).val(); // SINGLE VALUE or MULTIPLE

    $.ajax({
        url: "{{ url('/admin/getBrand') }}",
        type: "POST",
        data: {
            mparents_id: parentId,
            _token: '{{ csrf_token() }}'
        },
        success: function(result) {

            // BRANDS
            $("#brand").html('<option value="">Select Brand</option>');
            $.each(result.brands, function(index, item) {
                $("#brand").append(
                    `<option value="${item.id}">${item.brand_name}</option>`
                );
            });

            // MODELS (FILTERED BY PARENT)
            $("#model").html('<option value="">Select Model</option>');
            $.each(result.models, function(index, item) {
                $("#model").append(
                    `<option value="${item.id}">${item.model_name}</option>`
                );
            });
        }
    });
});



            // When Brand changes → Load Models
$('#brand').on('change', function() {

    let brandId = $(this).val();

    $.ajax({
        url: "{{ url('/admin/getModel') }}",
        type: "POST",
        data: {
            brand_id: brandId,
            _token: '{{ csrf_token() }}'
        },
        success: function(result) {

            $("#model").html('<option value="">Select Model</option>');

            $.each(result.city, function(index, item) {
                $("#model").append(
                    `<option value="${item.id}">${item.model_name}</option>`
                );
            });
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

            // $(document).on('change', '#model', function () {

            //     var cid = this.value;   //let cid = $(this).val(); we cal also write this.
            //     $.ajax({
            //     url: "{{ url('/admin/getgeneration') }}",
            //     type: "POST",
            //     datatype: "json",
            //     data: {
            //         model_id: cid,
            //         '_token':'{{ csrf_token() }}'
            //     },
            //     success: function(result) {
            //         $('#generation').html('<option value="">Select Generation</option>');
            //         $.each(result.subcat, function(key, value) {
            //         $('#generation').append('<option value="' +value.id+ '">' +value.start_year+'-'+value.end_year+ '</option>');
            //         });
            //     },
            //     errror: function(xhr) {
            //         console.log(xhr.responseText);
            //         }
            //     });
            // });

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

    <script>
        $(document).on('click', '.track-product-click', function(e) {
            e.preventDefault(); // prevent link from navigating before AJAX

            const $this = $(this);
            const productUrl = $this.attr('href');
            const productId = $this.data('product-id');
            const sellerId = $this.data('seller-id');
            const adminProductId = $this.data('admin-product-id');

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                        .then(res => res.json())
                        .then(data => {
                            const address = data.address || {};

                            $.ajax({
                                url: "{{ route('product.track.click') }}",
                                method: "POST",
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    product_id: productId,
                                    admin_product_id: adminProductId,
                                    seller_id: sellerId,
                                    latitude: lat,
                                    longitude: lng,
                                    country: address.country || '',
                                    state: address.state || '',
                                    city: address.city || address.town || address.village || ''
                                },
                                complete: function() {
                                    // after tracking, continue navigation
                                    window.location.href = productUrl;
                                }
                            });
                        })
                        .catch(() => {
                            // fallback redirect if API fails
                            window.location.href = productUrl;
                        });
                }, () => {
                    // fallback if geolocation blocked or failed
                    window.location.href = productUrl;
                });
            } else {
                window.location.href = productUrl;
            }
        });
    </script>
@endpush
