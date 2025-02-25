<form class="row g-3 builderForm totallyHide" id='birthday'>
    <div class="">

        <div class="row mt-1 mx-2 my-2">
            <div class="text-start mb-2 col-9">
                <h6 class="fw-normal fs-6">Memory(1 of 3)</h6>
            </div>
            <div class="text-end mb-2 col-3">
                <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
            </div>
        </div>

        <div class="row  mx-2  memtabs">
            <div class="col-4">
                <div class="text-start d-flex align-items-center justify-content-center col-12 memtab memtab-active"
                    onclick="activateTab(this)" tag="birth-remember">
                    <h6 class="fw-normal fs-6 m-0">Memory</h6>
                </div>
            </div>

            <div class="col-4">
                <div class="text-center d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="birth-share">
                    <h6 class="fw-normal fs-6 m-0">Share</h6>
                </div>
            </div>

            <div class="col-4">
                <div class="text-end d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="birth-collab">
                    <h6 class="fw-normal fs-6 m-0">Viewers</h6>
                </div>
            </div>
        </div>
        <div class="col-12 tabgroup">
            <div class="tabsect birth-remember">
                <div class="mem-instruction">
                    <span class="text-white " id="name-label">Add details of your birth story</span>
                </div>
                <div class="col-12 mb-3 mt-2">
                    <span class="mb-2 text-white" id="shortDescription">Give it a title.</span>
                    <input type="text" class="form-control border-white mb-2" id="birthTitle" name="birthTitle"
                        placeholder="Title" value="Birth">
                    <div class="text-end">
                        <span id="charCount" class="text-white">0 of 100</span>
                    </div>
                </div>

                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white" id="shortDescription">Name</span>
                    <input type="text" class="form-control border-white mb-2" id="Name" name="Name"
                        placeholder="Full Name" value="<?php echo $_SESSION["username"] ?>">
                </div>

                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white" id="birth-label">Birthday</span>
                    <input type="date" class="form-control border-white mb-2" id="inputDOB" name="inputDOB"
                        placeholder="Select a date"
                        value="<?php echo isset($_SESSION["dateofbirth"]) ? $_SESSION["dateofbirth"] : ''; ?>">
                    <div class="form-check">
                        <input class="form-check-input border-white day-in-history" type="checkbox">
                        <label class="form-check-label text-white" for="flexSwitchCheckChecked">This Day in History adds
                            facts about current events.</label>
                    </div>
                </div>

                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white">Birthplace</span>

                    <input type="text" class="form-control border-white mb-2" id="birthplace" name="birthplace"
                        placeholder="Pontiac, MI(USA)">
                </div>

                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white">Parent(s)</span>
                    <input type="text" class="form-control border-white mb-2" id="parent1" name="parent1"
                        placeholder="Full name">
                    <input type="text" class="form-control border-white mb-2" id="parent2" name="parent2"
                        placeholder="Full name">
                </div>

                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white">Add a tag to associate this memory with others on your
                        timeline.</span>
                    <input type="text" class="form-control border-white mb-2" id="birthTag" name="birthTag"
                        placeholder="Enter text and press return">
                </div>

                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white" id="shortDescription">Share some details.</span>
                    <textarea class="form-control border-white mb-2 text-white" id="birthDetails" name="birthDetails"
                        placeholder="Tell us about your parent(s). Who else was there?" rows="5"></textarea>
                    <div class="text-end">
                        <span id="birthDetailsCount" class="text-white">0 of 500</span>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white"> Add media to your memory.</span>
                    <div class="col-12">
                        <input id="birthPic" class="form-control border-white" name="birthPic" type="file"
                            accept=".jpg, .jpeg, .png"
                            onchange="previewImage(this, 'previewbirthPic', 'birthfileName');">
                        <div class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                            <img id="previewbirthPic" src="" alt="Profile Preview">
                            <span id="birthfileName" class="text-white"></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 hideOnMobile" style="cursor:pointer;">
                    <div class="text-center text-white"><i class="bi bi-camera-video text-white mx-2"></i>Record a video
                        message.</div>
                </div>
            </div>

            <!-- <div class="tabsect birth-share totallyHide">
                <span class="mb-2 text-white fs-6" id="name-label">Choose the audience for this memory. Who can see it?</span>
                <div class="col-12 mb-3 mt-3">
                    <label class="cbContainer">
                        <input type="radio" name="audience" value="family" checked>
                        <span class="checkmark"></span>
                        <div class="text">
                            <div class="title">Only family (default)</div>
                        </div>
                    </label>

                </div>
                <div class="col-12 mb-3">
                    <label class="cbContainer">
                        <input type="radio" name="audience" value="connections">
                        <span class="checkmark"></span>
                        <div class="text">
                            <div class="title">All connections</div>
                        </div>
                    </label>

                </div>

                <div class="col-12 mb-3">
                    <label class="cbContainer">
                        <input type="radio" name="audience" value="anyone">
                        <span class="checkmark"></span>
                        <div class="text">
                            <div class="title">Anyone (public)</div>
                        </div>
                    </label>

                </div>

                <div class="col-12 mb-3">
                    <label class="cbContainer">
                        <input type="radio" name="audience" value="custom">
                        <span class="checkmark"></span>
                        <div class="text">
                            <div class="title">Custom</div>
                        </div>
                    </label>

                </div>
            </div> -->
            <div class="tabsect birth-collab totallyHide">
                <span class="mb-2 text-white fs-6" id="name-label">Invite existing connections to contribute to this
                    memory.</span>
                <div class="row mt-1 mx-2 my-2 align-items-center">
                    <div class="text-start col-1 d-flex justify-content-center" style="cursor: pointer;">
                        <i class="bi bi-search text-white fs-5"></i>
                    </div>
                    <div class="text-end col-11">
                        <input type="text" class="form-control border-white" id="searchParam" name="searchParam"
                            placeholder="Search">
                    </div>
                    <div class="col-12 mt-3">
                        <div class="collab-name d-flex justify-content-center gap-2 px-3 py-2">
                            <i class="bi bi-person text-white text-start" style="cursor: pointer;"></i>
                            <div class="text-center">Name 1</div>
                            <i class="bi bi-x text-white text-end" style="cursor: pointer;"></i>
                        </div>
                    </div>
                </div>
                <form class="row g-3 w-100">
                    <div class="scrollable-div col-12 w-100 mt-2">
                        <div class="col-12 mb-2 w-100">
                            <div class="spacing-20 d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">Invite a new connection to contribute.</h6>
                            </div>


                            <div class="col-12 spacing-20">
                                <span class="mb-2 text-white">Name? <sup>*</sup></span>
                                <input type="text" class="form-control border-white mb-2" id="birthinputCollabFirstName"
                                    name="birthinputCollabFirstName" placeholder="First name">
                                <input type="text" class="form-control border-white mb-2" id="birthinputCollabLastName"
                                    name="birthinputCollabLastName" placeholder="Last name">
                            </div>

                            <div class="col-12 spacing-20">
                                <span class="mb-2 text-white">Email <sup>*</sup></span>
                                <input type="email" class="form-control border-white mb-2" id="birthinputCollabEmail"
                                    name="inputEmail" placeholder="name@email.com">
                            </div>

                            <div class="col-12 spacing-20">
                                <span class="mb-2 text-white">Relationship <sup>*</sup></span>
                                <select class="form-select border-white mb-2 text-white bg-dark"
                                    id="birthinputCollabRelation" name="birthinputCollabRelation">
                                    <option value="" disabled selected>Choose a relationship</option>
                                    <option selected value="friend">Friend</option>
                                    <option value="sibling">Sibling</option>
                                    <option value="partner">Partner</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <span class="mb-2 text-white" id="shortDescription">Share some details.</span>
                                <textarea class="form-control border-white mb-2 text-white" id="birthCollabDetails"
                                    name="birthCollabDetails" placeholder="Optional" rows="3"></textarea>
                                <div class="text-end">
                                    <span id="birthCollabDetailsCount" class="text-white">0 of 500</span>
                                </div>
                            </div>


                        </div>
                    </div>

                </form>
            </div>


            <div class="col-12">
                <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
                <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
            </div>
        </div>

        <div class="row mt-1 mx-2 my-2 position-relative">
            <div class="text-center mb-2 col-12 d-flex align-items-center justify-content-center">
                <button type="button" class="btn slim-next d-flex align-items-center justify-content-center"
                    onclick="toggleDropdown('birthday')">
                    Save
                    <img src="assets/images/icons/down.svg" alt="Back" style="vertical-align: middle;">
                </button>
                <div id="saveDropdown-birthday" class="dropdown-menu-tm position-absolute bg-white shadow"
                    style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                    <button class="dropdown-item-tm" onclick="saveAndContinue('birthday')">Save & Continue</button>
                    <input type="submit" class="dropdown-item-tm" value="Save & Close">
                </div>
            </div>
        </div>

    </div>
