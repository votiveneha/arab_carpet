@extends('web.common.layout')    
@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    #map { height: 100%; min-height: 480px; }
    .shop-list { max-height: 480px; overflow:auto; }
    .shop-item { cursor: pointer; }
    .shop-item:hover { background:#f8f9fa; }
    .leaflet-container { font: 14px/1.4 "Helvetica Neue", Arial, sans-serif; }
    .diectory_listing{ margin-top:97px; background: #fdf9ee; }
</style>

<section class="diectory_listing text-right">
    <div class="container py-3">
        <p><strong>Shop Listing&nbsp;</strong></p>
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <input id="q" class="form-control" placeholder="Search shop name..." />
                        </div>

                        <div class="mb-2">
                            <select id="countryFilter" class="form-select select2" multiple>
                                <option value="">All countries</option>
                                @foreach($country_data as $count_data)
                                    <option value="{{ $count_data->country_id }}">{{ $count_data->country_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <select id="cityFilter" class="form-select select2" multiple>
                                <option value="">All cities</option>
                            </select>
                        </div>

                        <div class="mb-2 d-flex gap-2">
                            <button id="btnSearch" class="btn btn-primary flex-grow-1">Search</button>
                            <button id="btnClear" class="btn btn-outline-secondary">Clear</button>
                        </div>

                        <hr>
                        <div id="resultsCount" class="small text-muted mb-2">0 results</div>
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
    console.log("Shops:", shops);

    // ---------- MAP SETUP ----------
    var map = L.map('map').setView([25.276987, 55.296249], 10); // default center
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker; // Keep track of marker

    function updateMap(lat, lng, shopName = '') {
        if(marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
        if(shopName) marker.bindPopup(shopName).openPopup();
        map.setView([lat, lng], 13);
    }

    // Try geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                let lat = position.coords.latitude;
                let lng = position.coords.longitude;
                
                // Extra check: ignore obviously wrong coordinates
                if(lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                    alert("Detected coordinates are invalid. Try again.");
                    return;
                }

                updateMap(lat, lng, "You are here");
            },
            function(error) {
                let msg = '';
                switch(error.code) {
                    case error.PERMISSION_DENIED: msg = "Location permission denied."; break;
                    case error.POSITION_UNAVAILABLE: msg = "Location unavailable."; break;
                    case error.TIMEOUT: msg = "Location request timed out."; break;
                    default: msg = "Unknown error occurred.";
                }
                alert(msg + " Make sure you are allowing location access.");
            },
            {
                enableHighAccuracy: true,   // Try GPS if available
                timeout: 15000,             // Give GPS enough time
                maximumAge: 0               // Don’t use cached position
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
    const $shopList = $('#shopList');
    const $resultsCount = $('#resultsCount');

    // Country → City filter
    $countryFilter.on('change', function() {
        const countryIds = $(this).val() || [];
        $cityFilter.empty().append('<option value="">All cities</option>');

        if (countryIds.length > 0) {
            $.get("{{ route('web.cities') }}", { country_ids: countryIds }, function(cities) {
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
        return s.toString().replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    // Render shop markers
    function renderMarkers(list) {
        markersLayer.clearLayers();
        currentMarkers = {};
        list.forEach(shop => {
            if (isFinite(shop.lat) && isFinite(shop.lng)) {
                const m = L.marker([shop.lat, shop.lng]).addTo(markersLayer);
                m.bindPopup(`<strong>${escapeHtml(shop.name)}</strong><br>${escapeHtml(shop.address)}<br><em>${escapeHtml(shop.city)}</em>`);
                currentMarkers[shop.id] = m;
            }
        });

        const coords = list.filter(s => isFinite(s.lat) && isFinite(s.lng)).map(s => [s.lat, s.lng]);
        if (coords.length === 1) map.setView(coords[0], 14);
        else if (coords.length > 1) map.fitBounds(coords, {padding: [60,60]});
    }

    // Render shop list
    function renderList(list) {
        $shopList.empty();
        if (list.length === 0) {
            $shopList.html('<div class="p-3 text-muted">No shops found.</div>');
            $resultsCount.text('0 results');
            return;
        }

        $resultsCount.text(`${list.length} result${list.length > 1 ? 's' : ''}`);

        list.forEach(shop => {
            const el = $(`<div class="list-group-item list-group-item-action shop-item">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${escapeHtml(shop.name)}</h6>
                    <small class="text-muted">${escapeHtml(shop.country_id)}</small>
                </div>
                <p class="mb-1 small">Address: ${escapeHtml(shop.address)}</p>
                <p class="mb-1 small">City: ${escapeHtml(shop.city_id)}</p>
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
    function filterShops(data, countryIds, cityIds, query) {
        query = (query || '').trim().toLowerCase();

        return data.filter(shop => {
            const matchCountry = !countryIds || countryIds.length === 0 || countryIds.includes(String(shop.country));
            const matchCity = !cityIds || cityIds.length === 0 || cityIds.includes(String(shop.city));
            const matchName = !query || (shop.name && shop.name.toLowerCase().includes(query));
            return matchCountry && matchCity && matchName;
        });
    }

    // Search & Clear
    $('#btnSearch').on('click', function() {
        const filtered = filterShops(
            shops,
            $countryFilter.val() || [],
            $cityFilter.val() || [],
            $q.val()
        );
        renderList(filtered);
        renderMarkers(filtered);
    });

    $('#btnClear').on('click', function() {
        $q.val('');
        $countryFilter.val(null).trigger('change');
        $cityFilter.val(null).trigger('change');
        $cityFilter.html('<option value="">All cities</option>');
        renderList(shops);
        renderMarkers(shops);
    });

    // Initial render
    renderList(shops);
    renderMarkers(shops);

    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });

});
</script>
@endpush
