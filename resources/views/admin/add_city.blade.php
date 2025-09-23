@extends('admin.layouts.layout')

@section('content')
<style>
   .select2-results__option,
.select2-selection__rendered {
  text-transform: uppercase;
}
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $city_name=$country_id=$city_id=$city_name_ar=$latitude=$longitude="";  
                  if($city_detail)
                  {
                    $country_id=$city_detail->country_id;
                    $city_id=$city_detail->city_id;
                    $city_name=$city_detail->city_name;
                    $city_name_ar=$city_detail->city_name_ar;
                    $latitude=$city_detail->latitude;
                    $longitude=$city_detail->longitude;
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.cityList')}}" class="btn btn-outline-info btn-fw" style="float: right;">City List</a>
                    <h4 class="card-title">Location Management</h4>
                    <p class="card-description"> Add / Update City  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addCity')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$city_id}}">
                          <label for="exampleInputUsername1">Country Name</label>
                          <select class="form-select form-select-sm select-drop" id="country_id" name="country_id">
                            <option value="">Select Country</option>
                            @if($country)
                            @foreach($country as $countrys)
                              <option value="{{$countrys->country_id }}" {{$country_id==$countrys->country_id?'selected':''}}>{{$countrys->country_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div> 
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">City Name</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter City Name"  name="city_name" value="{{$city_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">City Name Arabic</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter City Name Arabic"  name="city_name_ar" value="{{$city_name_ar}}" required>
                        </div>    
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Latitude</label>
                          <input type="text" class="form-control form-control-sm" placeholder="6.51569110"  name="latitude" value="{{$latitude}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Longitude</label>
                          <input type="text" class="form-control form-control-sm" placeholder="36.95410700"  name="longitude" value="{{$longitude}}" required>
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
       <script>
          $(document).ready(function() {
            $('.select-drop').select2({
              placeholder: 'Select Country',
              allowClear: true
            });
          });
        </script>
        @endpush