</form>
<?php if (isset($_SESSION['activities']) && in_array(1091, $_SESSION['activities'])): ?>
<form class="row g-3 totallyHide builderForm" id='1091' action="memory_add.php" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="selectedEmails" name="selectedEmails" class="selectedEmails">
    <input type="hidden" id="selectedViewers" name="selectedViewers" class="selectedViewers">
    <input name="memoryCategory" value="1091" hidden>
    <div>
        <div class="row mt-1 mx-2 my-2">
            <div class="text-start mb-2 col-9">
                <h6 class="fw-normal fs-6">Memory(2 of 3)</h6>
            </div>
            <div class="text-end mb-2 col-3">
                <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
            </div>
        </div>
        <div class="row  mx-2  memtabs">
            <div class="col-4">
                <div class="text-start d-flex align-items-center justify-content-center col-12 memtab memtab-active"
                    onclick="activateTab(this)" tag="graduation-remember">
                    <h6 class="fw-normal fs-6 m-0">Memory</h6>
                </div>
            </div>

            <div class="col-4">
                <div class="text-center d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="graduation-share">
                    <h6 class="fw-normal fs-6 m-0">Share</h6>
                </div>
            </div>

            <div class="col-4">
                <div class="text-end d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="graduation-collab">
                    <h6 class="fw-normal fs-6 m-0">Viewers</h6>
                </div>
            </div>
        </div>
        <div class="col-12 mb-2 tabgroup">
            <div class="tabsect graduation-remember ">
                <div class="mem-instruction">
                    <span class="mb-2 text-white " id="name-label">Share your graduation story.</span>
                </div>

                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white" id="shortDescription">Give it a title.</span>
                    <input type="text" class="form-control border-white mb-2" id="graduationTitle" name="storyTitle"
                        placeholder="Title" value="Graduation">
                    <div class="text-end">
                        <span id="graduationTitleCount" class="text-white">0 of 100</span>
                    </div>
                </div>
                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white">What kind of graduation was it?</span>
                    <select class="form-select border-white mb-2 text-white bg-dark" id="inputGraduationKind"
                        name="kindofgrad">
                        <option value="" disabled selected>Select one</option>
                        <option value="sibhighschoolling">High School</option>
                        <option value="college">College/ University</option>
                    </select>
                </div>

                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white" id="birth-label">When did it take place?</span>
                    <input type="date" class="form-control border-white mb-2" id="inputGraduationDate"
                        name="inputstoryDate" placeholder="Select a date">
                    <div class="form-check ">
                        <input class="form-check-input border-white day-in-history" type="checkbox"
                            name="enableDayInHistory" id="graduationSwitchs">
                        <label class="form-check-label text-white" for="graduationSwitchs">This Day in History adds
                            facts about current events.</label>
                    </div>
                </div>

                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white" id="shortDescription">What was the name of the school?</span>
                    <input type="text" class="form-control border-white mb-2" id="schoolName" name="nameofschool"
                        placeholder="School Name">
                </div>

                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white" id="shortDescription">Where was it located?</span>
                    <input type="text" class="form-control border-white mb-2" id="schoolLocation" name="memoryLocation"
                        placeholder="School Location">
                </div>


                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white">Add a tag to associate this memory with others on your
                        timeline.</span>
                    <input type="text" class="form-control border-white mb-2" id="graduationTag" name="storyTag"
                        placeholder="Enter text and press return">
                </div>

                <div class="col-12 spacing-20">
                    <span class="mb-2 text-white" id="shortDescription">Share some details.</span>
                    <textarea class="form-control border-white mb-2 text-white" id="graduationDetails"
                        name="storyDetails"
                        placeholder="Where was the ceremony held? Who were your best friends at the time? Did you have any favorite sports or other extra-curricular activities?"
                        rows="5"></textarea>
                    <div class="text-end">
                        <span id="graduationDetailsCount" class="text-white">0 of 500</span>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white"> Add media to your memory.</span>
                    <div class="col-12">
                        <input id="graduationPic" class="form-control border-white" type="file"
                            accept=".jpg, .jpeg, .png" name="storyPic"
                            onchange="previewImage(this, 'previewgraduationPic', 'graduationfileName');">
                        <div class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                            <img id="previewgraduationPic" src="" alt="Profile Preview">
                            <span id="graduationfileName" class="text-white"></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 hideOnMobile" style="cursor:pointer;">
                    <div class="text-center text-white"><i class="bi bi-camera-video text-white mx-2"></i>Record a video
                        message.</div>
                </div>
            </div>

            <div class="tabsect graduation-collabs totallyHide">
                <span class="mb-2 text-white fs-6" id="name-label">Invite existing connections to contribute to this
                    memory.</span>
                <div class="row mt-1 mx-2 my-2 align-items-center">
                    <div class="text-start col-1 d-flex justify-content-center" style="cursor: pointer;">
                        <i class="bi bi-search text-white fs-5"></i>
                    </div>
                    <div class="text-end col-11">
                        <input type="text" class="form-control border-white" id="searchParam" name="searchParam"
                            placeholder="Search">
                    </div>
                    <div class="col-12 mt-3">
                        <div class="collab-name d-flex justify-content-center gap-2 px-3 py-2">
                            <i class="bi bi-person text-white text-start" style="cursor: pointer;"></i>
                            <div class="text-center">Name 1</div>
                            <i class="bi bi-x text-white text-end" style="cursor: pointer;"></i>
                        </div>
                    </div>
                </div>
                <form class="row g-3 w-100">
                    <div class="scrollable-div col-12 w-100 mt-2">
                        <div class="col-12 mb-2 w-100">
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">Invite a new connection to contribute.</h6>
                            </div>


                            <div class="col-12 mb-2">
                                <span class="mb-2 text-white">Name? <sup>*</sup></span>
                                <input type="text" class="form-control border-white mb-2"
                                    id="graduationinputCollabFirstName" name="graduationinputCollabFirstName"
                                    placeholder="First name">
                                <input type="text" class="form-control border-white mb-2"
                                    id="graduationinputCollabLastName" name="graduationinputCollabLastName"
                                    placeholder="Last name">
                            </div>

                            <div class="col-12 mb-2">
                                <span class="mb-2 text-white"><i>Email <sup>*</sup></i></span>
                                <input type="email" class="form-control border-white mb-2"
                                    id="graduationinputCollabEmail" name="inputEmail" placeholder="name@email.com">
                            </div>

                            <div class="col-12">
                                <span class="mb-2 text-white"><i>Relationship <sup>*</sup></i></span>
                                <select class="form-select border-white mb-2 text-white bg-dark"
                                    id="graduationinputCollabRelation" name="graduationinputCollabRelation">
                                    <option value="" disabled selected>Choose a relationship</option>
                                    <option selected value="friend">Friend</option>
                                    <option value="sibling">Sibling</option>
                                    <option value="partner">Partner</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <span class="mb-2 text-white" id="shortDescription">Share some details.</span>
                                <textarea class="form-control border-white mb-2 text-white" id="graduationCollabDetails"
                                    name="graduationCollabDetails" placeholder="Optional" rows="3"></textarea>
                                <div class="text-end">
                                    <span id="graduationCollabDetailsCount" class="text-white">0 of 500</span>
                                </div>
                            </div>


                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="col-12">
            <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
            <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
        </div>

        <div class="row mt-1 mx-2 my-2 position-relative">

            <div class="text-center mb-2 col-12 d-flex align-items-center justify-content-center">
                <button type="button" class="btn slim-next d-flex align-items-center justify-content-center"
                    onclick="toggleDropdown('1091')">
                    Save
                    <img src="assets/images/icons/down.svg" alt="Back" style="vertical-align: middle;">
                </button>
                <div id="saveDropdown-1091" class="dropdown-menu-tm position-absolute bg-white shadow"
                    style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                    <!-- <button class="dropdown-item-tm" onclick="saveAndContinue('1091')">Save & Continue</button> -->
                    <input type="submit" class="dropdown-item-tm" value="Save & Continue" onclick="saveAndClose()">
                    <input type="submit" class="dropdown-item-tm" value="Save & Close">
                </div>
            </div>
        </div>

    </div>
