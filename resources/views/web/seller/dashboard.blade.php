@extends('web.seller.layout.layout')

@section('content')

<style>
  .ck-editor__editable {
    min-height: 300px !important; /* Or whatever height you want */
  }
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('seller.productList')}}" class="btn btn-outline-info btn-fw" style="float: right;">{{__('messages.product_list')}}</a>
                    <h4 class="card-title">{{$user_detail->first_name}} {{$user_detail->last_name}}</h4>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  
                    <h4 class="card-title">{{__('messages.top_search_product')}}</h4>
                    
                      <div class="table-responsive">
                        <table class="table table-striped product_tbl" id="example">
                          <thead>
                            <tr>
                              <th>{{__('messages.tbl_sr_no')}} </th>
                              <th> {{__('messages.MAKE')}} </th>
                              <th> {{__('messages.MODEL')}} </th>
                              <th> {{__('messages.PART TYPE')}} </th>
                              <th> {{__('messages.PART')}} </th>
                              <th> {{__('messages.GENERATION')}} </th>
                              <th> {{__('messages.tbl_variant')}} </th>
                              <th> {{__('messages.tbl_count')}}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @php $i=1; @endphp
                            @if($topProducts)
                            @foreach($topProducts as $products)
                            <tr>
                              <td>{{$i}}</td>
                              <td>{{$products->brand_name}}</td>
                              <td>{{$products->model_name}}</td>
                              <td>{{$products->category_name}}</td>
                              <td>{{$products->subcategory_name}}</td>
                              <td>{{$products->start_year}} - {{$products->end_year}}</td>
                              <td>{{$products->part_type_label}}</td>
                              <td>{{$products->total_searches}}</td>
                            </tr>
                            @endforeach
                            @php $i++;@endphp
                            @endif
                          </tbody>
                        </table>
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
        

        @endpush