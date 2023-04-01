@if (session()->has('success'))
<div class="alert alert-success" role="alert">
    {{session()->get('success')}}
</div>
@endif

@if (session()->has('danger'))
<div class="alert alert-danger" role="alert">
    {{session()->get('danger')}}
</div>
@endif

@if (session()->has('warning'))
<div class="alert alert-warning" role="alert">
    {{session()->get('warning')}}
</div>
@endif

@if (session()->has('primary'))
<div class="alert alert-primary" role="alert">
    {{session()->get('primary')}}
</div>
@endif