</form>
<?php endif; ?>

<?php if (isset($_SESSION['activities']) && in_array(1131, $_SESSION['activities'])): ?>
<form class="row g-3 totallyHide builderForm" id='1131' action="memory_add.php" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="selectedEmails" name="selectedEmails" class="selectedEmails">
    <input type="hidden" id="selectedViewers" name="selectedViewers" class="selectedViewers">
    <input name="memoryCategory" value="1131" hidden>
    <div>
        <div class="row mt-1 mx-2 my-2">
            <div class="text-start mb-2 col-9">
                <h6 class="fw-normal fs-6">Memory</h6>
            </div>
            <div class="text-end mb-2 col-3">
                <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
            </div>
        </div>
        <div class="row mt-1 mx-2 my-2 memtabs">
            <div class="col-4">
                <div class="text-start d-flex align-items-center justify-content-center col-12 memtab memtab-active"
                    onclick="activateTab(this)" tag="parent-remember">
                    <h6 class="fw-normal fs-6 m-0">Remember</h6>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="parent-share">
                    <h6 class="fw-normal fs-6 m-0">Share</h6>
                </div>
            </div>
            <div class="col-4">
                <div class="text-end d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="parent-collab">
                    <h6 class="fw-normal fs-6 m-0">Collaborate</h6>
                </div>
            </div>
        </div>
        <div class="col-12 mb-2 tabgroup">
            <div class=" tabsect parent-remember">

                <span class="mb-2 text-white " id="name-label">Share your journey to becoming a parent</span>
                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">Give it a title.</span>
                    <input type="text" class="form-control border-white mb-2" id="parentTitle" name="storyTitle"
                        placeholder="Title" value="Parenthood">
                    <div class="text-end">
                        <span id="parentTitleCount" class="text-white">0 of 100</span>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">What was the child's name?</span>
                    <input type="text" class="form-control border-white mb-2" id="parentName" name="partnersname"
                        placeholder="Enter name">
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="birth-label">When did the child become part of your family?</span>
                    <input type="date" class="form-control border-white mb-2" id="inputparentDate" name="inputstoryDate"
                        placeholder="Select a date">
                    <div class="form-check ">
                        <input class="form-check-input border-white day-in-history" type="checkbox"
                            name="enableDayInHistory" id="graduationSwitchs">
                        <label class="form-check-label text-white" for="graduationSwitchs">This Day in History adds
                            facts about current events.</label>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white">Add a tag to associate this memory with others on your
                        timeline.</span>
                    <input type="text" class="form-control border-white mb-2" id="parentTag" name="storyTag"
                        placeholder="Enter text and press return">
                </div>
                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="birth-label">Where were you living at the time?</span>
                    <input type="text" class="form-control border-white mb-2" id="inputparentDate" name="memoryLocation"
                        placeholder="Enter location">
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">Share some details.</span>
                    <textarea class="form-control border-white mb-2 text-white" id="parentDetails" name="storyDetails"
                        placeholder="How did you feel about becoming a parent? Describe your child’s arrival—was it by birth, foster care, or adoption?  What special memories did you have from that time?"
                        rows="5"></textarea>
                    <div class="text-end">
                        <span id="parentDetailsCount" class="text-white">0 of 500</span>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white"> Add media to your memory.</span>
                    <div class="col-12">
                        <input id="parentPic" class="form-control border-white" name="storyPic" type="file"
                            accept=".jpg, .jpeg, .png"
                            onchange="previewImage(this, 'previewparentPic', 'parentfileName');">
                        <div class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                            <img id="previewparentPic" src="" alt="Profile Preview">
                            <span id="parentfileName" class="text-white"></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 hideOnMobile" style="cursor:pointer;">
                    <div class="text-center text-white"><i class="bi bi-camera-video text-white mx-2"></i>Record a video
                        message.</div>
                </div>
                <!-- paste below -->
            </div>

        </div>

        <div class="col-12">
            <p class="mb-0 text-danger" id="passwordError">
                <?php echo $error; ?>
            </p>
            <p class="mb-0 text-success" id="txtMessage">
                <?php echo $message; ?>
            </p>
        </div>

        <div class="row mt-1 mx-2 my-2 position-relative">
            <div class="text-center mb-2 col-12">
                <button type="button" class="btn slim-next" onclick="toggleDropdown('1131')">
                    Save
                    <i class="bi bi-caret-down"></i>
                </button>
                <div id="saveDropdown-1131" class=" dropdown-menu-tm position-absolute bg-white shadow"
                    style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                    <input type="submit" class="dropdown-item-tm" value="Save & Continue" onclick="saveAndClose()">
                    <input type="submit" class="dropdown-item-tm" value="Save & Close">
                </div>
            </div>
        </div>

    </div>
