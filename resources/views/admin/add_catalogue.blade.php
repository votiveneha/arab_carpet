@extends('admin.layouts.layout')

@section('content')

<style>
  .ck-editor__editable {
    min-height: 300px !important; /* Or whatever height you want */
  }
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
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.adminProductList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Admin Product List</a>
                    <h4 class="card-title">Admin Product Management</h4>
                    <p class="card-description"> Add Product Catalogue </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addProductCatalogue')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Make</label>
                          <select required class="form-select form-select-sm select-drop" id="brand" name="brand_id">
                            <option value="">Select Make</option>  
                            @if($brand)
                            @foreach($brand as $brands)
                              <option value="{{$brands->id }}">{{$brands->brand_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Model</label>
                          <select required class="form-select form-select-sm select-drop" id="model" name="model_id">
                            <option>Select Model</option>
                            
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Generation</label>
                          <select required class="form-select form-select-sm select-drop" id="generation" name="generation_id">
                            <option>Select Generation</option>
                            
                          </select>
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
          document.getElementById('product_image').addEventListener('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('image_preview');

            if (input.files && input.files[0]) {
              const reader = new FileReader();

              reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
              };

              reader.readAsDataURL(input.files[0]);
            } else {
              preview.src = '#';
              preview.style.display = 'none';
            }
          });
        </script>
        
        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            });
        </script>
        @endif
        
        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session('error') }}",
                confirmButtonText: "OK"
              });
            });
        </script>
        @endif

        <script>
          $(document).on('click', '.add-part-type', function () {
            let newRow = `
              <div class="row part-type-row mt-2">
                <div class="form-group col-md-5">
                  <input type="text" class="form-control form-control-sm" name="part_type[]">
                </div>
                <div class="form-group col-md-1">
                  <button type="button" class="btn btn-outline-danger remove-part-type">-</button>
                </div>
              </div>`;
            $('#part-type-wrapper').append(newRow);
          });

          $(document).on('click', '.remove-part-type', function () {
            let partTypeId = $(this).attr('pid');
            if(partTypeId)
            {
              $.ajax({
                url: "{{url('/admin/deletePartType')}}/" + partTypeId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        button.closest('.part-type-row').remove();
                    } else {
                        alert('Delete failed');
                    }
                },
                error: function () {
                    alert('Error occurred');
                }
              }); 
            }
            $(this).closest('.part-type-row').remove();
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
                  $('#subcategory').html('<option value="">Select Part</option>');
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
        </script>
        <!--script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
          <script>
              ClassicEditor
                  .create(document.querySelector('#video-introduction'))
                  .catch(error => {
                      console.error(error);
                  });
          </script-->

        @endpush