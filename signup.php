<?php

include 'global.php';

if ($_SESSION["token"] && $_SESSION["rem"]) {
	header("location: timeline.php");
}
unset($_SESSION['token']);
session_destroy();

$error = $message = '';

$response = makeGetAPICall('getDictionaries');

$resp_json = json_decode($response, true);

if (isset($resp_json)) {
	session_start();
	$_SESSION["CONNECTION_TYPE"] = $resp_json['CONNECTION_TYPE'];
	$_SESSION["CONTENT_APPROVAL"] = $resp_json['CONTENT_APPROVAL'];
	$_SESSION["COUNTRY"] = $resp_json['COUNTRY'];
	$_SESSION["LIFE_LAYERS"] = $resp_json['LIFE_LAYERS'];
	$_SESSION["OCCUPATION_TYPES"] = $resp_json['OCCUPATION_TYPES'];
	$_SESSION["RELATIONSHIP"] = $resp_json['RELATIONSHIP'];
	$_SESSION["SEARCH_CRITERIA"] = $resp_json['SEARCH_CRITERIA'];
	$_SESSION["STATES"] = $resp_json['STATES'];
	$_SESSION["TITLE"] = $resp_json['TITLE'];
	$_SESSION["VIEW_RIGHT"] = $resp_json['VIEW_RIGHT'];
	$_SESSION["VIEW_RIGHT_CONNECTION"] = $resp_json['VIEW_RIGHT_CONNECTION'];
	$_SESSION["VIEW_RIGHT_DEPRECATED"] = $resp_json['VIEW_RIGHT_DEPRECATED'];
}

$country = mapOptionSets('COUNTRY');

$state = mapOptionSets('STATES');

$viewability = mapOptionSets('VIEW_RIGHT');

if ($_SERVER["REQUEST_METHOD"] == "POST") {


	$firstname = $_POST['inputFirstName'];
	$lastname = $_POST['inputLastName'];
	$emailaddress = $_POST['inputEmailAddress'];
	$backupemailaddress = $_POST['inputBackupEmail'];
	$password = $_POST['inputChoosePassword'];
	$profileviewability = $_POST['VIEW_RIGHT_timeline'];
	$timelineviewability = $_POST['VIEW_RIGHT_tree'];
	$image = $_FILES["profile_pic"];


	$imageExtension = $image = '';
	$check = getimagesize($_FILES["profile_pic"]["tmp_name"]);

	if ($check !== false) {
		$image = base64_encode(file_get_contents($_FILES["profile_pic"]["tmp_name"]));
		$imageExtension = end((explode(".", $_FILES["profile_pic"]["name"])));
		$fileType = $_FILES['profile_pic']['type'];
		$dataUri = 'data:' . $fileType . ';base64,' . $image;

		$_SESSION["PROFILE_PIC"] = $dataUri;
	}

	$body = json_encode(
		array(
			"firstname" => $firstname,
			"lastname" => $lastname,
			"emailaddress" => $emailaddress,
			"backupemailaddress" => $backupemailaddress,
			"profilepic" => $dataUri,
			"pictype" => $imageExtension,
			"password" => $password,
			"profileviewability" => $profileviewability,
			"timelineviewability" => $timelineviewability
		)
	);


	$response = makePostAPIcall('signUp', $body);
	$resp_json = json_decode($response, true);
	if (array_key_exists("token", $resp_json)) {
		session_start();
		$name = $_SESSION["username"] = $firstname . " " . $lastname;
		$_SESSION["token"] = $resp_json['token'];
		$_SESSION["loggedin"] = true;
		$_SESSION["rem"] = true;
		$_SESSION["PROFILE_PIC"] = $dataUri;
		$_SESSION["background"] = "Summer";
		$_SESSION['firstname'] = $firstname;
		$_SESSION['lastname'] = $lastname;
		$_SESSION["emailaddress"] = $emailaddress;

		header("location: onboarding.php");
	} else {
		if (preg_match("/'([^']+)'/", $resp_json['error'], $matches)) {
			$errorMessage = $matches[1];
		} else {
			$errorMessage = "Error creating account, try again later.";
		}
	}
}

?>
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<link href="assets/plugins/datetimepicker/css/classic.css" rel="stylesheet" />
	<link href="assets/plugins/datetimepicker/css/classic.time.css" rel="stylesheet" />
	<link href="assets/plugins/datetimepicker/css/classic.date.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css">
	<link rel="stylesheet" href="assets/plugins/notifications/css/lobibox.min.css" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<title>Dignitrees - Family App</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100..900&display=swap" rel="stylesheet">