</form>
<?php endif; ?>
<!-- here and the one below as well -->
<?php if (isset($_SESSION['activities']) && in_array(1132, $_SESSION['activities'])): ?>
<form class="row g-3 totallyHide builderForm" id='1132' action="memory_add.php" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="selectedEmails" name="selectedEmails" class="selectedEmails">
    <input type="hidden" id="selectedViewers" name="selectedViewers" class="selectedViewers">
    <input name="memoryCategory" value="1132" hidden>
    <div>
        <div class="row mt-1 mx-2 my-2">
            <div class="text-start mb-2 col-9">
                <h6 class="fw-normal fs-6">Memory</h6>
            </div>
            <div class="text-end mb-2 col-3">
                <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
            </div>
        </div>
        <div class="row mt-1 mx-2 my-2 memtabs">
            <div class="col-4">
                <div class="text-start d-flex align-items-center justify-content-center col-12 memtab memtab-active"
                    onclick="activateTab(this)" tag="pet-remember">
                    <h6 class="fw-normal fs-6 m-0">Remember</h6>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="pet-share">
                    <h6 class="fw-normal fs-6 m-0">Share</h6>
                </div>
            </div>
            <div class="col-4">
                <div class="text-end d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="pet-collab">
                    <h6 class="fw-normal fs-6 m-0">Collaborate</h6>
                </div>
            </div>
        </div>
        <div class="col-12 mb-2 tabgroup">

            <div class="pet-remember tabsect">
                <span class="mb-2 text-white " id="name-label">Share the story of adopting your special pet.</span>
                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">Give it a title.</span>
                    <input type="text" class="form-control border-white mb-2" id="petTitle" name="storyTitle"
                        placeholder="Title" value="Pet">
                    <div class="text-end">
                        <span id="petTitleCount" class="text-white">0 of 100</span>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">What was the pet's?</span>
                    <input type="text" class="form-control border-white mb-2" id="petName" name="partnersname"
                        placeholder="Enter name">
                </div>


                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="birth-label">When did the pet become part of your family?</span>
                    <input type="date" class="form-control border-white mb-2" id="inputpetDate" name="inputstoryDate"
                        placeholder="Select a date">
                    <div class="form-check ">
                        <input class="form-check-input border-white day-in-history" type="checkbox"
                            name="enableDayInHistory" id="graduationSwitchs">
                        <label class="form-check-label text-white" for="graduationSwitchs">This Day in History adds
                            facts about current events.</label>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white">Add a tag to associate this memory with others on your
                        timeline.</span>
                    <input type="text" class="form-control border-white mb-2" id="petTag" name="storyTag"
                        placeholder="Enter text and press return">
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">Share some details.</span>
                    <textarea class="form-control border-white mb-2 text-white" id="petDetails" name="storyDetails"
                        placeholder="How did you meet this pet? What attracted you to it? Do you remember any funny stories about it?"
                        rows="5"></textarea>
                    <div class="text-end">
                        <span id="petDetailsCount" class="text-white">0 of 500</span>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white"> Add media to your memory.</span>
                    <div class="col-12">
                        <input id="petPic" class="form-control border-white" name="storyPic" type="file"
                            accept=".jpg, .jpeg, .png" onchange="previewImage(this, 'previewpetPic', 'petfileName');">
                        <div class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                            <img id="previewpetPic" src="" alt="Profile Preview">
                            <span id="petfileName" class="text-white"></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 hideOnMobile" style="cursor:pointer;">
                    <div class="text-center text-white"><i class="bi bi-camera-video text-white mx-2"></i>Record a video
                        message.</div>
                </div>
                <!-- paste below div -->
            </div>
        </div>

        <div class="col-12">
            <p class="mb-0 text-danger" id="passwordError">
                <?php echo $error; ?>
            </p>
            <p class="mb-0 text-success" id="txtMessage">
                <?php echo $message; ?>
            </p>
        </div>

        <div class="row mt-1 mx-2 my-2 position-relative">
            <div class="text-center mb-2 col-12 d-flex align-items-center justify-content-center">
                <button type="button" class="btn slim-next" onclick="toggleDropdown('pet')">
                    Save
                    <i class="bi bi-caret-down"></i>
                </button>
                <div id="saveDropdown-pet" class=" dropdown-menu-tm position-absolute bg-white shadow"
                    style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                    <input type="submit" class="dropdown-item-tm" value="Save & Continue" onclick="saveAndClose()">
                    <input type="submit" class="dropdown-item-tm" value="Save & Close">
                </div>
            </div>
        </div>

    </div>
