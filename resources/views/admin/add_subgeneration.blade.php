@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $subgen_id=$brand_id=$model_id=$start_year=$end_year=$subgen_text=$generation_id="";  
                  if($subgen)
                  {
                    $subgen_id=$subgen->id;
                    $generation_id=$subgen->generation_id;
                    $start_year=$subgen->start_year;
                    $end_year=$subgen->end_year;
                    $subgen_text=$subgen->subgen_text;
                    $brand_id=$generation[0]->brand_id;
                    $model_id=$generation[0]->model_id;
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.subGenerationList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Sub Generation List</a>
                    <h4 class="card-title">Generation Management</h4>
                    <p class="card-description"> Add / Update Sub Generation  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addSubGeneration')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-4">
                          <label for="exampleInputUsername1">Brand Name</label>
                          <select class="form-select form-select-sm" id="brand" name="brand_id">
                            <option value="">Select Brand</option>
                            @if($brand)
                            @foreach($brand as $types)
                              <option value="{{$types->id }}" {{$brand_id==$types->id?'selected':''}}>{{$types->brand_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="exampleInputEmail1">Make Model</label>
                          <select required class="form-select form-select-sm" id="model" name="model_id">
                            <option value="">Select Model</option>
                            @if($model)
                            @foreach($model as $models)
                            <option value="{{$models->id }}" {{$model_id==$models->id?'selected':''}}>{{$models->model_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="exampleInputEmail1">Generation</label>
                          <select required class="form-select form-select-sm" id="generation" name="generation_id">
                            <option value="">Select Generation</option>
                            @if($generation)
                            @foreach($generation as $generations)
                            <option value="{{$generations->id }}" {{$generation_id==$generations->id?'selected':''}}>{{$generations->start_year}} - {{$generations->end_year}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$subgen_id}}">
                          <label for="exampleInputUsername1">Start Year</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Start Year"  name="start_year" value="{{$start_year}}" required>
                        </div> 
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">End Year</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter End Year"  name="end_year" value="{{$end_year}}" required>
                        </div>    
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Identification Label</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Identification Label"  name="subgen_text" value="{{$subgen_text}}" required>
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

            $(document).on('change', '#model', function () {
              var cid = this.value;   //let cid = $(this).val(); we cal also write this.
              $.ajax({
                url: "{{url('/admin/getgeneration')}}",
                type: "POST",
                datatype: "json",
                data: {
                  model_id: cid,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  $('#generation').html('<option value="">Select Generation</option>');
                  $.each(result.subcat, function(key, value) {
                    $('#generation').append('<option value="' +value.id+ '">' +value.start_year+' - '+value.end_year+ '</option>');
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