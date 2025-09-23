@extends('web.common.layout') 
@section('content')	
		<?php
		$shop_name=$shop_logo=$shop_banner=$about_shop=$qr_code='';
		if($shop)
		{
			$shop_logo=$shop->shop_logo;
			$shop_banner=$shop->shop_banner;
			$about_shop=$shop->about_shop;
			$qr_code=$shop->qr_code;
			$shop_name=$shop->shop_name;
		}
		?>
		<section class="listing_detail_header" style="
   background: rgba(0, 0, 0, 0) url(http://localhost/autopart/public/web_assets/images/banner-bg-02.jpg) no-repeat;
   padding: 80px 0;
   position: relative;
   background-size: cover;
   background-position: bottom;">
			<div class="container">
				<div class="row">
				  <div class="col-md-3 col-sm-3 col-xs-4">
					<div class="dealer_logo"> 
						@if($shop_logo=='')
							<img src="{{ url('/public') }}/web_assets/images/logo.png" alt="image"> 
						@else
							<img src="{{ url('/public') }}/uploads/shop_image/{{$shop_logo}}" alt="image"> 
						@endif
						
					</div>
				  </div>
				  <div class="col-md-6 col-sm-5 col-xs-8">
					<div class="dealer_info">
					  <h4>{{$shop_name}} </h4>
					  <p>Address : {{$user->address1}} ,<br>
					  		{{$user->address2}} ,{{$user->zip_code}}<br>
							</p>
					  <ul class="dealer_social_links">
						<li class="facebook-icon"><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
						<li class="twitter-icon"><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
						<li class="linkedin-icon"><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
					  </ul>
					</div>
				  </div>
				  <div class="col-md-3 col-sm-4 col-xs-12">
					<div class="dealer_contact_info gray-bg">
					  <h6><i class="fa fa-globe" aria-hidden="true"></i> Website</h6>
					  <a href="#">www.example.com</a> </div>
					<div class="dealer_contact_info gray-bg">
					  <h6><i class="fa fa-envelope" aria-hidden="true"></i> Email Address</h6>
					  <a href="mailto:contact@example.com">contact@example.com</a> </div>
					<div class="dealer_contact_info gray-bg">
					  <h6><i class="fa fa-phone" aria-hidden="true"></i> Phone Number</h6>
					  <a href="tel:61-1234-5678-09">+61-1234-5678-09</a> </div>
				  </div>
				</div> 
			</div>
			<div class="dark-overlay"></div>
		</section>
		
		<section class="seller_abt">
			<div class="container">
				<div class="row">
				  	<div class="col-md-12">
						<div class="dealer_more_info">
						<h5 class="gray-bg info_title"> {{$shop_name}}</h5>
						<p>{{$about_shop}} </p>
						<div class="dealer_map">
							<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26361315.424069386!2d-113.75658747371207!3d36.241096924675375!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited+States!5e0!3m2!1sen!2sin!4v1483614660041" width="100%" frameborder="0" style="border:0" allowfullscreen=""></iframe>
						</div>
					</div>
					
					<div class="inventory--list">
						<h6>Inventorys Listing By {{$shop_name}}</h6>
						<div class="row">
							@foreach ($products as $product)
          					<div class="col-md-4 grid_listing">
            					<div class="product-listing-m ">
									<div class="product-listing-img"> <a href="#"><img src="{{ url('/public') }}/web_assets/images/product_26.jpg" class="img-fluid" alt="image"> </a>
										<div class="label_icon">{{$product->product_type==0?'--':($product->product_type==1?'New':($product->product_type==2?'Old':'Refurbished'))}}</div>
											<div class="save_item">
												<div class="save-icon">
													<i class="fa-regular fa-heart"></i>
												</div>
                							</div>
              							</div>
              							<div class="product-listing-content">
											<div class="model_info">
												<p>{{$product->brand_name}} &gt; {{$product->model_name}}</p>
												<p class="price">@if(!empty($product->product_price))$ {{ number_format($product->product_price, 2) }} @endif</p>
											</div>
                						<h5><a href="#">{{$product->category_name}} > {{$product->subcategory_name}}</a></h5>
										<div class="model_info">
											<p>{{$product->start_year}} - {{$product->end_year}}</p>
										</div>
                						<div class="seller_get">
											<p class="shop-name">{{$user->first_name}} {{$user->last_name}}</p>
                							<div class="car-location">
												<span><i class="fa-solid fa-map-location-dot"></i> {{$user->city_name}}, {{$user->country_name}}</span>
											</div>
										</div>     
										<ul class="features_list">
											<!-- <li><i class="fa fa-road" aria-hidden="true"></i>35,000 km</li>
											<li><i class="fa fa-tachometer" aria-hidden="true"></i>30.000 miles</li>
											<li><i class="fa fa-calendar" aria-hidden="true"></i>2005 model</li>
											<li><i class="fa fa-car" aria-hidden="true"></i>Diesel</li> -->
										</ul>
										<div class="btns">
											<div class="whts_seller">
												<a href="https://wa.me/{{ $user->mobile }}?text={{ urlencode('Hi, I am interested in your product on your website.') }}" target="_blank">
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
					</div>
								  
					<!-- <aside class="col-md-3">
						<div class="sidebar_widget">
							<div class="widget_heading">
								<h5><i class="fa fa-envelope" aria-hidden="true"></i> Message to Seller</h5>
							</div>
							<form action="" method="get" class="seller_cont_form" id="ContactUsForm">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Name" name="full_name" id="full_name">
									<div id="full_name_error" class="error"></div>
								</div>
								<div class="form-group">
									<input type="email" class="form-control" placeholder="Email" name="email_address" id="email_address">
									<div id="femail_addr_error" class="error"></div>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Phone no." name="phone_number" id="phone_number">
									<div id="phone_no_error" class="error"></div>
								</div>
								<div class="form-group">
									<textarea rows="4" class="form-control" placeholder="Message" name="contact_message" id="contact_message"></textarea>
									<div id="contct_mgs_error" class="error"></div>
								</div>
								<input type="hidden" name="contact_send_btn" value="contact_send_btn">
								<div class="form-group">
									<input type="submit" value="Send Message" class="btn submit btn-block">
								</div>
								<div class="form-group">
									<div id="error_message" class="ajax_response alert alert-danger" style="display:none;"></div>
									<div id="success_message" class="ajax_response alert alert-success" style="display:none;"></div>
								</div>
							</form>
						</div>
					</aside> -->
				</div>
			</div>
		</section>
@endsection
@push('scripts')

@endpush