</form>
<?php endif; ?>
<!-- fix it -->
<?php if (isset($_SESSION['activities']) && in_array(1133, $_SESSION['activities'])): ?>
<form class="row g-3 totallyHide builderForm" id='1133' action="memory_add.php" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="selectedEmails" name="selectedEmails" class="selectedEmails">
    <input type="hidden" id="selectedViewers" name="selectedViewers" class="selectedViewers">
    <input name="memoryCategory" value="1133" hidden>
    <div>
        <div class="row mt-1 mx-2 my-2">
            <div class="text-start mb-2 col-9">
                <h6 class="fw-normal fs-6">Memory</h6>
            </div>
            <div class="text-end mb-2 col-3">
                <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
            </div>
        </div>
        <div class="row mt-1 mx-2 my-2 memtabs">
            <div class="col-4">
                <div class="text-start d-flex align-items-center justify-content-center col-12 memtab memtab-active"
                    onclick="activateTab(this)" tag="faith-remember">
                    <h6 class="fw-normal fs-6 m-0">Remember</h6>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="faith-share">
                    <h6 class="fw-normal fs-6 m-0">Share</h6>
                </div>
            </div>
            <div class="col-4">
                <div class="text-end d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="faith-collab">
                    <h6 class="fw-normal fs-6 m-0">Collaborate</h6>
                </div>
            </div>
        </div>
        <div class="col-12 mb-2 tabgroup">
            <div class="faith-remember tabsect">

                <span class="mb-2 text-white " id="name-label">Share the story of reaching a faith milestone.</span>
                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">Give it a title.</span>
                    <input type="text" class="form-control border-white mb-2" id="faithTitle" name="storyTitle"
                        placeholder="Title" value="Faith">
                    <div class="text-end">
                        <span id="faithTitleCount" class="text-white">0 of 100</span>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">What was the milestone?</span>
                    <input type="text" class="form-control border-white mb-2" id="faithName" name="faithName"
                        placeholder="Enter name">
                </div>


                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="birth-label">When did it take place?</span>
                    <input type="date" class="form-control border-white mb-2" id="inputfaithDate" name="inputstoryDate"
                        placeholder="Select a date">
                    <div class="form-check ">
                        <input class="form-check-input border-white day-in-history" type="checkbox"
                            name="enableDayInHistory" id="graduationSwitchs">
                        <label class="form-check-label text-white" for="graduationSwitchs">This Day in History adds
                            facts about current events.</label>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white">Add a tag to associate this memory with others on your
                        timeline.</span>
                    <input type="text" class="form-control border-white mb-2" id="faithTag" name="storyTag"
                        placeholder="Enter text and press return">
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">Share some details.</span>
                    <textarea class="form-control border-white mb-2 text-white" id="faithDetails" name="storyDetails"
                        placeholder="Describe this milestone and why it is important to you." rows="5"></textarea>
                    <div class="text-end">
                        <span id="faithDetailsCount" class="text-white">0 of 500</span>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white"> Add media to your memory.</span>
                    <div class="col-12">
                        <input id="faithPic" class="form-control border-white" name="storyPic" type="file"
                            accept=".jpg, .jpeg, .png"
                            onchange="previewImage(this, 'previewfaithPic', 'faithfileName');">
                        <div class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                            <img id="previewfaithPic" src="" alt="Profile Preview">
                            <span id="faithfileName" class="text-white"></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 hideOnMobile" style="cursor:pointer;">
                    <div class="text-center text-white"><i class="bi bi-camera-video text-white mx-2"></i>Record a video
                        message.</div>
                </div>
                <!-- paste below this div -->
            </div>

        </div>

        <div class="col-12">
            <p class="mb-0 text-danger" id="passwordError">
                <?php echo $error; ?>
            </p>
            <p class="mb-0 text-success" id="txtMessage">
                <?php echo $message; ?>
            </p>
        </div>

        <div class="row mt-1 mx-2 my-2 position-relative">
            <div class="text-center mb-2 col-12 d-flex align-items-center justify-content-center">
                <button type="button" class="btn slim-next" onclick="toggleDropdown('1133')">
                    Save
                    <i class="bi bi-caret-down"></i>
                </button>
                <div id="saveDropdown-1133" class=" dropdown-menu-tm position-absolute bg-white shadow"
                    style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                    <input type="submit" class="dropdown-item-tm" value="Save & Continue" onclick="saveAndClose()">
                    <input type="submit" class="dropdown-item-tm" value="Save & Close">
                </div>
            </div>
        </div>

    </div>
