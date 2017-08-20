@if(count($errors))
    <div class="alert_div">
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endforeach
    </div>
@endif

@if(session('success'))
    <div class="alert_div">
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert_div">
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    </div>
@endif
<script type="text/javascript">
/* global $ */
    $('.alert_div').click(function() {
        $('.alert_div').hide();
    });
</script>