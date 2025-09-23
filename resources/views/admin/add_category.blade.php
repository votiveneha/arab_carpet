@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $category_name=$language_id=$ar_category_name=$fr_category_name=$ru_category_name=$fa_category_name=$ur_category_name="";
                  if($category_detail)
                  {
                    $language_id=$category_detail->id;
                    $category_name=$category_detail->category_name;
                    $ar_category_name=$category_detail->ar_category_name;
                    $fr_category_name=$category_detail->fr_category_name;
                    $ru_category_name=$category_detail->ru_category_name;
                    $fa_category_name=$category_detail->fa_category_name;
                    $ur_category_name=$category_detail->ur_category_name;
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.CategoryList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Part Type List</a>
                    <h4 class="card-title">Part Type Management</h4>
                    <p class="card-description"> Add / Update Part Type  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addCategory')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$language_id}}">
                          <label for="exampleInputUsername1">Part Type Name</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Part Type Name" aria-label="catename" name="category_name" value="{{$category_name}}">
                        </div>
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$language_id}}">
                          <label for="exampleInputUsername1">Part Type Name Arabic</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Part Type Name Arabic" aria-label="ar_category_name" name="ar_category_name" value="{{$ar_category_name}}">
                        </div>
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$language_id}}">
                          <label for="exampleInputUsername1">Part Type Name French</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Part Type Name French" aria-label="fr_category_name" name="fr_category_name" value="{{$fr_category_name}}">
                        </div>
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$language_id}}">
                          <label for="exampleInputUsername1">Part Type Name Russian</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Part Type Name Russian" aria-label="ru_category_name" name="ru_category_name" value="{{$ru_category_name}}">
                        </div>
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$language_id}}">
                          <label for="exampleInputUsername1">Part Type Name Dari</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Part Type Name Dari" aria-label="fa_category_name" name="fa_category_name" value="{{$fa_category_name}}">
                        </div>
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$language_id}}">
                          <label for="exampleInputUsername1">Part Type Name Urdu</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Part Type Name Urdu" aria-label="ur_category_name" name="ur_category_name" value="{{$ur_category_name}}">
                        </div>
                      </div>
                      <input type="hidden" name="user_time" value="" id="user_timezone">
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