</form>
<?php endif; ?>

<form class="row g-3 totallyHide builderForm" id='story' action="memory_add.php" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="selectedEmails" name="selectedEmails" class="selectedEmails">
    <input type="hidden" id="selectedViewers" name="selectedViewers" class="selectedViewers">
    <input name="memoryCategory" value="1090" hidden>
    <div>
        <div class="row mt-1 mx-2 my-2">
            <div class="text-start mb-2 col-9">
                <h6 class="fw-normal fs-6">Memory</h6>
            </div>
            <div class="text-end mb-2 col-3">
                <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
            </div>
        </div>
        <div class="row mt-1 mx-2 my-2 memtabs">
            <div class="col-4">
                <div class="text-start d-flex align-items-center justify-content-center col-12 memtab memtab-active"
                    onclick="activateTab(this)" tag="story-remember">
                    <h6 class="fw-normal fs-6 m-0">Remember</h6>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="story-share">
                    <h6 class="fw-normal fs-6 m-0">Share</h6>
                </div>
            </div>
            <div class="col-4">
                <div class="text-end d-flex align-items-center justify-content-center col-12 memtab"
                    onclick="activateTab(this)" tag="story-collab">
                    <h6 class="fw-normal fs-6 m-0">Collaborate</h6>
                </div>
            </div>
        </div>
        <div class="col-12 mb-2 tabgroup">
            <div class="tabsect story-remember">
                <span class="mb-2 text-white " id="name-label">Share a story.</span>
                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">Give it a title.</span>
                    <input type="text" class="form-control border-white mb-2" id="storyTitle" name="storyTitle"
                        placeholder="Title">
                    <div class="text-end">
                        <span id="storyTitleCount" class="text-white">0 of 100</span>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">What was the milestone?</span>
                    <input type="text" class="form-control border-white mb-2" id="storyName" name="storyName"
                        placeholder="Enter name">
                </div>


                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="birth-label">When did it take place?</span>
                    <input type="date" class="form-control border-white mb-2" id="inputstoryDate" name="inputstoryDate"
                        placeholder="Select a date">
                    <div class="form-check form-switc">
                        <input class="form-check-input border-white activity-checkbox" type="checkbox" id="storySwitch"
                            name="enableDayInHistory">
                        <label class=" form-check-label text-white" for="storySwitch">This Day in History adds facts
                            about current
                            events.</label>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white">Add a tag to associate this memory with others on your
                        timeline.</span>
                    <input type="text" class="form-control border-white mb-2" id="storyTag" name="storyTag"
                        placeholder="Enter text and press return">
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescription">Share some details.</span>
                    <textarea class="form-control border-white mb-2 text-white" id="storyDetails" name="storyDetails"
                        placeholder="Describe this milestone and why it is important to you." rows="5"></textarea>
                    <div class="text-end">
                        <span id="storyDetailsCount" class="text-white">0 of 500</span>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="mb-2 text-white"> Add media to your memory.</span>
                    <div class="col-12">
                        <input id="storyPic" class="form-control border-white" name="storyPic" type="file"
                            accept=".jpg, .jpeg, .png"
                            onchange="previewImage(this, 'previewstoryPic', 'storyfileName');">
                        <div class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                            <img id="previewstoryPic" src="" alt="Profile Preview">
                            <span id="storyfileName" class="text-white"></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 hideOnMobile" style="cursor:pointer;">
                    <div class="text-center text-white"><i class="bi bi-camera-video text-white mx-2"></i>Record a video
                        message.</div>
                </div>

            </div>
            <!-- <div class="tabsect story-share totallyHide">
                <span class="mb-2 text-white fs-6" id="name-label">Choose the audience for this memory. Who can see it?</span>
                <div class="col-12 mb-3 mt-3">
                    <label class="cbContainer">
                        <input type="radio" name="story-audience" value="family" checked>
                        <span class="checkmark"></span>
                        <div class="text">
                            <div class="title">Only family (default)</div>
                        </div>
                    </label>

                </div>
                <div class="col-12 mb-3">
                    <label class="cbContainer">
                        <input type="radio" name="story-audience" value="connections">
                        <span class="checkmark"></span>
                        <div class="text">
                            <div class="title">All connections</div>
                        </div>
                    </label>

                </div>

                <div class="col-12 mb-3">
                    <label class="cbContainer">
                        <input type="radio" name="story-audience" value="anyone">
                        <span class="checkmark"></span>
                        <div class="text">
                            <div class="title">Anyone (public)</div>
                        </div>
                    </label>

                </div>

                <div class="col-12 mb-3">
                    <label class="cbContainer">
                        <input type="radio" name="story-audience" value="custom" id="customRadio">
                        <span class="checkmark"></span>
                        <div class="text">
                            <div class="title">Custom</div>
                        </div>
                    </label>

                </div>
                <div class="custom-view totallyHide" id="customSelect">
                    <div class="row mt-1 mx-2 my-2 align-items-center">
                        <div class="text-start col-1 d-flex justify-content-center" style="cursor: pointer;">
                            <i class="bi bi-search text-white fs-5"></i>
                        </div>
                        <div class="text-end col-11">
                            <input type="text" class="form-control border-white" id="searchViewers" name="searchViewers" placeholder="Search" autocomplete="off">

                        </div>
                    </div>
                    <input type="hidden" id="selectedViewers" name="selectedViewers">
                    <div id="viewersx"></div>
                    <div id="viewersNames" class="row"></div>
                </div>
            </div> -->
        </div>

        <div class="col-12">
            <p class="mb-0 text-danger" id="passwordError">
                <?php echo $error; ?>
            </p>
            <p class="mb-0 text-success" id="txtMessage">
                <?php echo $message; ?>
            </p>
        </div>

        <div class="row mt-1 mx-2 my-2 position-relative mem-save">
            <div class="text-center mb-2 col-12 d-flex align-items-center justify-content-center">
                <button type="button" class="btn slim-next" onclick="toggleDropdown('story')">
                    Save
                    <i class="bi bi-caret-down"></i>
                </button>
                <div id="saveDropdown-story" class=" dropdown-menu-tm position-absolute bg-white shadow"
                    style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                    <input type="submit" class="dropdown-item-tm" value="Save & Continue" onclick="saveAndClose()">
                    <input type="submit" class="dropdown-item-tm" value="Save & Close">
                </div>
            </div>
        </div>

    </div>
