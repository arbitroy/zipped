<?php
function renderTabs($categoryId)
{
    return '
    <div class="row mt-1 mx-2 my-2 memtabs">
        <div class="col-4">
            <div class="text-start d-flex align-items-center justify-content-center col-12 memtab memtab-active" onclick="activateTab(this)" tag="t' . $categoryId . '-remember-view">
                <h6 class="fw-normal fs-6 m-0">Memory</h6>
            </div>
        </div>
        <div class="col-4">
            <div class="text-center d-flex align-items-center justify-content-center col-12 memtab" onclick="activateTab(this)" tag="t' . $categoryId . '-history-view">
                <h6 class="fw-normal fs-6 m-0">History</h6>
            </div>
        </div>
        <div class="col-4">
            <div class="text-end d-flex align-items-center justify-content-center col-12 memtab" onclick="activateTab(this)" tag="t' . $categoryId . '-collabs-view">
                <h6 class="fw-normal fs-6 m-0">Viewers</h6>
            </div>
        </div>
    </div>';
}
// 1089 => "Started School", // should be 1089
// 1090 => "Made a friend", // should be 1090
// 1091 => "Graduated",
// 1092 => "Moved to a new home",
// 1093 => "Began first job",
// 1094 => "Fell in love",
// 1095 => "Got Married",
// 1131 => "Became a parent",
// 1132 => "Adopted a special pet",
// 1133 => "Reached a faith milestone"
function renderTabContent($memory, $categoryId, $image)
{
    $date = htmlspecialchars($memory["datefrom"] ?? "Unknown Date");
    $location = htmlspecialchars($memory["memorylocation"] ?? "Unknown Location");
    $memoryText = htmlspecialchars($memory["memorytext"] ?? "No memory details available.");
    $viewability = htmlspecialchars($memory["viewability"] ?? "No contributions available.");
    $school = $categoryId == 1089 ? "<b>School: </b>" . htmlspecialchars($memory['nameofschool']) . "<br/>" : "";
    $partnersname = $categoryId == 1132 ? "<b>Pet's Name: </b>" . htmlspecialchars($memory['partnersname']) . "<br/>" : "";
    $partnersname = $categoryId == 1090 ? "<b>Friend's Name: </b>" . htmlspecialchars($memory['partnersname']) . "<br/>" : "";
    $partnersname = $categoryId == 1095 ? "<b>Wedding to: </b>" . htmlspecialchars($memory['partnersname']) . "<br/>" : "";
    $kindofgrad = $categoryId == 1091 ? "</b>" . htmlspecialchars($memory['kindofgrad']) . " graduation<br/>" : "";
    $combinedArray = array_merge($memory['collabmembers'], $memory['taggedmembers']);
    $collabMembersUris = $memory['collabmembers'] ?? [];
    $collabMember = '';
    $connections = $_SESSION['connections'] ?? [];
    foreach ($connections as $connection) {
        $name = trim($connection['PersonName']);
        $userId = trim($connection['userId']);

        // Check if the userId is in the viewability array
        if (in_array($userId, $combinedArray)) {
            $collabMember .= "<p>$name</p>";
        }
    }

    return '
    <div class="tabsect t' . $categoryId . '-remember-view">
        <span class="mb-2 text-white" id="name-label">
            <b>Date: </b> ' . $date . '<br/>
            ' . $school . '
            ' . $kindofgrad . '
            <b>Location: </b> ' . $location . '<br/>
            ' . $partnersname . '
        </span>
        <div class="col-12 mb-3 mt-4">
            <h6>Memory:</h6>
            <p>' . $memoryText . '</p>
            <img src="' . htmlspecialchars($image) . '" style="height:200px;width:200px;" />
        </div>
        <div class="col-12 mb-3 mt-4">
            <h6>From Contributed Stories:</h6>
            <p>' . $viewability . '</p>
        </div>
    </div>
    <div class="tabsect t' . $categoryId . '-history-view totallyHide">
        <div class="col-12 mb-3 mt-4">
            <h6>On this day in history:</h6>
            <ul>
                <li>French impressionist painter Claude Monet was born. (1840)</li>
                <li>The BBC began daily radio broadcasts, starting with a news bulletin and a weather forecast. (1922)</li>
                <li>King Charles III of the United Kingdom was born. (1948)</li>
            </ul>
        </div>
        <div class="col-12 mb-3 mt-4">
            <h6>In 1968:</h6>
            <ul>
                <li>Gasoline sold for 50 cents</li>
                <li>Richard Nixon was the newly elected President of the U.S.</li>
                <li>“Hey Jude” by the Beatles was the #1 song in the U.S.</li>
                <li>Dr. Martin Luther King was assassinated.</li>
                <li>US President Lyndon B. Johnson signs the 1968 Civil Rights Act.</li>
                <li>NASA launches the Apollo 8 mission.</li>
                <li>Wilt Chamberlain becomes the 1st NBAer to score 25,000 points.</li>
                <li>IXX Summer Olympic Games open in Mexico City.</li>
            </ul>
        </div>
    </div>
    <div class="tabsect t' . $categoryId . '-collabs-view totallyHide">
        <div class="col-12 mb-3 mt-4">
            <h6>Who can see this memory:</h6>
            <p>' . $collabMember . '</p>
        </div>
    </div>';
}

foreach ($memoriesByLifeLayer as $lifelayer => $memories) {
    foreach ($memories as $memory) {
        $categoryId = getMemoryKey($memory["memorycategory"]);
        $image = 'data:' . $memory["memoryImageExtension"] . ';base64,' . $memory["memoryimage"];
        echo '
        <form class="row g-3 builderForm totallyHide viewMode" id="' . $categoryId . '-viewMode">
            <div>
                <div class="row mt-1 mx-2 my-2">
                    <div class="text-start mb-2 col-9">
                        <h6 class="fw-normal fs-6">' . htmlspecialchars($memory["memoryTitle"] ?? "Unknown Name") . '</h6>
                    </div>
                    <div class="text-end mb-2 col-3"></div>
                </div>
                ' . renderTabs($categoryId) . '
                <div class="col-12 mb-2 tabgroup">
                    ' . renderTabContent($memory, $categoryId, $image) . '
                </div>
                <div class="row mt-1 mx-2 my-2 position-relative">
                    <div class="text-center mb-2 col-12 d-flex justify-content-center align-items-center">
                        <button type="button" class="btn slim-close text-white btn-white" onclick="saveAndClose(\'' . $categoryId . '-viewMode#' . $categoryId . '\')">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </form>';
    }
}
