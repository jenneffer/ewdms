<!DOCTYPE html>
<html>
<head>
    <title>Laravel Dependent Dropdown Example - websolutionstuff.com</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<div class="container">
    <h3 class="text-center" style="margin-bottom: 20px;">Laravel Dependent Dropdown Example - websolutionstuff.com</h3>
     <div class="col-md-8 col-md-offset-2">
    	<div class="panel panel-default">
	      	<div class="panel-heading">Select State and Get Related City</div>
	      	<div class="panel-body">
	            <div class="form-group">
	                <label for="title">Select State:</label>
	                <select name="state" class="form-control">
	                    <option value="">Select State</option>
	                    @foreach ($states as $key => $value)
	                        <option value="{{ $key }}">{{ $value }}</option>
	                    @endforeach
	                </select>
	            </div>
	            <div class="form-group">
	                <label for="title">Select City:</label>
	                <select name="city" class="form-control">
	                </select>
	            </div>
	      	</div>
      	</div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="state"]').on('change', function() {
            var stateID = $(this).val();
            if(stateID) {
                $.ajax({
                    url: '/city/'+stateID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {                      
                        $('select[name="city"]').empty();
                        $.each(data, function(key, value) {
                        $('select[name="city"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            }else{
                $('select[name="city"]').empty();
            }
        });
    });
</script>
</body>
</html>