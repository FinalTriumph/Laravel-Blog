@if(count($errors))
    <div class="alert_div">
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endforeach
    <p style='text-align: center; color: #fff;'><small>(click anywhere on this page to close this message)</small></p>
    </div>
@endif

@if(session('success'))
    <div class="alert_div">
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        <p style='text-align: center; color: #fff;'><small>(click anywhere on this page to close this message)</small></p>
    </div>
@endif

@if(session('error'))
    <div class="alert_div">
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        <p style='text-align: center; color: #fff;'><small>(click anywhere on this page to close this message)</small></p>
    </div>
@endif
<script type="text/javascript">
/* global $ */
    $('.alert_div').click(function() {
        $('.alert_div').hide();
    });
</script>