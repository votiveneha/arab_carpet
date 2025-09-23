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
                  $ar_subcat_name=$fr_subcat_name=$ru_subcat_name=$fa_subcat_name=$ur_subcat_name=$subcat_name=$category_id=$subcategory_id="";
                  if($subcategory)
                  {
                    $category_id=$subcategory->category_id;
                    $subcategory_id=$subcategory->id;
                    $subcat_name=$subcategory->subcat_name;
                    $ar_subcat_name=$subcategory->ar_subcat_name;
                    $fr_subcat_name=$subcategory->fr_subcat_name;
                    $ru_subcat_name=$subcategory->ru_subcat_name;
                    $fa_subcat_name=$subcategory->fa_subcat_name;
                    $ur_subcat_name=$subcategory->ur_subcat_name;
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.subCategoryList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Part List</a>
                    <h4 class="card-title">Part Management</h4>
                    <p class="card-description"> Add / Update Part  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addSubCategory')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$subcategory_id}}">
                          <label for="exampleInputUsername1">Part Type Name</label>
                          <select class="form-select form-select-sm select-drop" id="category_id" name="category_id">
                            @if($category)
                            @foreach($category as $types)
                              <option value="{{$types->id }}" {{$category_id==$types->id?'selected':''}}>{{$types->category_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Part Name</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Part Name"  name="subcat_name" value="{{$subcat_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Part Name Arabic</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Part Name Arabic"  name="ar_subcat_name" value="{{$ar_subcat_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Part Name French</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Part Name French"  name="fr_subcat_name" value="{{$fr_subcat_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Part Name Russian</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Part Name Russian"  name="ru_subcat_name" value="{{$ru_subcat_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Part Name Dari</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Part Name Dari"  name="fa_subcat_name" value="{{$fa_subcat_name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Part Name Urdu</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Part Name Urdu"  name="ur_subcat_name" value="{{$ur_subcat_name}}" required>
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
