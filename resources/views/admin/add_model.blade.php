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
                  $ar_model_name=$fr_model_name=$ru_model_name=$fa_model_name=$ur_model_name=$model_name=$brand_id=$model_id="";
                  if($make_model)
                  {
                    $brand_id=$make_model->brand_id;
                    $model_id=$make_model->id;
                    $model_name=$make_model->model_name;
                    $ar_model_name=$make_model->ar_model_name;
                    $fr_model_name=$make_model->fr_model_name;
                    $ru_model_name=$make_model->ru_model_name;
                    $fa_model_name=$make_model->fa_model_name;
                    $ur_model_name=$make_model->ur_model_name;

                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.modelList')}}" class="btn btn-outline-info btn-fw" style="float: right;"> Model List</a>
                    <h4 class="card-title"> Model Management</h4>
                    <p class="card-description"> Add / Update  Model  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addModel')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$model_id}}">
                          <label for="exampleInputUsername1">Make Name</label>
                          <select class="form-select form-select-sm select-drop" id="brand_id" name="brand_id" required>
                            @if($brand)
                            @foreach($brand as $types)
                              <option value="{{$types->id }}" {{$brand_id==$types->id?'selected':''}}>{{$types->brand_name}}/{{$types->ar_brand_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Model Name</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Model Name"  name="model_name" value="{{$model_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Model Name Arabic</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Model Name Arabic"  name="ar_model_name" value="{{$ar_model_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Model Name French</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Model Name French"  name="fr_model_name" value="{{$fr_model_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Model Name Russian</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Model Name Russian"  name="ru_model_name" value="{{$ru_model_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Model Name Dari </label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Model Name Dari"  name="fa_model_name" value="{{$fa_model_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Model Name Urdu</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Model Name Urdu"  name="ur_model_name" value="{{$ur_model_name}}" required>
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
        @endpush
