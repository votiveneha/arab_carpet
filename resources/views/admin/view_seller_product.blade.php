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
                <div class="card">
                  <div class="card-body">
                      <a href="{{route('admin.sellerList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Seller List</a>
                      <h4 class="card-title">Seller Management</h4>
                      <!--p class="card-description"> Add / Update Blog  </p-->

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <a href="{{route('admin.viewSeller', ['id' => $seller_id])}}">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link " id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Profile</button>
                        </li>
                      </a>
                      <a href="{{route('admin.viewSellerEnquiry', ['id' => $seller_id])}}">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" >Request</button>
                        </li>
                      </a>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">Product</button>
                      </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="seller_pro">
                      <div class="tab-pane active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                          <form class="forms-sample" id="filters">
                            {!! csrf_field() !!}
                              <div class="row">
                                <input type="hidden" name="seller_id" id="seller_id" value="{{$seller_id}}">
                                <div class="form-group col-md-3">
                                  <label for="exampleInputEmail1">Brand</label>
                                  <select required class="form-select form-select-sm" id="brand" name="brand_id">
                                    <option value="">Select Brand</option>
                                    @if($brand)
                                    @foreach($brand as $brands)
                                      <option value="{{$brands->id }}">{{$brands->brand_name}}</option>
                                    @endforeach
                                    @endif
                                  </select>
                                </div>

                                <div class="form-group col-md-3">
                                  <label for="exampleInputEmail1">Make Model</label>
                                  <select required class="form-select form-select-sm" id="model" name="model_id">
                                    <option value="">Select Model</option>
                                    
                                  </select>
                                </div>

                                <div class="form-group col-md-3">
                                  <label for="exampleInputEmail1">Product</label>
                                  <select class="form-select form-select-sm" id="category" name="category_id">
                                    <option value="">Select Product</option>
                                    @if($category)
                                    @foreach($category as $categorys)
                                    <option value="{{$categorys->id }}">{{$categorys->category_name}}</option>
                                    @endforeach
                                    @endif
                                  </select>
                                </div>

                                <div class="form-group col-md-3">
                                  <label for="exampleInputEmail1">Subcategory</label>
                                  <select class="form-select form-select-sm" id="subcategory" name="subcategory_id">
                                    <option value="">Select Subcategory</option>
                                  
                                  </select>
                                </div>
                              </div>
                            </form>
                      
                      
                          <div class="table-responsive">
                            <table class="table table-striped" id="example">
                              <thead>
                                <tr>
                                  <th><input type="checkbox" id="selectAll"></th>
                                  <th> Sr no </th>
                                  <th> Brand </th>
                                  <th> Model </th>
                                  <th> Product </th>
                                  <th> Subcategory </th>
                                  <th> Generation </th>
                                  <th> Variant </th>
                                  <th> Price </th>
                                </tr>
                              </thead>
                              <tbody>
                              
                              </tbody>
                            </table>
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

            $('#category').change(function () {
              var sid = this.value;
              $.ajax({
                url: "{{url('/admin/getSubcategory')}}",
                type: "POST",
                datatype: "json",
                data: {
                  category_id: sid,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  console.log(result);
                  $('#subcategory').html('<option value="">Select Subcategory</option>');
                  $.each(result.subcat, function(key, value) {
                    $('#subcategory').append('<option value="' +value.id+ '">' +value.subcat_name+ '</option>')
                  });
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
              });
            });
          });

          let table = $('#example').DataTable({
              processing: true,
              serverSide: true,
              ajax: {
                  url: "{{url('/admin/getSellerProduct')}}",
                  
                  type: 'POST',
                  data: function (d) {
                    let brand_id = $('#brand').val();
                      if (!brand_id) {
                          //return false; // prevent request if brand not selected
                      }
                      return $.extend({}, d, {
                          brand_id: $('#brand').val(),
                          seller_id: $('#seller_id').val(),
                          model_id: $('#model').val(),
                          category_id: $('#category').val(),
                          subcategory_id: $('#subcategory').val(),
                           _token: "{{ csrf_token() }}"
                      });
                  }
              },
              lengthMenu: [[10, 25, 50, 100,500, -1], [10, 25, 50, 100,500, "All"]],
              pageLength: 10,
              columns: [
                      { data: 'checkbox', orderable: false, searchable: false },
                      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                      { data: 'brand', name: 'brand' },
                      { data: 'model', name: 'model' },
                      { data: 'category', name: 'category' },
                      { data: 'subcategory', name: 'subcategory' },
                      { data: 'generation', name: 'generation' },
                      { data: 'variant', name: 'variant' },
                      { data: 'price', name: 'price' }
                      
                  ],
              //deferLoading: 0 
          });

        // Only trigger reload when brand is selected
          $('#brand').on('change', function () {
              if ($(this).val()) {
                  table.ajax.reload();
              }
          });
          // Reload table when filters change
          $('#filters select').on('change', function () {
              table.ajax.reload();
          });
        </script>
        @endpush