</form>

<div class="tabsect collabTab totallyHide">
    <span class="mb-2 text-white fs-6" id="name-label">Invite existing connections to contribute to this memory.</span>
    <div class="row mt-1 mx-2 my-2 align-items-center">
        <div class="text-start col-1 d-flex justify-content-center" style="cursor: pointer;">
            <i class="bi bi-search text-white fs-5"></i>
        </div>
        <div class="text-end col-11">
            <input type="text" class="form-control border-white" id="searchParamc" name="searchParam"
                placeholder="Search" autocomplete="off">

        </div>
    </div>
    <div class="">
        <input type="hidden" id="selectedEmails" name="selectedEmails">
        <div id="suggestions"></div>
        <div id="selectedNames" class="row"></div>
    </div>
    <form class="row g-3 w-100">
        <div class="scrollable-div col-12 w-100 mt-2">
            <div class="col-12 mb-2 w-100">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Invite a new connection to contribute.</h6>
                </div>


                <div class="col-12 mb-2">
                    <span class="mb-2 text-white">Name? <sup>*</sup></span>
                    <input type="text" class="form-control border-white mb-2" id="storyinputCollabFirstName"
                        name="storyinputCollabFirstName" placeholder="First name">
                    <input type="text" class="form-control border-white mb-2" id="storyinputCollabLastName"
                        name="storyinputCollabLastName" placeholder="Last name">
                </div>

                <div class="col-12 mb-2">
                    <span class="mb-2 text-white">Email <sup>*</sup></span>
                    <input type="email" class="form-control border-white mb-2" id="storyinputCollabEmail"
                        name="inputEmail" placeholder="name@email.com">
                </div>

                <div class="col-12">
                    <span class="mb-2 text-white">Relationship <sup>*</sup></span>
                    <select class="form-select border-white mb-2 text-white bg-dark" id="storyinputCollabRelation"
                        name="storyinputCollabRelation">
                        <option value="" disabled selected>Choose a relationship</option>
                        <option selected value="friend">Friend</option>
                        <option value="sibling">Sibling</option>
                        <option value="partner">Partner</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <span class="mb-2 text-white" id="shortDescriptions">Share some details.</span>
                    <textarea class="form-control border-white mb-2 text-white" id="storyCollabDetailss"
                        name="storyCollabDetails" placeholder="Optional" rows="3"></textarea>
                    <div class="text-end">
                        <span id="storyCollabDetailsCounts" class="text-white">0 of 500</span>
                    </div>
                </div>


            </div>
        </div>

    </form>
