@extends('web.common.layout') @section('content')
<style>
    .select2-results__option,
    .select2-selection__rendered {
        text-transform: uppercase;
    }

    #searchBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    section.privacy-policy-add {
    padding-top: 118px;
}
.privacy-policy-scroll {
    overflow: scroll;
    height: 600px;
}

.privacy-policy-scroll::-webkit-scrollbar {
    width: 10px;
}

</style>
<!-- Hero Section start-->
<section class="privacy-policy-add ">
    <div class="container privacy-policy-scroll">
        @if($policya)
        {!! $policya->policy_content !!}
        @endif
    </div>
    
</section>


@endsection @push('scripts')

<script>
    function checkFilters() {
        const year = $('#year').val();
        const brand = $('#brand').val();
        const model = $('#model').val();
        const category = $('#category').val();
        const subcategory = $('#subcategory').val();

        const allSelected = year && brand && model && category && subcategory;

        $('#searchBtn').prop('disabled', !allSelected);
    }

    $(document).ready(function () {
        checkFilters(); // initial check

        // Trigger check when any select2 dropdown changes
        $('#year, #brand, #model, #category, #subcategory').on('change', function () {
            checkFilters();
        });
    });
</script>
<script>
  $(document).ready(function() {
    $('.select2').select2();
  });
</script>

<script>
    $(document).ready(function () {
        $(document).on("change", "#brand", function () {
            var cid = this.value; //let cid = $(this).val(); we cal also write this.
            $.ajax({
                url: "{{url('/admin/getModel')}}",
                type: "POST",
                datatype: "json",
                data: {
                    brand_id: cid,
                    _token: "{{csrf_token()}}",
                },
                success: function (result) {
                    $("#model").html('<option value="">{{ __("messages.MODEL") }}</option>');
                    $.each(result.city, function (key, value) {
                        $("#model").append('<option value="' + value.id + '">' + value.model_name + "</option>");
                    });
                },
                errror: function (xhr) {
                    console.log(xhr.responseText);
                },
            });
        });

        $("#category").change(function () {
            var sid = this.value;
            $.ajax({
                url: "{{url('/admin/getSubcategory')}}",
                type: "POST",
                datatype: "json",
                data: {
                    category_id: sid,
                    _token: "{{csrf_token()}}",
                },
                success: function (result) {
                    console.log(result);
                    $("#subcategory").html('<option value="">{{ __("messages.PART") }}</option>');
                    $.each(result.subcat, function (key, value) {
                        $("#subcategory").append('<option value="' + value.id + '">' + value.subcat_name + "</option>");
                    });
                },
                errror: function (xhr) {
                    console.log(xhr.responseText);
                },
            });
        });

        $("#country").change(function () {
            var sid = this.value;
            $.ajax({
                url: "{{url('/admin/getcityByCountry')}}",
                type: "POST",
                datatype: "json",
                data: {
                    country_id: sid,
                    _token: "{{csrf_token()}}",
                },
                success: function (result) {
                    console.log(result);
                    $("#city").html('<option value="">{{ __("messages.ALL Location") }}</option>');
                    $.each(result.city, function (key, value) {
                        $("#city").append('<option value="' + value.city_id + '">' + value.city_name + "</option>");
                    });
                },
                errror: function (xhr) {
                    console.log(xhr.responseText);
                },
            });
        });
    });
    
</script>




@endpush
