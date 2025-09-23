<div class="row">
    @foreach ($products as $product)
    <div class="col-md-4 grid_listing">
        <div class="product-listing-m ">
            <div class="product-listing-img"> 
                <a href="{{route('productDetail', $product->id)}}" class="track-product-click" data-product-id="{{ $product->id }}" data-seller-id="{{ $product->seller_id }}" data-admin-product-id="{{ $product->admin_product_id }}">
                    @if($product->product_image!='')
                        <img src="{{ asset('/public') }}/uploads/product_image/{{$product->product_image}}" class="img-fluid" alt="image"> 
                    @else
                        <img src="{{ url('/public') }}/web_assets/images/product_26.jpg" class="img-fluid" alt="image"> 
                    @endif
                    
                </a>
                <div class="label_icon">{{$product->product_type==0?'--':($product->product_type==1?'New':($product->product_type==2?'Old':'Refurbished'))}}</div>
                <div class="save_item">
                    <div class="save-icon">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                </div>
            </div>
            <div class="product-listing-content">
                <div class="model_info">
                    <p>{{$product->brand_name}} > {{$product->model_name}}</p>
                    <p class="price">@if(!empty($product->product_price))$ {{ number_format($product->product_price, 2) }} @endif</p>
                </div>
                <h5><a href="#">{{$product->category_name}} > {{$product->subcategory_name}} </a></h5>
                <div class="model_info">
                    <p>{{$product->part_type_label}}</p>
                </div>
                <div class="model_info">
                    <p>{{$product->start_year}} - {{$product->end_year}}</p>
                </div>
                <div class="seller_get">
                    <p class="shop-name">{{$product->first_name}} {{$product->last_name}}</p>
                    <div class="car-location">
                        <span><i class="fa-solid fa-map-location-dot"></i> {{$product->state_name}}, {{$product->country_name}}</span>
                    </div>
                </div>     
                <ul class="features_list">
                    <!--li><i class="fa fa-road" aria-hidden="true"></i>Manual</li>
                    <li><i class="fa fa-tachometer" aria-hidden="true"></i>30.000 miles</li>
                    <li><i class="fa fa-calendar" aria-hidden="true"></i>4x4</li>
                    <li><i class="fa fa-car" aria-hidden="true"></i>Diesel</li-->
                </ul>
                <div class="btns">
                    <div class="v_seller">
                        <a href="{{route('shopDetail', $product->seller_id)}}">
                            <button type="button">View Seller Shop <i class="fa-solid fa-location-dot"></i></button>
                        </a>
                    </div>
                    <div class="whts_seller">
                        <a href="https://wa.me/{{ $product->mobile }}?text={{ urlencode('Hi, I am interested in your product on your website.') }}" target="_blank">
                            <button type="button">WhatsApp Seller <i class="fa-brands fa-whatsapp"></i></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="pagination">
    {{ $products->links('pagination::bootstrap-4') }}
</div>