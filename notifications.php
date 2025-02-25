<?php

include 'global.php';
if (!$_SESSION["token"]) {
	header("location: index.php");
}
$invites_responses = makeGetAPICall('searchInvites', $_SESSION["token"]);
$invites = json_decode($invites_responses, true);

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
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">


	<title>Dignitrees - Family App</title>
</head>

<body class="bg-theme bg-<?php echo $_SESSION['background']; ?>">
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<?php include 'nav/sidebar.php'; ?>
		<!--end sidebar wrapper -->
		<!--start header -->
		<?php include 'nav/header.php'; ?>
		<!--end header -->
		<!--start page wrapper -->


		<div class="page-wrapper">
			<div class="page-content">

				<div class="main-body">
					<div class="notification-header">
						<div class="row mt-1 mx-2 my-2">
							<div class="text-start mb-2 col-12">
								<h6 class="fw-normal fs-4"><img src="assets/images/icons/notifications.png" alt="Back" style="height: 1em; vertical-align: middle; margin-right:25px;">Notifications</h6>
							</div>
						</div>

						<div class="row mt-1 mx-2 my-2 align-items-center">
							<div class="text-start col-1 d-flex justify-content-center" style="cursor: pointer;">
								<i class="bi bi-search text-white fs-5"></i>
							</div>
							<div class="text-end col-11">
								<input type="text" class="form-control border-white" id="searchParam" name="searchParam" placeholder="Search" required>
							</div>
						</div>

						<div class="row mt-1 mx-2 my-2 align-items-center">
							<div class="text-end col-12 d-flex justify-content-center align-items-center">
								<div class="dropdown-container-2 text-start" id="filterDropdown">
									<span id="selectedFilter">Filter: None ▼</span>
									<div class="dropdown-menu-2 accent-group">
										<div data-value="All">All</div>
										<div data-value="Family">Family</div>
										<div data-value="Friends">Friends</div>
										<div data-value="Other">Other</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<!-- <div class="p-3 notification-card">
						<div class="card-header mb-3">
							<h2 class="fw-normal fs-6"><img src="assets/images/icons/connections.png" alt="Back" style="height: 1em; vertical-align: middle; margin-right:15px;">Connection</h2>
						</div>
						<div class="notification-body">
							<p class="text-white"><b>From: </b> Dan Lewis</p>
							<p class="text-white"><b>Email: </b>dan@gmail.com</p>
							<p class="text-white">Dan accepted your connection request.</p>
						</div>
						<div class="notification-footer row mx-2">
							<div class="col-6">
								<button class="text-start btn btn-transparent txt-hover-white text-white bn-next"> Dismiss</button>
							</div>
							<div class="col-6 text-end">
								<button class="btn btn-white  bn-next" style="width:145px;"> View connection</button>
							</div>
						</div>

					</div> -->

					<?php

					foreach ($invites as $invite) {
						if (!isset($invite['EmailAddress'])) {
							break;
						}

						$fullName = $invite['FullName'] ?? 'Connection';
						$emailAddress = $invite['EmailAddress'] ?? 'N/A';

						echo '
    <div class="p-3 notification-card">
        <div class="card-header mb-3">
            <h2 class="fw-normal fs-6">
                <img src="assets/images/icons/connections.png" alt="Back" style="height: 1em; vertical-align: middle; margin-right:15px;">
                Connection
            </h2>
        </div>
        <div class="notification-body">
            <p class="text-white"><b>From: </b>' . htmlspecialchars($fullName) . '</p>
            <p class="text-white"><b>Email: </b>' . htmlspecialchars($emailAddress) . '</p>
            <p class="text-white">' . htmlspecialchars($fullName) . ' has sent you a connection request.</p>
        </div>
        <div class="notification-footer row mx-2">
            <div class="col-6">
                <button class="text-start btn btn-transparent txt-hover-white text-white bn-next"> Dismiss</button>
            </div>
            <div class="col-6 text-end">
                <button class="btn btn-white  bn-next" style="width:145px;"> View connection</button>
            </div>
        </div>
    </div>';
					}
					?>

					<div class="p-3 notification-card">
						<div class="card-header mb-3">
							<h2 class="fw-normal fs-6"><img src="assets/images/icons/collaboration.png" alt="Back" style="height: 1em; vertical-align: middle; margin-right:15px;">Collaboration</h2>
						</div>
						<div class="notification-body">
							<p class="text-white"><b>From: </b> Doug Roberts</p>
							<p class="text-white"><b>Email: </b>doug@gmail.com</p>
							<p class="text-white"><b>Memory: </b>Camping in Sarnia</p>
						</div>
						<div class="notification-footer row">
							<div class="col-6">
								<button class="text-start btn slim-next-transparent txt-hover-white text-white bn-next"> Dismiss</button>
							</div>
							<div class="col-6 d-flex justify-content-end">
								<button class="btn slim-next" style="width:160px;"> View collaboration</button>
							</div>
						</div>

					</div>

					<div class="p-3 notification-card">
						<div class="card-header mb-3">
							<h2 class="fw-normal fs-6"><img src="assets/images/icons/timeline_alert.png" alt="Back" style="height: 1em; vertical-align: middle; margin-right:15px;">Timeline Alert</h2>
						</div>
						<div class="notification-body">
							<p class="text-white" style="line-height:2 !important;">You have an incomplete memory on your timeline. Please provide details for "Graduation."</p>
						</div>
						<div class="notification-footer row">
							<div class="col-6">
								<button class="text-start btn slim-next-transparent txt-hover-white text-white bn-next"> Dismiss</button>
							</div>
							<div class="col-6 d-flex justify-content-end">
								<button class="btn slim-next"> View Memory</button>
							</div>
						</div>

					</div>




				</div>
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
	</div>
	<footer class="accent-group page-footer">
		<p class="mb-0">Copyright © <?php echo $year; ?>. All right reserved.</p>
	</footer>
	<!--end wrapper-->
	<!--start switcher-->
	<div class="switcher-wrapper">
		<!-- <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
		</div> -->
		<div class="switcher-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-uppercase">Theme Customizer</h5>
				<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
			</div>
			<hr />
			<p class="mb-0">Gaussian Texture</p>
			<hr>

			<ul class="switcher">
				<li id="theme1"></li>
				<li id="theme2"></li>
				<li id="theme3"></li>
				<li id="theme4"></li>
				<li id="theme5"></li>
				<li id="theme6"></li>
			</ul>
			<hr>
			<p class="mb-0">Gradient Background</p>
			<hr>

			<ul class="switcher">
				<li id="theme7"></li>
				<li id="theme8"></li>
				<li id="theme9"></li>
				<li id="theme10"></li>
				<li id="theme11"></li>
				<li id="theme12"></li>
				<li id="theme13"></li>
				<li id="theme14"></li>
				<li id="theme15"></li>
			</ul>
		</div>
	</div>
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/datetimepicker/js/legacy.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.time.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.date.js"></script>
	<script src="assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js"></script>
	<script src="assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js"></script>

	<script>
		// $(document).ready(function() {
		// 	$('#image-uploadify').imageuploadify();
		// })
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
	<!--app JS-->
	<script src="assets/js/app.js"></script>

	<script>
		// function showStep(stepNumber) {
		//     document.querySelectorAll('[id^="stepdiv"]').forEach(step => step.classList.add('totallyHide'));
		//     document.getElementById('settings-options').style.display = 'none';
		//     document.getElementById(`stepdiv${stepNumber}`).classList.remove('totallyHide');
		// }


		// document.querySelectorAll('[id^="stept"]').forEach(trigger => {

		//     trigger.addEventListener('click', function() {
		//         const stepNumber = this.id.match(/\d+/)[0];
		//         showStep(stepNumber);
		//     });
		// });

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

		function back(step) {
			console.log(document.querySelectorAll('[id^="stepdiv"]'));

			document.querySelectorAll('[id^="stepdiv"]').forEach(step => step.classList.add('totallyHide'));


			document.getElementById('settings-options').style.display = 'block';

		}

		function validate(step) {
			document.querySelectorAll('[id^="stepdiv"]').forEach(step => step.classList.add('totallyHide'));


			document.getElementById('settings-options').style.display = 'block';
			// document.getElementById('step1').style.display = 'block';
		}

		document.querySelectorAll('.letter-square').forEach(square => {
			square.addEventListener('click', function() {
				document.querySelectorAll('.letter-square').forEach(sq => sq.classList.remove('selected'));
				this.classList.add('selected');
			});
		});

		document.querySelectorAll('.square-image').forEach((img) => {
			img.addEventListener('click', function() {
				const bgImage = this.src;
				document.body.style.backgroundImage = `url(${bgImage})`;
			});
		});
	</script>

	<script>
		const btnActive = document.getElementById('btn-active');
		const btnPending = document.getElementById('btn-pending');


		function toggleButtonStyles() {
			if (this.id === 'btn-active') {
				btnActive.classList.add('btn-white', 'text-black');
				btnActive.classList.remove('btn-transparent', 'text-white');
				btnPending.classList.add('btn-transparent', 'text-white');
				btnPending.classList.remove('btn-white', 'text-black');
			} else if (this.id === 'btn-pending') {
				btnPending.classList.add('btn-white', 'text-black');
				btnPending.classList.remove('btn-transparent', 'text-white');
				btnActive.classList.add('btn-transparent', 'text-white');
				btnActive.classList.remove('btn-white', 'text-black');
			}
		}

		btnActive.addEventListener('click', toggleButtonStyles);
		btnPending.addEventListener('click', toggleButtonStyles);

		function showStepInvite() {
			document.getElementById("stepinvite").classList.remove("totallyHide");
			for (let i = 1; i <= 10; i++) {
				const connectElement = document.getElementById(`connect${i}`);
				if (connectElement) {
					console.log(connectElement);
					connectElement.classList.add("totallyHide");
				}
			}
		}

		function validate(step) {
			document.getElementById("stepinvite").classList.add("totallyHide");
			for (let i = 1; i <= 10; i++) {
				const connectElement = document.getElementById(`connect${i}`);
				if (connectElement) {
					connectElement.classList.remove("totallyHide");
				}
			}
		}
	</script>
	<script>
		$(document).ready(function() {
			$(".dropdown-container-2").click(function(e) {
				console.log("toggling");
				e.stopPropagation();

				$(this).find(".dropdown-menu-2").toggle();
			});

			$(".dropdown-menu-2 div").click(function() {
				let selectedValue = $(this).data("value");
				let parentDropdown = $(this).closest(".dropdown-container-2");

				if (parentDropdown.attr("id") === "filterDropdown") {
					$("#selectedFilter").text(`Filter: ${selectedValue} ▼`);
				}
			});

		});
	</script>

</body>

</html>