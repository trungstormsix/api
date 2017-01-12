<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/less" href="{{asset('assets/css/buildform.less')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}">
</head>
<body>
	<div class="wrap-form">
		<form action="" class="form-contact" id="form-contact">
			<span class="form-legend">Please provide your details below</span>
			<div class="form-block">
				<label for="" class="tag-label">Name</label>
				<div class="wrap-form-control">
					<input type="text" class="tag-input" required>
				</div>
			</div>
			<div class="clr"></div>
			<div class="form-block">
				<label for="" class="tag-label">Email</label>
				<div class="wrap-form-control">
					<input type="text" class="tag-input" placeholder="youremail@email.com" name="email">
				</div>
			</div>
			<div class="clr"></div>
			<div class="form-block">
				<label for="" class="tag-label">Subject</label>
				<div class="wrap-form-control">
					<select name="" id="" class="tag-input">
						<option>hoang</option>
						<option>toan</option>
						<option>quang</option>
						<option>trung</option>
					</select>
				</div>
			</div>
			<div class="clr"></div>
			<div class="form-block">
				<label for="" class="tag-label">Message</label>
				<div class="wrap-form-control">
					<textarea class="tag-input" rows="3" name="message"></textarea>
				</div>
			</div>
			<div class="clr"></div>
			<div class="form-block">
				<label for="" class="tag-label"></label>
				<div class="wrap-form-control">
					<input type="radio" id="cb1" name="cbox1" checked="" class="tag-input-radio">
					<label for="cb1">check box 1</label>
					<input type="radio" id="cb2" name="cbox1" class="tag-input-radio">
					<label for="cb2">check box 2</label>
				</div>
			</div>
			<div class="clr"></div>
			<div class="form-block">
				<label for="" class="tag-label">Upload file</label>
				<div class="wrap-form-control">
					<input type="file" class="tag-input-file">
				</div>
			</div>
			<div class="clr"></div>
			<div class="form-block">
				<label for="" class="tag-label"></label>
				<div class="wrap-form-control">
					<div class="g-recaptcha" data-sitekey="6LcNiREUAAAAADZX64aDCKgM80aB6lEOKZ9fr28m"></div>
					<input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
				</div>
			</div>
			<div class="clr"></div>
			<div class="form-block">
				<label for="" class="tag-label"></label>
				<div class="wrap-form-control">
					<input type="submit" class="tag-input-submit btn btn-warning">
				</div>
			</div>
			<div class="clr"></div>
		</form>
	</div>
	<script type="text/javascript" src="{{asset('assets/js/jquery-2.1.1.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/js/less.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/js/bootstrap.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
	<script src="https://www.google.com/recaptcha/api.js?hl=vi"></script>
	<script>
		// just for the demos, avoids form submit
		
		$( "#form-contact" ).validate({
			ignore: ".ignore",
		  	rules: {
			    email: {
			      	required: true,
			      	email: true
			    },
			    message: {
			      	required: true
			    },
			    hiddenRecaptcha: {
	                required: function () {
	                    if (grecaptcha.getResponse() == '') {
	                        return true;
	                    } else {
	                        return false;
	                    }
	                }
	            }
		  	},
		  	errorClass: "validate-error-class",
		   	validClass: "validate-class"
		});
</script>
</body>
</html>