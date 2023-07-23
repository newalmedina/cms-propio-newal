@if (session()->has('success-alert'))
<div class="row">
    <div class="col-12 ">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ date('d/m/Y H:i:s') }}</strong>
            {{ Session::get('success-alert',"") }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    //  $(document).ready(function() {
    //     $(".successSaved").modal("hide");
    //     $("#modalConfirmDelete").modal("hide");
    // });
</script>

@endif
