@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $country_name=$country_id="";  
                  if($country_detail)
                  {
                    $country_id=$country_detail->country_id;
                    $country_name=$country_detail->country_name;
                    
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.countryList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Country List</a>
                    <h4 class="card-title">Location Management</h4>
                    <p class="card-description"> Add / Update Country  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addCountry')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$country_id}}">
                          <label for="exampleInputUsername1">Country Name</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Country Name" aria-label="catename" name="country_name" value="{{$country_name}}">
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