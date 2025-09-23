@extends('web.seller.layout.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                      <h4 class="card-title">REQUEST A {{__('messages.tbl_product')}}</h4>
                      <form class="forms-sample" method="post" action="{{route('seller.addSellerRequest')}}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                          <div class="row">
                            <input type="hidden" name="user_id" value="{{Auth::id()}}">
                            <input type="hidden" name="request_for" value="car">
                            <div class="form-group col-md-6">
                              <label for="exampleInputUsername1">{{__('messages.MAKE')}}</label>
                              <input type="text" class="form-control form-control-sm" placeholder="{{__('messages.MAKE')}}" aria-label="Username" name="brand">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputUsername1">{{__('messages.MODEL')}}</label>
                              <input type="text" class="form-control form-control-sm" placeholder="{{__('messages.MODEL')}}" aria-label="Username" name="model">
                            </div>
                            
                            <div class="form-group col-md-6">
                              <label for="examspleInputEmail1">{{__('messages.GENERATION')}}</label>
                              <input type="text" class="form-control form-control-sm" placeholder="{{__('messages.GENERATION')}}" name="generation" >
                            </div>
                          </div>
                          
                          <button type="submit" class="btn btn-primary me-2">Submit</button>
                        </form>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                      <h4 class="card-title">REQUEST A {{__('messages.tbl_part')}}</h4>
                      <form class="forms-sample" method="post" action="{{route('seller.addSellerRequest')}}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                          <div class="row">
                            <input type="hidden" name="user_id" value="{{Auth::id()}}">
                            <input type="hidden" name="request_for" value="part">
                            <div class="form-group col-md-6">
                              <label for="exampleInputUsername1">{{__('messages.PART TYPE')}}</label>
                              <input type="text" class="form-control form-control-sm" placeholder="{{__('messages.PART TYPE')}}" aria-label="Username" name="category">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputUsername1">{{__('messages.PART')}}</label>
                              <input type="text" class="form-control form-control-sm" placeholder="{{__('messages.PART')}}" aria-label="Username" name="subcategory">
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
        var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
        triggerTabList.forEach(function (triggerEl) {
          var tabTrigger = new bootstrap.Tab(triggerEl)

          triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
          })
        })
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
        @endpush