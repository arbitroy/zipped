<?php

include 'global.php';
if (!$_SESSION["token"]) {
    header("location: index.php");
}
$email = $countrySelected = $stateSelected = $city = $shortbio = $dob = '';

$response = makeGetAPICall('getProfile', $_SESSION["token"]);

$country = mapOptionSets('COUNTRY');

$user_memories = makeGetAPICall('getMemories', $_SESSION["token"]);
$data = json_decode($user_memories, true);


$memoriesByLifeLayer = [];
if (isset($data['memories']) && is_array($data['memories'])) {
    $categoryId = getMemoryKey($memory["memorycategory"]);
    foreach ($data['memories'] as $memory) {
        $lifelayer = $categoryId; // $memory['lifelayer'];

        if (!isset($memoriesByLifeLayer[$lifelayer])) {
            $memoriesByLifeLayer[$lifelayer] = [];
        }

        $memoriesByLifeLayer[$lifelayer][] = $memory;
    }
}


$user = [
    "name" => $_SESSION["username"],
    "dob" => "01/10/2001",
];

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
    <link rel="stylesheet"
        href="assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css">
    <!-- loader-->
    <!-- <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script> -->
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">


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
                <!-- totallyHide -->
                <div class="main-body totallyHide" id="builderForms">
                    <div class="timeline-form-container">
                        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                            <div class="mb-0 w-100" style="height:100vH !important;">
                                <div class="card-body">
                                    <div class="form-body">
                                        <!-- totallyHide -->

                                        <!-- Begin View mode -->
                                        <?php //include 'components/default_viewmode.php' 
                                        ?>
                                        <?php
                                        // Assuming $memory contains the details for the "Birth" memory
                                        $memory = $memoriesByLifeLayer[1089][0] ?? null;
                                        ?>

                                        <?php include_once 'components/view_modes.php' ?>


                                        <!-- End view mode -->

                                        <?php include 'components/memory_forms.php'; ?>

                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
                <div class="timeline-header">
                    <div class="row mt-1 mx-2 my-2">
                        <div class="text-start mb-2 col-9">
                            <h6 class="fw-normal fs-4"><img src="assets/images/icons/memories.png" alt="Back"
                                    style="height: 1em; vertical-align: middle; margin-right:10px;">Memory Timeline</h6>
                        </div>
                        <div class="text-end mb-2 col-3">
                            <button type="button" onclick="showForm('story');"
                                class="btn btn-white text-black btn-next btn-timeline"><i
                                    class="bi bi-plus text-black"></i>Add </button>
                        </div>
                    </div>
                    <div class="text-center col-12">
                        <span class="text-white"><?php echo $_SESSION["username"]; ?></span>
                    </div>
                    <div class="row mt-1 mx-2 my-2 align-items-center">
                        <div class="text-start col-1 d-flex justify-content-center" style="cursor: pointer;">
                            <i class="bi bi-search text-white fs-5"></i>
                        </div>
                        <div class="text-end col-11">
                            <input type="text" class="form-control border-white" id="searchPara" name="searchPara"
                                placeholder="Search" required>
                        </div>
                    </div>

                    <div class="row mt-1 mx-2 my-2 align-items-center">
                        <div class="text-center col-12 d-flex justify-content-center align-items-center">
                            <span class="text-white">Decade Filter:</span>
                            <div class="dropdown-container">
                                <select class="form-select custom-dropdown">
                                    <option value="0">None</option>
                                    <option value="1">1960</option>
                                    <option value="2">1970</option>
                                    <option value="3">1980</option>
                                    <option value="4">1990</option>
                                    <option value="5">2000</option>
                                    <option value="6">2010</option>
                                    <option value="7">2020</option>
                                </select>
                                <span class="custom-arrow">▼</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="stepinvite" class="totallyHide connection-container-form">
                    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                        <div class="form-body w-100">
                            <form class="row g-3 w-100">
                                <div class="scrollable-div w-100">
                                    <div class="col-12 mb-2 w-100">
                                        <div class="mb-2 d-flex align-items-center justify-content-between">
                                            <h6 class="mb-0">Edit connection</h6>
                                            <div>
                                                <i class="bi bi-send me-2" style="cursor: pointer;"></i>
                                                <i class="bi bi-trash" onclick="saveAndClose()"
                                                    style="cursor: pointer;"></i>
                                            </div>
                                        </div>

                                        <h6 class="mb-0">Joined: 00/00/0000</h6>
                                        <div class="col-12 mb-2">
                                            <span class="mb-2 text-white">Name? <sup>*</sup></span>
                                            <input type="text" class="form-control border-white mb-2"
                                                id="inputGuestFirstName" name="inputGuestFirstName"
                                                placeholder="First name" required>
                                            <input type="text" class="form-control border-white mb-2"
                                                id="inputGuestLastName" name="inputGuestLastName"
                                                placeholder="Last name" required>
                                        </div>

                                        <div class="col-12 mb-2">
                                            <span class="mb-2 text-white"><i>Email <sup>*</sup></i></span>
                                            <input type="email" class="form-control border-white mb-2" id="inputEmail"
                                                name="inputEmail" placeholder="name@email.com" required>
                                        </div>

                                        <div class="col-12">
                                            <span class="mb-2 text-white"><i>Relationship <sup>*</sup></i></span>
                                            <select class="form-select border-white mb-2 text-white bg-dark"
                                                id="inputGuestRelation" name="inputGuestRelation" required>
                                                <option value="" disabled selected>Choose a relationship</option>
                                                <option value="sibling">Sibling</option>
                                                <option value="partner">Partner</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="cbContainer">
                                                <input type="radio" name="role" value="collaborator">
                                                <span class="checkmark"></span>
                                                <div class="text">
                                                    <div class="title">Collaborator</div>
                                                    <div class="description">Note: Collaborators can suggest memories
                                                        for the timeline.</div>
                                                </div>
                                            </label>

                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="cbContainer">
                                                <input type="radio" name="role" value="guestAdmin">
                                                <span class="checkmark"></span>
                                                <div class="text">
                                                    <div class="title">Guest administrator</div>
                                                    <div class="description">Warning: Guest administrators have the
                                                        ability to make changes to any data or setting in this
                                                        application.</div>
                                                </div>
                                            </label>

                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
                                        <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
                                    </div>
                                    <div class="row my-3">
                                        <div class="text-start col-6">
                                            <button type="button" onclick="validate(1);"
                                                class="btn btn-transpaernt text-white btn-next">Cancel</button>
                                        </div>

                                        <div class="text-end  col-6">
                                            <button type="button" onclick="validate(1);"
                                                class="btn btn-white btn-next">Save Changes</button>
                                        </div>
                                    </div>


                                </div>

                            </form>
                        </div>

                    </div>
                </div>

                <div class="timeline-vertical">

                    <?php if (!empty($memoriesByLifeLayer)) {
                        include_once 'components/timeline_component.php';
                    } else {
                        include_once 'components/default_timeline.php';
                    } ?>
                </div>
            </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
    </div>
    <footer class="accent-group page-footer">
        <p class="mb-0">Copyright © <?php echo $year; ?>. All right reserved.</p>
    </footer>
    <!--end wrapper-->
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

    <!-- <script>
        $(document).ready(function() {
            $('#image-uploadify').imageuploadify();
        })
    </script> -->
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
    function back(step) {
        console.log(document.querySelectorAll('[id^="stepdiv"]'));

        document.querySelectorAll('[id^="stepdiv"]').forEach(step => step.classList.add('totallyHide'));


        document.getElementById('settings-options').style.display = 'block';

    }

    function validate(step) {
        document.querySelectorAll('[id^="stepdiv"]').forEach(step => step.classList.add('totallyHide'));


        document.getElementById('settings-options').style.display = 'block';
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
    document.querySelectorAll('.timeline-container').forEach((container) => {
        container.addEventListener('click', () => {

            document.querySelectorAll('.timelie-text-box').forEach((box) => {
                box.classList.remove('timeline-selected');
            });


            const textBox = container.querySelector('.timelie-text-box');
            if (textBox) {
                textBox.classList.add('timeline-selected');
            }
        });
    });


    function setupCharCount(inputId, countId, maxLength) {
        const inputElement = document.getElementById(inputId);
        const countElement = document.getElementById(countId);
        if (inputElement) {
            inputElement.addEventListener('input', function() {

                const currentLength = inputElement.value.length;

                countElement.textContent = `${currentLength} of ${maxLength}`;

                if (currentLength > maxLength) {
                    inputElement.value = inputElement.value.substring(0, maxLength);
                }
            });
        } else {
            console.log("Input element not found with ID: " + inputId);

        }
    }


    setupCharCount('birthTitle', 'charCount', 100);
    setupCharCount('birthDetails', 'birthDetailsCount', 500);
    setupCharCount('graduationTitle', 'graduationTitleCount', 100);
    setupCharCount('graduationDetails', 'graduationDetailsCount', 500);
    setupCharCount('weddingTitle', 'weddingTitleCount', 100);
    setupCharCount('weddingDetails', 'weddingDetailsCount', 500);
    setupCharCount('schoolsTitle', 'schoolsTitleCount', 100);
    setupCharCount('schoolsDetails', 'schoolsDetailsCount', 500);
    setupCharCount('friendshipTitle', 'friendshipTitleCount', 100);
    setupCharCount('friendshipDetails', 'friendshipDetailsCount', 500);
    setupCharCount('homeTitle', 'homeTitleCount', 100);
    setupCharCount('jobDetails', 'jobDetailsCount', 500);
    setupCharCount('jobDetails', 'jobDetailsCount', 500);
    setupCharCount('relationshipDetails', 'relationshipDetailsCount', 500);
    setupCharCount('relationshipDetails', 'relationshipDetailsCount', 500);
    setupCharCount('parentDetails', 'parentDetailsCount', 500);
    setupCharCount('parentDetails', 'parentDetailsCount', 500);
    setupCharCount('petDetails', 'petDetailsCount', 500);
    setupCharCount('petDetails', 'petDetailsCount', 500);
    setupCharCount('faithDetails', 'faithDetailsCount', 500);
    setupCharCount('faithDetails', 'faithDetailsCount', 500);
    setupCharCount('storyDetails', 'storyDetailsCount', 500);
    setupCharCount('storyDetails', 'storyDetailsCount', 500);
    setupCharCount('birthCollabDetails', 'birthCollabDetailsCount', 500);
    setupCharCount('graduationCollabDetails', 'graduationCollabDetailsCount', 500);
    setupCharCount('storyCollabDetailss', 'storyCollabDetailsCounts', 500);
    setupCharCount('storyTitle', 'storyTitleCount', 100);


    function previewImage(input, previewId, fileNameId) {
        const preview = document.getElementById(previewId);
        const fileNameSpan = document.getElementById(fileNameId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            fileNameSpan.textContent = input.files[0].name;

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'inline';
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
            fileNameSpan.textContent = '';
        }
    }

    function toggleDropdown(formId) {
        const dropdown = document.getElementById("saveDropdown-" + formId);
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    function saveAndContinue(formId) {

        const formElement = document.getElementById(formId);
        if (formElement) {
            formElement.classList.add("totallyHide");
        }

        saveAndClose()
    }


    function saveAndClose() {

        const builderForms = document.getElementById("builderForms");
        if (builderForms) {
            builderForms.classList.add("totallyHide");
        }

        closeDropdown();
    }

    function closeDropdown() {
        const dropdowns = document.querySelectorAll("[id^='saveDropdown-']");
        dropdowns.forEach(dropdown => {
            dropdown.style.display = "none";
        });
    }



    function showForm(formId) {
        parts = formId.split('#');
        let currentURL = window.location.origin + window.location.pathname;
        let newURL = currentURL + "?memory=" + parts[1];

        document.querySelectorAll('.tabsect').forEach(element => {
            if ([...element.classList].some(cls => cls.endsWith('-remember-view'))) {
                element.classList.remove('totallyHide');
            }
        });
        document.querySelectorAll('.tabsect').forEach(element => {
            if ([...element.classList].some(cls => cls.endsWith('-history-view'))) {
                element.classList.remove('totallyHide');
            }
        });
        document.querySelectorAll('.tabsect').forEach(element => {
            if ([...element.classList].some(cls => cls.endsWith('-collabs-view'))) {
                element.classList.remove('totallyHide');
            }
        });

        document.querySelectorAll('.tabsect').forEach(element => {
            if ([...element.classList].some(cls => cls.endsWith('-remember'))) {
                element.classList.remove('totallyHide');
            }
        });
        document.querySelectorAll('div[tag$="-remember"]').forEach(element => {
            element.classList.add('memtab-active');
        });
        document.querySelectorAll('div[tag$="-share"]').forEach(element => {
            element.classList.remove('memtab-active');
        });
        document.querySelectorAll('div[tag$="-collab"]').forEach(element => {
            element.classList.remove('memtab-active');
        });

        document.querySelectorAll('div[tag$="-remember-view"]').forEach(element => {
            element.classList.add('memtab-active');
        });
        document.querySelectorAll('div[tag$="-history-view"]').forEach(element => {
            element.classList.remove('memtab-active');
        });
        document.querySelectorAll('div[tag$="-collabs-view"]').forEach(element => {
            element.classList.remove('memtab-active');
        });

        const allBuilderForms = document.querySelectorAll('.builderForm');

        allBuilderForms.forEach(form => {
            form.classList.add('totallyHide');
        });
        const targetForm = document.getElementById(parts[0]);
        if (targetForm) {
            targetForm.classList.remove("totallyHide");
        } else {
            console.error(`Element with ID '${formId}' not found.`);
        }

        const builderForms = document.getElementById("builderForms");
        if (builderForms) {
            builderForms.classList.remove("totallyHide");
        } else {
            console.error("Element with ID 'builderforms' not found.");
        }
        const collabDiv = document.querySelector('.collabTab');
        const shareDiv = document.querySelector('.shareTab');
        collabDiv.classList.add('totallyHide');
        shareDiv.classList.add('totallyHide');


    }

    function activateTab(selectedTab) {
        const collabDiv = document.querySelector('.collabTab');
        const shareDiv = document.querySelector('.shareTab');
        if (collabDiv) {
            collabDiv.classList.remove('totallyHide');
        }
        const tabs = document.querySelectorAll('.memtab');
        tabs.forEach(tab => tab.classList.remove('memtab-active'));
        selectedTab.classList.add('memtab-active');
        const tag = selectedTab.getAttribute('tag');

        const sections = document.querySelectorAll('.tabsect');
        sections.forEach(section => section.classList.add('totallyHide'));

        const targetSection = document.querySelector(`.${tag}`);
        if (targetSection) {
            targetSection.classList.remove('totallyHide');
        }
        console.log(tag);
        const tagParts = tag.split('-');
        console.log(tagParts);

        if (tagParts[1] == 'collab') {
            const collabDiv = document.querySelector('.collabTab');
            if (collabDiv) {
                collabDiv.classList.remove('totallyHide');
            } else {
                console.log("No found");
            }
        }
        if (tagParts[1] == 'share') {
            if (shareDiv) {
                shareDiv.classList.remove('totallyHide');
            }
        }
    }
    </script>
    <script>
    $(document).ready(function() {
        let selectedUsers = {}; // Store selected users (email => name)

        $("#searchParamc").on("input", function() {
            let query = $(this).val().trim();
            let inputWidth = $(this).outerWidth();
            $("#suggestions").css({
                "width": inputWidth + "px",
                "left": $(this).position().left + "px"
            });
            if (query.length > 0) {
                $.get("search.php", {
                    query: query
                }, function(data) {
                    let results = JSON.parse(data);
                    let suggestionBox = $("#suggestions");
                    suggestionBox.empty().show();

                    if (results.length > 0) {
                        results.forEach(user => {
                            suggestionBox.append(
                                `<div class="suggestion" data-email="${user.email}">${user.name}</div>`
                                );
                        });
                    } else {
                        suggestionBox.append('<div class="suggestion">No results found</div>');
                    }
                });
            } else {
                $("#suggestions").hide();
            }
        });

        $(document).on("click", ".suggestion", function() {
            let email = $(this).data("email");
            let name = $(this).text();

            if (!(email in selectedUsers)) {
                selectedUsers[email] = name;
                $("#selectedNames").append(
                    `<div class="col-4 d-flex justify-content-center gap-2 chip-search py-2 px-3" data-email="${email}">
                            <i class="bi bi-person text-white text-start" style="cursor: pointer;"></i>
                            <div class="text-center"> ${name}</div>
                            <i class="bi bi-x text-white text-end remove-chip" style="cursor: pointer;"></i>
                        </div>`
                );
                updateHiddenInput();
            }

            $("#searchParamc").val('');
            $("#suggestions").hide();
        });

        $(document).on("click", ".remove-chip", function() {
            let chip = $(this).closest(".chip-search");
            let email = chip.data("email");

            delete selectedUsers[email];
            chip.remove();
            updateHiddenInput();
        });

        function updateHiddenInput() {
            let emails = Object.keys(selectedUsers);
            $(".selectedEmails").each(function() {
                $(this).val(emails.join(","));
            });
        }


        $(document).click(function(e) {
            if (!$(e.target).closest("#searchParamc, #suggestions").length) {
                $("#suggestions").hide();
            }
        });
    });
    </script>

    <!-- Viewers search -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const customRadio = document.getElementById("customRadio");
        const customSelect = document.getElementById("customSelect");

        document.querySelectorAll('input[name="story-audience"]').forEach((radio) => {
            radio.addEventListener("change", function() {
                if (customRadio.checked) {
                    customSelect.classList.remove("totallyHide");
                } else {
                    customSelect.classList.add("totallyHide");
                }
            });
        });
    });

    $(document).ready(function() {
        let selectedViewers = {};

        $("#searchViewers").on("input", function() {
            let query = $(this).val().trim();
            let inputWidth = $(this).outerWidth();
            $("#viewersx").css({
                "width": inputWidth + "px",
                "left": $(this).position().left + "px"
            });
            if (query.length > 0) {
                $.get("search.php", {
                    query: query
                }, function(data) {
                    let results = JSON.parse(data);
                    let suggestionBox = $("#viewersx");
                    suggestionBox.empty().show();

                    if (results.length > 0) {
                        results.forEach(user => {
                            suggestionBox.append(
                                `<div class="suggestion" data-email="${user.email}">${user.name}</div>`
                                );
                        });
                    } else {
                        suggestionBox.append('<div class="suggestion">No results found</div>');
                    }
                });
            } else {
                $("#viewersx").hide();
            }
        });

        $(document).on("click", ".suggestion", function() {
            let email = $(this).data("email");
            let name = $(this).text();

            if (!(email in selectedViewers)) {
                selectedViewers[email] = name;
                $("#viewersNames").append(
                    `<div class="col-4 d-flex justify-content-center gap-2 chip-search py-2 px-3" data-email="${email}">
                            <i class="bi bi-person text-white text-start" style="cursor: pointer;"></i>
                            <div class="text-center"> ${name}</div>
                            <i class="bi bi-x text-white text-end remove-chip" style="cursor: pointer;"></i>
                        </div>`
                );
                updateHiddenInputs();
            }

            $("#searchViewers").val('');
            $("#viewersx").hide();
        });

        $(document).on("click", ".remove-chip", function() {
            let chip = $(this).closest(".chip-search");
            let email = chip.data("email");

            delete selectedViewers[email];
            chip.remove();
            updateHiddenInputs();
        });

        function updateHiddenInputs() {
            let emails = Object.keys(selectedViewers);
            $(".selectedViewers").each(function() {
                $(this).val(emails.join(","));
            });
        }


        $(document).click(function(e) {
            if (!$(e.target).closest("#searchViewers, #viewersx").length) {
                $("#viewersx").hide();
            }
        });
    });
    </script>

</body>

</html>