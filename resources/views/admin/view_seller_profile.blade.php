@extends('admin.layouts.layout')

@section('content')
<style>
  .seller_pro {
    margin-top: 20px;
}
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                    $first_name=$last_name=$email=$gender=$user_id="";
                    $country_name=$state_name=$city_name=$profile_image='';
                    if($user_detail)
                    {
                      $user_id=$user_detail->id;
                      $first_name=$user_detail->first_name;
                      $last_name=$user_detail->last_name;
                      $email=$user_detail->email;
                      $gender=$user_detail->gender;
                      $country_name=$user_detail->country_name;
                      //$state_name=$user_detail->state_name;
                      $city_name=$user_detail->city_name;
                      $profile_image=$user_detail->profile_image;
                    }

                    $shop_name=$shop_logo=$shop_banner=$about_shop=$qr_code='';
                    if($shop_detail)
                    {
                      $shop_name=$shop_detail->shop_name;
                      $shop_logo=$shop_detail->shop_logo;
                      $shop_banner=$shop_detail->shop_banner;
                      $about_shop=$shop_detail->about_shop;
                      $qr_code=$shop_detail->qr_code;
                    }
                  ?>
                <div class="card">
                  <div class="card-body">
                      <a href="{{route('admin.sellerList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Seller List</a>
                      <h4 class="card-title">Seller Management</h4>
                      <!--p class="card-description"> Add / Update Blog  </p-->

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Profile</button>
                      </li>
                      <a href="{{route('admin.viewSellerEnquiry', ['id' => $user_id])}}">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Request</button>
                        </li>
                      </a>
                      <a href="{{route('admin.viewSellerProduct', ['id' => $user_id])}}">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">Product</button>
                        </li>
                      </a>
                    </ul>

                    <!-- Tab panes -->
                    <div class="seller_pro">
                      <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                          <div class="row">
                            <div class="form-group col-md-6">
                              <input type="hidden" name="user_id" value="{{$user_id}}">
                              <label for="exampleInputUsername1"><strong>First Name : </strong> {{$first_name}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputUsername1"><strong>Last Name : </strong>{{$last_name}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Email address : </strong>{{$email}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Gender : </strong> {{$gender==1?'Male':($gender==2?'Female':'Other')}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Country : </strong> {{$country_name}}</label>
                              
                            </div>
                            {{--<div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>State : </strong> {{$state_name}}</label>
                              
                            </div>--}}
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>City : </strong> {{$city_name}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Profile Image  : </strong></label>
                              @if(!empty($profile_image))
                              <img src="{{ asset('public/uploads/profile_image/' . $profile_image)}}" style="max-width: 400px;max-height: 400px;">
                              @endif
                            </div>
                          </div>
                          <div class="row">
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Shop Name : </strong> {{$shop_name}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>About Shop : </strong> {{$about_shop}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Services : </strong></label>
                              @if($services)
                              <ul>
                                @foreach($services as $service)
                                  <li>
                                  {{$service->service_name}}
                                  </li>
                                @endforeach
                              </ul>
                              @endif
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Shop Logo  : </strong></label>
                              @if(!empty($shop_logo))
                              <img src="{{ asset('public/uploads/shop_image/' . $shop_logo)}}" style="max-width: 400px;max-height: 400px;">
                              @endif
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Banner Image  : </strong></label>
                              @if(!empty($shop_banner))
                              <img src="{{ asset('public/uploads/shop_image/' . $shop_banner)}}" style="max-width: 400px;max-height: 400px;">
                              @endif
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- main-panel ends -->
        @endsection
        @push('scripts')
          
        @endpush