</head>

<body class="bg-theme bg-theme2">
	<!--wrapper-->
	<div class="wrapper">

		<div class="container login-container">
			<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
				<div class="col">
					<div class="mb-0">
						<div class="card-body login-card">
							<div class="pad-20">
								<div class="text-center logo_login">
									<img src="assets/images/logo-icon.png" width="40" alt="" />
								</div>
								<div class="text-center mb-4">
									<h5 class="">Dignitrees Family App</h5>
									<p class="mb-0 op-90 main-label">Welcome to the Dignitrees Family App. Let's create your account.</p>
								</div>
								<div class="form-body">
									<form class="row g-3" action="signup.php" method="POST" enctype="multipart/form-data">
										<div id="step1">
											<div class="col-12 spacing-20">
												<div class="d-flex align-items-center gap-2">
													<div class="numberCircle">
														<span>1</span>
													</div>
													<span class="stepDescription text-white main-label">Enter your name and email.</span>
												</div>
											</div>
											<div class="col-12 spacing-10">
												<div class="spacing-10">
													<label for="inputFirstName" class="form-label text-white main-label">Name<sup>*</sup></label>
													<p class="mb-2 text-white login-sublabel"><i>Enter first and last names separately.</i></p>
												</div>
												<input type="text" class="form-control border-white" id="inputFirstName" name="inputFirstName" placeholder="First name" required value="<?php echo $firstname; ?>">
											</div>
											<div class="col-12 spacing-20">
												<input type="text" class="form-control border-white" id="inputLastName" name="inputLastName" placeholder="Last name" required value="<?php echo $lastname; ?>">
											</div>
											<div class="col-12 spacing-20">
												<div class="spacing-10">
													<label for="inputEmailAddress" class="form-label text-white main-label ">Email<sup>*</sup></label>
													<p class="mb-2 text-white login-sublabel">
														This will be your username.
													</p>
												</div>
												<input type="email" class="form-control border-white" id="inputEmailAddress" name="inputEmailAddress" placeholder="name@domain" value="<?php echo $_POST['inputEmailAddress']; ?>" required>
												<span class="mb-0 error_message text-danger"><?php echo $errorMessage; ?></span>
											</div>
											<div class="col-12">
												<div class="spacing-10">
													<label for="inputEmailAddress" class="form-label text-white main-label">Backup Email</label>
													<br>
													<span class="mb-2 text-white login-sublabel">
														Enter a different email address.
													</span>
												</div>
												<input type="email" class="form-control border-white" id="inputBackupEmail" placeholder="name@domain" name="inputBackupEmail" value="<?php echo $backupemailaddress; ?>">
											</div>

											<div class="col-12">
												<p class="mb-0 text-danger" id="txtError"><?php echo $error; ?></p>
												<p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
											</div>
											</br>
											<div class="col-12 d-flex justify-content-center btn-box">
												<div class="text-center mb-2">
													<button type="button" onclick="validate(1);" onmouseover="changeIcon(this, 'assets/images/icons/right-white.svg')"
														onmouseout="changeIcon(this, 'assets/images/icons/right.svg')" class="btn slim-next">Next <img src="assets/images/icons/right.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
												</div>
											</div>
											<div class="col-12">
												<div class="text-center mb-3">
													<p class="mb-0 already-text">Already have an account?</p>
												</div>

												<div class="text-center ">
													<p class="mb-0 already-text"><a href="index.php"><u>Sign in</u></a></p>
												</div>
											</div>
										</div>
										<div id="step2" style="display:none;">
											<div class="col-12 mb-4">
												<div class="d-flex align-items-center gap-2">
													<div class="numberCircle">
														<span>2</span>
													</div>
													<span class="stepDescription">Create your password.</span>
												</div>
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label text-white main-label">Password<sup>*</sup></label>
												<br>
												<p class="mb-2 hint-text">
													Must contain 8 characters including a capital letter, a lowercase letter, and a number.</i>
												</p>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0 border-white" id="inputChoosePassword" name="inputChoosePassword" required value="<?php echo $password; ?>" placeholder=" Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent border-white"><i class='bx bx-hide'></i></a>
												</div>
											</div>
											<br>
											<div class="col-12">
												<label for="inputChooseConfirmPassword" class="form-label text-white main-label">Confirm password<sup>*</sup></label>
												<div class="input-group" id="show_hide_confirm_password">
													<input type="password" class="form-control border-end-0 border-white" id="inputChooseConfirmPassword" required placeholder="Enter Confirm Password" value="<?php echo $password; ?>"> <a href="javascript:;" class="input-group-text bg-transparent border-white"><i class='bx bx-hide'></i></a>
												</div>
											</div>
											<div class="col-12">
												<p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
												<p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
											</div>
											</br>
											<div class="row btn-box">
												<div class="text-start mb-2 col-6">
													<button type="button" onclick="back(2);" onmouseover="changeIcon(this, 'assets/images/icons/left-white.svg')"
														onmouseout="changeIcon(this, 'assets/images/icons/left.svg')" class="btn  btn-next-arrow "><img src="assets/images/icons/left.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
												</div>
												<div class="text-end mb-2 col-6">
													<button type="button" onclick="validate(2);" onmouseover="changeIcon(this, 'assets/images/icons/right-white.svg')"
														onmouseout="changeIcon(this, 'assets/images/icons/right.svg')" class="btn  btn-next-arrow"><img src="assets/images/icons/right.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
												</div>
											</div>
											<div class="col-12">
												<div class="text-center mb-3">
													<p class="mb-0 already-text">Already have an account?</p>
												</div>

												<div class="text-center ">
													<p class="mb-0 already-text"><a href="index.php"><u>Sign in</u></a></p>
												</div>
											</div>

										</div>
										<div id="step3" style="display:none;">

											<div class="col-12 mb-4">
												<div class="d-flex align-items-center gap-2">
													<div class="numberCircle">
														<span>3</span>
													</div>
													<span class="stepDescription">Just a few more details...</span>
												</div>
											</div>
											<div class="col-12">
												<label for="inputProductDescription" class="form-label text-white main-label">Upload a picture of yourself (jpg or png under 8MB)</label>
												</br>
												<input id="profile_pic" class="form-control border-white" name="profile_pic" type="file" accept=".jpg, .jpeg, .png" required name="" onchange="previewImage(this);" value="<?php echo $image; ?>">
												<div class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
													<img id="previewProfile" src="" alt="Profile Preview">
													<span id="fileName" class="text-white"></span>
												</div>
											</div>
											<br>
											<div class="col-12">
												<label for="inputCollection" class="form-label text-white main-label">Who can view your timeline?</label>
												<select class="form-select border-white" id="VIEW_RIGHT" name="VIEW_RIGHT_timeline">
													<option>Select Option</option>
													<option value="1098" <?php if ($profileviewability == "1098"): ?> selected <?php endif ?>>All Connections</option>
													<option value="1099" <?php if ($profileviewability == "1099"): ?> selected <?php endif ?>>Anyone (Public)</option>
													<option value="1101" <?php if ($profileviewability == "1101"): ?> selected <?php endif ?> selected>Only Family (Default)</option>
													<option value="1140" <?php if ($profileviewability == "1140"): ?> selected <?php endif ?>>Only me</option>
												</select>
											</div>
											<br>
											<div class="col-12">
												<label for="inputCollection" class="form-label text-white main-label">Who can view your family tree?</label>
												<select class="form-select border-white" id="VIEW_RIGHT" name="VIEW_RIGHT_tree">
													<option>Select Option</option>
													<option value="1098" <?php if ($timelineviewability == "1098"): ?> selected <?php endif ?>>All Connections</option>
													<option value="1099" <?php if ($timelineviewability == "1099"): ?> selected <?php endif ?>>Anyone (Public)</option>
													<option value="1101" <?php if ($timelineviewability == "1101"): ?> selected <?php endif ?> selected>Only Family (Default)</option>
													<option value="1140" <?php if ($timelineviewability == "1140"): ?> selected <?php endif ?>>Only me</option>
												</select>
											</div>
											</br>
											<div class="row btn-box">
												<div class="text-start mb-2 col-5">
													<button type="button" onclick="back(3);" onmouseover="changeIcon(this, 'assets/images/icons/left-white.svg')"
														onmouseout="changeIcon(this, 'assets/images/icons/left.svg')" class="btn btn-white btn-next-arrow"><img src="assets/images/icons/left.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
												</div>
												<div class="text-end d-flex justify-content-end mb-2 col-7">
													<button class="btn btn-white btn-next text-end" onclick="disableButton(this)">Create Account</button>
													<input type="submit" id="submitSignUp" hidden>
												</div>
											</div>
											<div class="col-12">
												<div class="text-center mb-3">
													<p class="mb-0 already-text">Already have an account?</p>
												</div>

												<div class="text-center ">
													<p class="mb-0 already-text"><a href="index.php"><u>Sign in</u></a></p>
												</div>
											</div>
										</div>

									</form>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<!--end row-->
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/datetimepicker/js/legacy.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.time.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.date.js"></script>
	<script src="assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js"></script>
	<script src="assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js"></script>
	<!--notification js -->
	<script src="assets/plugins/notifications/js/lobibox.min.js"></script>
	<script src="assets/plugins/notifications/js/notifications.min.js"></script>
	<script src="assets/plugins/notifications/js/notification-custom-script.js"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function() {
			$("#show_hide_password a").on('click', function(event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});

			$("#show_hide_confirm_password a").on('click', function(event) {
				event.preventDefault();
				if ($('#show_hide_confirm_password input').attr("type") == "text") {
					$('#show_hide_confirm_password input').attr('type', 'password');
					$('#show_hide_confirm_password i').addClass("bx-hide");
					$('#show_hide_confirm_password i').removeClass("bx-show");
				} else if ($('#show_hide_confirm_password input').attr("type") == "password") {
					$('#show_hide_confirm_password input').attr('type', 'text');
					$('#show_hide_confirm_password i').removeClass("bx-hide");
					$('#show_hide_confirm_password i').addClass("bx-show");
				}
			});
		});
	</script>

	<script>
		function checkPassword(password) {
			const hasUpperCase = /[A-Z]/.test(password);
			const hasLowerCase = /[a-z]/.test(password);
			const hasNumber = /\d/.test(password);
			const hasMinLength = password.length >= 8;

			let errorMessage = [];

			if (!hasMinLength) {
				errorMessage.push("at least 8 characters");
			}
			if (!hasUpperCase) {
				errorMessage.push("a capital letter");
			}
			if (!hasLowerCase) {
				errorMessage.push("a lowercase letter");
			}
			if (!hasNumber) {
				errorMessage.push("a number");
			}

			return {
				isValid: errorMessage.length === 0,
				message: errorMessage.length > 0 ? "Password must contain " + errorMessage.join(", ") : ""
			};
		}

		function validate(step) {
			var firstname = $('#inputFirstName').val();
			var lastname = $('#inputLastName').val();
			var inputEmailAddress = $('#inputEmailAddress').val();
			var inputBackupEmail = $('#inputBackupEmail').val();
			var password = $('#inputChoosePassword').val();
			var confirmPassword = $('#inputChooseConfirmPassword').val();
			if (step == 1) {
				let errorMessage = [];
				$('#txtError').text('');
				if (firstname == "") {
					errorMessage.push("First name is required");
				}
				if (lastname == "") {
					errorMessage.push("Last name is required");
				}
				if (inputEmailAddress == "") {
					errorMessage.push("Email address is required");
				}
				if (errorMessage.length == 0) {
					$('#step1').hide();
					$('#step2').show();
					$('#step3').hide();
				} else {
					$('#txtError').text(errorMessage);
				}

			} else if (step == 2) {
				$('#passwordError').text('');
				if (password != confirmPassword) {
					$('#passwordError').text('Passwords must match');
				} else {
					const passwordCheck = checkPassword(password);
					if (!passwordCheck.isValid) {
						$('#passwordError').text(passwordCheck.message);
					} else {
						$('#step1').hide();
						$('#step2').hide();
						$('#step3').show();
					}
				}


			} else if (step == 3) {
				// MM: We will do some validations here then call the signup endpoint
				signup();
			}

		}

		function back(step) {
			if (step == 2) {
				$('#step1').show();
				$('#step2').hide();
				$('#step3').hide();
			} else if (step == 3) {
				$('#step1').hide();
				$('#step2').show();
				$('#step3').hide();
			}
		}

		function signup() {
			var viewability = $('#VIEW_RIGHT').val();
			var firstname = $('#inputFirstName').val();
			var lastname = $('#inputLastName').val();
			var email = $('#inputEmailAddress').val();
			var backupemail = $('#inputBackupEmail').val();
			var password = $('#inputChoosePassword').val();

			var form_data = new FormData();

			form_data.append('firstname', firstname);
			form_data.append('lastname', lastname);
			form_data.append('email', email);
			form_data.append('backupemail', backupemail);
			form_data.append('password', password);
			form_data.append('VIEW_RIGHT', viewability);
			form_data.append("fileToUpload", document.getElementById('profile_pic').files[0]);

			$.ajax({
				type: 'POST',
				url: 'signup.php',
				dataType: 'text', // what to expect back from the PHP script
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				success: function(response) {
					console.log(response);
					round_success_notification("Signup successful");
					location.reload();
					// if(response.indexOf('"error"') > 0){
					// 	round_error_notification("Something went wrong, please try again.");
					// }else{
					// 	round_success_notification("Signup successful");
					// 	location.reload();
					// }

				},
				error: function(xhr, status, error) {
					console.error('Error: ' + error);
					round_error_notification(error);
				}
			});
		}
	</script>

	<script>
		$(".switcher-btn").on("click", function() {
				$(".switcher-wrapper").toggleClass("switcher-toggled")
			}), $(".close-switcher").on("click", function() {
				$(".switcher-wrapper").removeClass("switcher-toggled")
			}),


			$('#theme1').click(theme1);
		$('#theme2').click(theme2);
		$('#theme3').click(theme3);
		$('#theme4').click(theme4);
		$('#theme5').click(theme5);
		$('#theme6').click(theme6);
		$('#theme7').click(theme7);
		$('#theme8').click(theme8);
		$('#theme9').click(theme9);
		$('#theme10').click(theme10);
		$('#theme11').click(theme11);
		$('#theme12').click(theme12);
		$('#theme13').click(theme13);
		$('#theme14').click(theme14);
		$('#theme15').click(theme15);

		function theme1() {
			$('body').attr('class', 'bg-theme bg-theme1');
		}

		function theme2() {
			$('body').attr('class', 'bg-theme bg-theme2');
		}

		function theme3() {
			$('body').attr('class', 'bg-theme bg-theme3');
		}

		function theme4() {
			$('body').attr('class', 'bg-theme bg-theme4');
		}

		function theme5() {
			$('body').attr('class', 'bg-theme bg-theme5');
		}

		function theme6() {
			$('body').attr('class', 'bg-theme bg-theme6');
		}

		function theme7() {
			$('body').attr('class', 'bg-theme bg-theme7');
		}

		function theme8() {
			$('body').attr('class', 'bg-theme bg-theme8');
		}

		function theme9() {
			$('body').attr('class', 'bg-theme bg-theme9');
		}

		function theme10() {
			$('body').attr('class', 'bg-theme bg-theme10');
		}

		function theme11() {
			$('body').attr('class', 'bg-theme bg-theme11');
		}

		function theme12() {
			$('body').attr('class', 'bg-theme bg-theme12');
		}

		function theme13() {
			$('body').attr('class', 'bg-theme bg-theme13');
		}

		function theme14() {
			$('body').attr('class', 'bg-theme bg-theme14');
		}

		function theme15() {
			$('body').attr('class', 'bg-theme bg-theme15');
		}
	</script>
	<script>
		$('.datepicker').pickadate({
				selectMonths: true,
				selectYears: true
			}),
			$('.timepicker').pickatime()
	</script>
	<script>
		$(function() {
			$('#date-time').bootstrapMaterialDatePicker({
				format: 'YYYY-MM-DD HH:mm'
			});
			$('#date').bootstrapMaterialDatePicker({
				time: false
			});
			$('#time').bootstrapMaterialDatePicker({
				date: false,
				format: 'HH:mm'
			});
		});
	</script>
	<script>
		function previewImage(input) {
			const preview = document.getElementById('previewProfile');
			const fileNameSpan = document.getElementById('fileName');

			if (input.files && input.files[0]) {
				const reader = new FileReader();
				fileNameSpan.textContent = input.files[0].name;
				reader.onload = function(e) {
					preview.src = e.target.result;
					preview.style.display = 'inline';
				}

				reader.readAsDataURL(input.files[0]);

			} else {
				preview.src = '';
				preview.style.display = 'none';
				fileNameSpan.textContent = '';
			}
		}
	</script>

	<script>
		const token = <?php echo json_encode($_SESSION["token"]); ?>;
		const rememberMe = <?php echo json_encode($_SESSION["rem"]); ?>;
		const username = <?php echo json_encode($_SESSION["username"]); ?>;
		sessionStorage.setItem("token", token);
		sessionStorage.setItem("remember", rememberMe);
		sessionStorage.setItem("username", username);

		function changeIcon(button, newSrc) {
			const img = button.querySelector("img");
			if (img) {
				img.src = newSrc;
			}
		}

		function disableButton(button) {
			button.disabled = true;
			setTimeout(() => {
				button.disabled = false;
			}, 5000);

			document.getElementById('submitSignUp').click();
		}
	</script>



</body>

</html>