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
                  $brand_id=$year_id=$model_id=$start_year=$end_year=$gen_text="";  
                  if($make_year)
                  {
                    $year_id=$make_year->id;
                    $brand_id=$make_year->brand_id;
                    $model_id=$make_year->model_id;
                    $start_year=$make_year->start_year;
                    $end_year=$make_year->end_year;
                    $gen_text=$make_year->gen_text;
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.makeYearList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Generation List</a>
                    <h4 class="card-title">Generation Management</h4>
                    <p class="card-description"> Add / Update Generation  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addMakeYear')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Brand Name</label>
                          <select class="form-select form-select-sm select-drop" id="brand" name="brand_id">
                            <option value="">Select Brand</option>
                            @if($brand)
                            @foreach($brand as $types)
                              <option value="{{$types->id }}" {{$brand_id==$types->id?'selected':''}}>{{$types->brand_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Make Model</label>
                          <select required class="form-select form-select-sm select-drop" id="model" name="model_id">
                            <option value="">Select Model</option>
                            @if($model)
                            @foreach($model as $models)
                            <option value="{{$models->id }}" {{$model_id==$models->id?'selected':''}}>{{$models->model_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$year_id}}">
                          <label for="exampleInputUsername1">Start Year</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Start Year"  name="start_year" value="{{$start_year}}" required>
                        </div> 
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">End Year</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter End Year"  name="end_year" value="{{$end_year}}" required>
                        </div>    
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Identification Label</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Identification Label"  name="gen_text" value="{{$gen_text}}" required>
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
              placeholder: 'Select an option',
              allowClear: true
            });
          });
        </script>
       <script>
          
          $(document).ready(function () {
            $(document).on('change', '#brand', function () {
              var cid = this.value;   //let cid = $(this).val(); we cal also write this.
              $.ajax({
                url: "{{url('/admin/getModel')}}",
                type: "POST",
                datatype: "json",
                data: {
                  brand_id: cid,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  $('#model').html('<option value="">Select Model</option>');
                  $.each(result.city, function(key, value) {
                    $('#model').append('<option value="' +value.id+ '">' +value.model_name+ '</option>');
                  });
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
                });
            });
          });
        </script>
        @endpush