@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $brand_name=$brand_id=$ar_brand_name=$fr_brand_name=$ru_brand_name=$fa_brand_name=$ur_brand_name="";
                  if($brand_detail)
                  {
                    $brand_id=$brand_detail->id;
                    $brand_name=$brand_detail->brand_name;
                    $ar_brand_name=$brand_detail->ar_brand_name;
                    $fr_brand_name=$brand_detail->fr_brand_name;
                    $ru_brand_name=$brand_detail->ru_brand_name;
                    $fa_brand_name=$brand_detail->fa_brand_name;
                    $ur_brand_name=$brand_detail->ur_brand_name;
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.BrandList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Make List</a>
                    <h4 class="card-title">Make Management</h4>
                    <p class="card-description"> Add / Update Make  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addBrand')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$brand_id}}">
                          <label for="exampleInputUsername1">Make Name</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Make Name"  name="brand_name" value="{{$brand_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Make Name Arabic</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Make Name Arabic"  name="ar_brand_name" value="{{$ar_brand_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Make Name French</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Make Name French"  name="fr_brand_name" value="{{$fr_brand_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Make Name Russian</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Make Name Russian"  name="ru_brand_name" value="{{$ru_brand_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Make Name Dari</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Make Name Dari"  name="fa_brand_name" value="{{$fa_brand_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Make Name Urdu</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Make Name Urdu"  name="ur_brand_name" value="{{$ur_brand_name}}" required>
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
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
