@extends('admin.layouts.layout')

@section('content')
<style>
  i.mdi {
    font-size: 18px;
  }
  select.form-select {
    padding: 5px 30px;
    border: 0;
    outline: 1px solid #CED4DA;
    color: #000000;
    padding-left: .5rem;
}
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <form class="forms-sample" id="groupForm" method="post" action="{{route('admin.createGroup')}}" enctype="multipart/form-data">
              {!! csrf_field() !!}
                <div class="row">
                  <div class="col-md-12 grid-margin stretch-card">
                    <?php
                      $group_name=$group_id=$group_note="";  
                      if($group_detail)
                      {
                        $group_id=$group_detail->id;
                        $group_name=$group_detail->group_name;                   
                        $group_note=$group_detail->group_note;                   
                      }
                    ?>
                    <div class="card">
                      <div class="card-body">
                        <a href="{{route('admin.groupList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Group List</a>
                        <h4 class="card-title">Group Management</h4>
                        <p class="card-description"> Add / Update Group  </p>
                        
                          <div class="row">
                            <div class="form-group col-md-6">
                              <input type="hidden" name="group_id" value="{{$group_id}}">
                              <label for="exampleInputUsername1">Group Name</label>
                              <input required type="text" class="form-control form-control-sm" placeholder="Enter Group Name" aria-label="Servicename" name="group_name" value="{{$group_name}}">
                            </div>                        
                            <div class="form-group col-md-6">
                              
                              <label for="exampleInputUsername1">Group Note</label>
                              <input required type="text" class="form-control form-control-sm" placeholder="Enter Group Note" aria-label="Servicename" name="group_note" value="{{$group_note}}">
                            </div>
                            
                          </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                          <div class="table-responsive">
                            <table class="table table-striped" id="example">
                              <thead>
                                <tr>
                                  <td><input type="checkbox" id="selectAll"></td>
                                  <th> Sr no </th>
                                  <th> Brand name </th>                            
                                  <th> Model name</th>
                                  <th> Generation</th>                            
                                </tr>
                              </thead>
                              <tbody>
                                @if($make_model)
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($make_model as $list)
                                    @php
                                        $comboKey = $list->brand_id . '-' . $list->id . '-' . $list->gen_id;
                                    @endphp
                                <tr>
                                  <td><input type="checkbox"  {{ in_array($comboKey, $group_model_combinations) ? 'checked' : '' }} name="ids[]" value="{{ $list->id }}" class="selectBox" data-brand-id="{{ $list->brand_id }}" data-gen-id="{{ $list->gen_id }}"></td>
                                  <td>{{$i}}</td>
                                  <td> {{$list->brand_name}} </td>
                                  <td> {{$list->model_name}} </td>
                                  <td> {{$list->start_year}} - {{$list->end_year}} </td>
                                </tr>
                                @php $i++; @endphp 
                                @endforeach
                                @endif
                              </tbody>
                            </table>
                          </div>
                          <button type="submit" class="btn btn-primary me-2">Submit</button>
                        
                        
                      </div>
                    </div>
                  </div>
                </div>
            </form>

          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- main-panel ends -->
        @endsection
        @push('scripts')
       <script>
        $(document).ready( function () {
            var table = $('#example').DataTable( {
              "bPaginate": true,
              "bInfo": true,
              lengthMenu: [[10, 25, 50, 100,500, -1], [10, 25, 50, 100,500, "All"]],
              columnDefs: [
                              { orderable: false, targets: 0 } // Disable sorting on the first column (checkboxes)
                          ]
            });

            $('#groupForm').on('submit', function (e) {
              e.preventDefault(); // prevent default submission

              var form = $(this);
              var table = $('#example').DataTable();
              var allIds = [];
              var allBrandIds = [];
              var allGenId = [];

              // Collect checked rows
              table.$('input.selectBox:checked').each(function () {
                  allIds.push($(this).val());
                  allBrandIds.push($(this).data('brand-id'));
                  allGenId.push($(this).data('gen-id'));
              });

              // Remove existing hidden inputs (if any)
              form.find('input[name="ids[]"], input[name="brand_ids[]"], input[name="gen_ids[]"]').remove();

              // Add new hidden inputs
              for (let i = 0; i < allIds.length; i++) {
                  $('<input>').attr({ type: 'hidden', name: 'ids[]', value: allIds[i] }).appendTo(form);
                  $('<input>').attr({ type: 'hidden', name: 'brand_ids[]', value: allBrandIds[i] }).appendTo(form);
                  $('<input>').attr({ type: 'hidden', name: 'gen_ids[]', value: allGenId[i] }).appendTo(form);
              }

              // Now safely submit the form
              form.off('submit').submit(); // remove handler to prevent recursion
          });

          } );
       </script>

       <script>
          document.getElementById('selectAll').addEventListener('click', function (e) {
            let checkboxes = document.querySelectorAll('.selectBox');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
          });
          
          document.getElementById('bulkDeleteBtn').addEventListener('click', function (e) {
            e.preventDefault(); // Stop normal form submit

            const form = document.getElementById('bulkDeleteForm');
            const checkboxes = document.querySelectorAll('.selectBox:checked');

            if (checkboxes.length === 0) {
              Swal.fire({
                icon: 'warning',
                title: 'No selection',
                text: 'Please select at least one Part Type to delete.',
              });
              return;
            }

            Swal.fire({
              title: 'Are you sure?',
              text: "Selected Part Type will be deleted.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete selected'
            }).then((result) => {
              if (result.isConfirmed) {
                form.submit(); // Submit the form only if confirmed
              }
            });
          });
        </script>
        @endpush