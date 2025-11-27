@extends('web.common.layout')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        #map {
            height: 100%;
            min-height: 480px;
        }

        .shop-list {
            max-height: 480px;
            overflow: auto;
        }

        .shop-item {
            cursor: pointer;
        }

        .shop-item:hover {
            background: #f8f9fa;
        }

        .leaflet-container {
            font: 14px/1.4 "Helvetica Neue", Arial, sans-serif;
        }

        .diectory_listing {
            margin-top: 97px;
            background: #fdf9ee;
            direction: ltr;
            text-align: left;
        }

        .select2-search__field {
            height: 24px !important;
            text-align: left;
        }

        .select2-results__options {
            text-align: left;
        }

        span#select2-carTypeFilter-container span {
            font-size: 12px;
        }
    </style>

    <section class="diectory_listing text-right">
        <div class="container py-3">
            <p><strong>{{ __('messages.shop_listing') }}&nbsp;</strong></p>
            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <input id="q" class="form-control"
                                    placeholder="{{ __('messages.search_shop_name') }}" />
                            </div>

                            <div class="mb-2">
                                <select id="countryFilter" class="form-select select2 country_select" multiple>
                                    <option value="">All countries</option>
                                    @foreach ($country_data as $count_data)
                                        <option value="{{ $count_data->country_id }}">{{ $count_data->country_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-2">
                                <select id="cityFilter" class="form-select select2 city_select" multiple>
                                    <option value="">All cities</option>
                                </select>
                            </div>

                            <div class="mb-2">
                                <select id="carTypeFilter" class="form-select select2 car_select">
                                    <option value="">{{ __('messages.all_car_type') }}</option>
                                    @foreach ($mparents as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->mparents_name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-2 d-flex gap-2">
                                <button id="btnSearch"
                                    class="btn btn-primary flex-grow-1">{{ __('messages.search') }}</button>
                                <button id="btnClear" class="btn btn-outline-secondary">{{ __('messages.clear') }}</button>
                            </div>

                            <hr>
                            <div id="resultsCount" class="small text-muted mb-2">0 {{ __('messages.results') }}</div>
                            <div class="list-group shop-list" id="shopList"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div id="map" class="rounded shadow-sm"></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // ---------- SHOP DATA ----------
            const shops = {!! $shop_details !!}; // Must include city_id & country_id in controller
            //console.log("Shops:", shops);

            // ---------- MAP SETUP ----------
            var map = L.map('map').setView([25.276987, 55.296249], 10); // default center
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var marker; // Keep track of marker

            function updateMap(lat, lng, shopName = '') {
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
                if (shopName) marker.bindPopup(shopName).openPopup();
                map.setView([lat, lng], 13);
            }

            // Try geolocation
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        let lat = position.coords.latitude;
                        let lng = position.coords.longitude;

                        // Extra check: ignore obviously wrong coordinates
                        if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                            alert("Detected coordinates are invalid. Try again.");
                            return;
                        }

                        updateMap(lat, lng, "{{ __('messages.you_are_here') }}");
                    },
                    function(error) {
                        let msg = '';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                msg = "Location permission denied.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                msg = "Location unavailable.";
                                break;
                            case error.TIMEOUT:
                                msg = "Location request timed out.";
                                break;
                            default:
                                msg = "Unknown error occurred.";
                        }
                        alert(msg + " Make sure you are allowing location access.");
                    }, {
                        enableHighAccuracy: true, // Try GPS if available
                        timeout: 15000, // Give GPS enough time
                        maximumAge: 0 // Don’t use cached position
                    }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
            }

            const markersLayer = L.layerGroup().addTo(map);
            let currentMarkers = {};

            const $q = $('#q');
            const $cityFilter = $('#cityFilter');
            const $countryFilter = $('#countryFilter');
            const $carTypeFilter = $('#carTypeFilter');
            const $shopList = $('#shopList');
            const $resultsCount = $('#resultsCount');

            // Country → City filter
            $countryFilter.on('change', function() {
                const countryIds = $(this).val() || [];
                $cityFilter.empty().append('<option value="">All cities</option>');

                if (countryIds.length > 0) {
                    $.get("{{ route('web.cities') }}", {
                        country_ids: countryIds
                    }, function(cities) {
                        cities.forEach(city => {
                            $cityFilter.append(new Option(city.city_name, city.city_id));
                        });
                        $cityFilter.trigger('change.select2');
                    });
                }
            });

            // Escape HTML
            function escapeHtml(s) {
                if (!s) return '';
                return s.toString().replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(
                    />/g, '&gt;');
            }

            // Render shop markers
            function renderMarkers(list) {
                markersLayer.clearLayers();
                currentMarkers = {};

                list.forEach(shop => {
                    if (isFinite(shop.lat) && isFinite(shop.lng)) {

                    const currentLocale = "{{ App::getLocale() }}";

                    let message = "";

                    if (currentLocale === "ar" || currentLocale === "ur" || currentLocale === "fa") {

                        // 🔹 AR / UR / FA version
                        message = `مرحبًا، وجدت متجرك على ARABCARPART:

                    المتجر: ${shop.name}
                    العنوان: ${shop.address}
                    المدينة: ${shop.city}

                    ${shop.shop_url}`;

                    } else {

                        // 🔹 English version
                        message = `Hello, I found your shop on ARABCARPART:

                        Shop: ${shop.name}
                        Address: ${shop.address}
                        City: ${shop.city}

                        ${shop.shop_url}`;
                        }

                        // WhatsApp URL only if number exists
                        let whatsappHtml = '';
                        if (shop.whatsapp1 && shop.whatsapp1 !== "" && shop.whatsapp1 !== null) {
                            const whatsappUrl =
                                `https://api.whatsapp.com/send/?phone=${shop.whatsapp1}&text=${encodeURIComponent(message)}&type=phone_number&app_absent=0`;

                            whatsappHtml = `
                    <a href="${whatsappUrl}" target="_blank" style="margin-left:8px;">
                        <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" width="18" height="18">
                    </a>
                `;
                        }

                        const m = L.marker([shop.lat, shop.lng]).addTo(markersLayer);

                        const popupHtml = `
                <strong>
                    <a href="${shop.shop_url}" target="_blank">
                        ${escapeHtml(shop.name)}
                    </a>
                </strong><br>

                <a href="tel:${escapeHtml(shop.mobile)}">${escapeHtml(shop.mobile)}</a>
                ${whatsappHtml}
            `;

                        m.bindPopup(popupHtml);
                        currentMarkers[shop.id] = m;
                    }
                });

                const coords = list.filter(s => isFinite(s.lat) && isFinite(s.lng))
                    .map(s => [s.lat, s.lng]);

                if (coords.length === 1) map.setView(coords[0], 14);
                else if (coords.length > 1) map.fitBounds(coords, {
                    padding: [60, 60]
                });
            }




            // Render shop list
            function renderList(list) {
                $shopList.empty();
                if (list.length === 0) {
                    $shopList.html('<div class="p-3 text-muted">No shops found.</div>');
                    $resultsCount.text('0 {{ __('messages.results') }}');
                    return;
                }

                $resultsCount.text(`${list.length} {{ __('messages.results') }}${list.length > 1 ? 's' : ''}`);

                list.forEach(shop => {
                    const el = $(`<div class="list-group-item list-group-item-action shop-item">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${escapeHtml(shop.name)}</h6>
                    <small class="text-muted">${escapeHtml(shop.country_id)}</small>
                </div>
                <p class="mb-1 small">{{ __('messages.address') }}: ${escapeHtml(shop.address)}</p>
                <p class="mb-1 small">{{ __('messages.city') }}: ${escapeHtml(shop.city_id)}</p>
            </div>`);
                    el.on('click', () => {
                        const m = currentMarkers[shop.id];
                        if (m) {
                            map.setView(m.getLatLng(), 15);
                            m.openPopup();
                        }
                    });
                    $shopList.append(el);
                });
            }

            // Filter function
            function filterShops(data, countryIds, cityIds, carTypeIds, query) {
                query = (query || '').trim().toLowerCase();

                return data.filter(shop => {
                    const matchCountry = !countryIds || countryIds.length === 0 || countryIds.includes(
                        String(shop.country_id));
                    const matchCity = !cityIds || cityIds.length === 0 || cityIds.includes(String(shop
                        .city_id));
                    const matchCarType = !carTypeIds || carTypeIds.length === 0 || carTypeIds.includes(
                        String(shop.car_type_id));
                    const matchName = !query || (shop.name && shop.name.toLowerCase().includes(query));
                    return matchCountry && matchCity && matchCarType && matchName;
                });
            }


            // Search & Clear
            $('#btnSearch').on('click', function() {
                const filtered = filterShops(
                    shops,
                    $countryFilter.val() || [],
                    $cityFilter.val() || [],
                    $carTypeFilter.val() || [], // <-- add this line
                    $q.val()
                );
                renderList(filtered);
                renderMarkers(filtered);
            });


            $('#btnClear').on('click', function() {
                $q.val('');
                $countryFilter.val(null).trigger('change');
                $cityFilter.val(null).trigger('change');
                $carTypeFilter.val(null).trigger('change'); // <-- added
                $cityFilter.html('<option value="">All cities</option>');
                renderList(shops);
                renderMarkers(shops);
            });


            // Initial render
            renderList(shops);
            renderMarkers(shops);

            $('.country_select').select2({
                placeholder: "{{ __('messages.select_countries') }}",
                allowClear: true,
                width: '100%'
            });

            $('.city_select').select2({
                placeholder: "{{ __('messages.select_cities') }}",
                allowClear: true,
                width: '100%'
            });

            $('.car_select').select2({
                placeholder: "{{ __('messages.all_car_type') }}",
                allowClear: true,
                width: '100%'
            });


            $(".directory_listing_dropdown").click(function() {
                $(".directory_listing_dropdown").toggleClass("show");
                $(".directory_listing_dropdown_menu").toggleClass("show");
            });

            $(".directory_listing_dropdown_mobile").click(function() {
                $(".directory_listing_dropdown_mobile").toggleClass("show");
                $(".directory_listing_dropdown_menu_mobile").toggleClass("show");
            });

        });
    </script>
@endpush