</div>

<div class="tabsect shareTab totallyHide">
    <span class="mb-2 main-label" id="name-label">Choose the audience for this memory. Who can see it?</span>
    <div class="col-12 mb-3 mt-3">
        <label class="cbContainer">
            <input type="radio" name="story-audience" value="family" checked>
            <span class="checkmark"></span>
            <div class="text">
                <div class="main-label">Only family (default)</div>
            </div>
        </label>

    </div>
    <div class="col-12 mb-3">
        <label class="cbContainer">
            <input type="radio" name="story-audience" value="connections">
            <span class="checkmark"></span>
            <div class="text">
                <div class="main-label">All connections</div>
            </div>
        </label>

    </div>

    <div class="col-12 mb-3">
        <label class="cbContainer">
            <input type="radio" name="story-audience" value="anyone">
            <span class="checkmark"></span>
            <div class="text">
                <div class="main-label">Anyone (public)</div>
            </div>
        </label>

    </div>

    <div class="col-12 mb-3">
        <label class="cbContainer">
            <input type="radio" name="story-audience" value="custom" id="customRadio">
            <span class="checkmark"></span>
            <div class="text">
                <div class="main-label">Custom</div>
            </div>
        </label>

    </div>
    <div class="custom-view totallyHide" id="customSelect">

        <span class="mb-2 main-label" id="name-label">Select the connections that you want to see this memory.</span>
        <div class="row mt-1 mx-2 my-2 align-items-center">
            <div class="text-start col-1 d-flex justify-content-center" style="cursor: pointer;">
                <i class="bi bi-search text-white fs-5"></i>
            </div>
            <div class="text-end col-11">
                <input type="text" class="form-control border-white" id="searchViewers" name="searchViewers"
                    placeholder="Search" autocomplete="off">

            </div>
        </div>
        <input type="hidden" id="selectedViewers" name="selectedViewers">
        <div id="viewersx"></div>
        <div id="viewersNames" class="row"></div>
    </div>
</div>