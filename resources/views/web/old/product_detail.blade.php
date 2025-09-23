@extends('web.common.layout') 
@section('content')	
		<section class="listing_detail_header">
            <div class="container">
                <div class="page-header_wrap">
                    <div class="page-heading">
                        <h1>Product Details</h1>
                    </div>
                </div>
            </div>
            <div class="dark-overlay"></div>
        </section>

        <!-- listing-html-code-start -->

        <!-- Product Info -->

        <div class="product-details-add">
            <div class="container">
                <!-- Left: Product Image & Thumbnails -->

                <div class="produxt-add-right">
                    <div class="right-side-detail">
                        <div class="left">
							<a href="#">
								
							@if($product->product_image!='')
								<img src="{{ asset('/public') }}/uploads/product_image/{{$product->product_image}}" class="img-fluid" alt="image"> 
							@else
								<img src="{{ url('/public') }}/web_assets/images/product_26.jpg" class="img-fluid" alt="image"> 
							@endif
							</a>                       
                                
                        </div>

                        <!-- Center: Info -->
                        <div class="center">
                            <div class="part-info">
                                <h2 class="part-name">{{$product->category_name}} > {{$product->subcat_name}}</h2>
                                <p class="model-year">{{$product->brand_name}} > {{$product->model_name}}</p>
                                <p>Generation: {{$product->start_year}} - {{$product->end_year}}</p>
								@if($product->part_type_label)
                                <div class="sub-range">
                                    <p><b>Sub-ranges:</b></p>
                                    <span>{{$product->part_type_label}}</span>
                                </div>
								@endif
                                <p class="condition">Condition: {{$product->product_type==0?'--':($product->product_type==1?'New':($product->product_type==2?'Old':'Refurbished'))}}</p>
                                
                                <p class="seller">Seller: <strong>{{$product->shop_name}}</strong> – {{$product->country_name}}</p>

                                 <div class="service-list-add">
                                     <i class="bi bi-truck"></i>
                                     <i class="bi bi-globe"></i>
                                     <i class="bi bi-gear-wide-connected"></i>
                                     <i class="bi bi-clipboard2-check"></i>
                                 </div>
                    
                            </div>

                        </div>

                        <div class="right-col">
                            <div class="price">@if(!empty($product->product_price))$ {{ number_format($product->product_price, 2) }} @endif</div>
                                        <div class="buttons">
                                    <a href="{{route('shopDetail', $product->seller_id)}}" class="btn view-shop">View Seller Shop</a>
									<a href="https://wa.me/{{ $product->mobile }}?text={{ urlencode('Hi, I am interested in your product on your website.') }}" target="_blank" class="btn whatsapp">
                                    WhatsApp Seller</a>
                                </div>
                        </div>
                    </div>

                </div>
     
            </div>

            <div class="related-products">
                <div class="container">
                    <h4 class="related-text">Related Products</h4>

                    <div class="row releated-used-content">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <!-- Slide 1 -->
                                <div class="carousel-item active">
                                    <div class="row">
                                        <div class="col-md-4 grid_listing">
                                            <div class="product-listing-m">
                                                <div class="product-listing-img">
                                                    <a href="#"><img src="{{ asset('/public') }}/web_assets/images/product_26.jpg" class="img-fluid" alt="image" /> </a>
                                                    <div class="label_icon">New</div>
                                                    <div class="save_item">
                                                        <div class="save-icon">
                                                            <i class="fa-regular fa-heart"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-listing-content">
                                                    <div class="model_info">
                                                        <p>Audi &gt; Q7</p>
                                                        <p class="price">$118</p>
                                                    </div>
                                                    <h5><a href="#">Back Glass Audi Q7</a></h5>
                                                    <div class="model_info">
                                                        <p>2007–2013</p>
                                                    </div>
                                                    <div class="seller_get">
                                                        <p class="shop-name">Seller Name 1</p>
                                                        <div class="car-location">
                                                            <span><i class="fa-solid fa-map-location-dot"></i> Colorado, USA</span>
                                                        </div>
                                                    </div>
                                                    <ul class="features_list">
                                                        <li><i class="fa fa-road" aria-hidden="true"></i>35,000 km</li>
                                                        <li><i class="fa fa-tachometer" aria-hidden="true"></i>30.000 miles</li>
                                                        <li><i class="fa fa-calendar" aria-hidden="true"></i>2005 model</li>
                                                        <li><i class="fa fa-car" aria-hidden="true"></i>Diesel</li>
                                                    </ul>
                                                    <div class="btns">
                                                        <div class="v_seller">
                                                            <button type="button">View Seller Shop <i class="fa-solid fa-location-dot"></i></button>
                                                        </div>
                                                        <div class="whts_seller">
                                                            <button type="button">WhatsApp Seller <i class="fa-brands fa-whatsapp"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 grid_listing">
                                            <div class="product-listing-m">
                                                <div class="product-listing-img">
                                                    <a href="#"><img src="{{ asset('/public') }}/web_assets/images/product_24_1.jpg" class="img-fluid" alt="image" /> </a>
                                                    <div class="label_icon">Used</div>
                                                    <div class="save_item">
                                                        <div class="save-icon">
                                                            <i class="fa-regular fa-heart"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-listing-content">
                                                    <div class="model_info">
                                                        <p>Audi &gt; Q7</p>
                                                        <p class="price">$98</p>
                                                    </div>
                                                    <h5><a href="#">Back Glass Audi Q7</a></h5>
                                                    <div class="model_info">
                                                        <p>2007–2013</p>
                                                    </div>
                                                    <div class="seller_get">
                                                        <p class="shop-name">Seller Name 2</p>
                                                        <div class="car-location">
                                                            <span><i class="fa-solid fa-map-location-dot"></i> Colorado, USA</span>
                                                        </div>
                                                    </div>
                                                    <ul class="features_list">
                                                        <li><i class="fa fa-road" aria-hidden="true"></i>35,000 km</li>
                                                        <li><i class="fa fa-tachometer" aria-hidden="true"></i>30.000 miles</li>
                                                        <li><i class="fa fa-calendar" aria-hidden="true"></i>2005 model</li>
                                                        <li><i class="fa fa-car" aria-hidden="true"></i>Diesel</li>
                                                    </ul>
                                                    <div class="btns">
                                                        <div class="v_seller">
                                                            <button type="button">View Seller Shop <i class="fa-solid fa-location-dot"></i></button>
                                                        </div>
                                                        <div class="whts_seller">
                                                            <button type="button">WhatsApp Seller <i class="fa-brands fa-whatsapp"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 grid_listing">
                                            <div class="product-listing-m">
                                                <div class="product-listing-img">
                                                    <a href="#"><img src="{{ asset('/public') }}/web_assets/images/product_9_1.jpg" class="img-fluid" alt="image" /> </a>
                                                    <div class="label_icon">New</div>
                                                    <div class="save_item">
                                                        <div class="save-icon">
                                                            <i class="fa-regular fa-heart"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-listing-content">
                                                    <div class="model_info">
                                                        <p>Audi &gt; Q7</p>
                                                        <p class="price">$508</p>
                                                    </div>
                                                    <h5><a href="#">Back Glass Audi Q7</a></h5>
                                                    <div class="model_info">
                                                        <p>2007–2013</p>
                                                    </div>
                                                    <div class="seller_get">
                                                        <p class="shop-name">Seller Name 3</p>
                                                        <div class="car-location">
                                                            <span><i class="fa-solid fa-map-location-dot"></i> Colorado, USA</span>
                                                        </div>
                                                    </div>
                                                    <ul class="features_list">
                                                        <li><i class="fa fa-road" aria-hidden="true"></i>35,000 km</li>
                                                        <li><i class="fa fa-tachometer" aria-hidden="true"></i>30.000 miles</li>
                                                        <li><i class="fa fa-calendar" aria-hidden="true"></i>2005 model</li>
                                                        <li><i class="fa fa-car" aria-hidden="true"></i>Diesel</li>
                                                    </ul>
                                                    <div class="btns">
                                                        <div class="v_seller">
                                                            <button type="button">View Seller Shop <i class="fa-solid fa-location-dot"></i></button>
                                                        </div>
                                                        <div class="whts_seller">
                                                            <button type="button">WhatsApp Seller <i class="fa-brands fa-whatsapp"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carousel Controls -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <i class="bi bi-arrow-left-circle"></i>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <i class="bi bi-arrow-right-circle"></i>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@push('scripts')

@endpush