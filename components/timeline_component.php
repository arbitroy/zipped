 <?php

    $counter = 0;
    $selected = 'timeline-selected';
    foreach ($memoriesByLifeLayer as $lifelayer => $memories) {
        foreach ($memories as $memory) {
            $categoryId = getMemoryKey($memory["memorycategory"]);
            if (isset($_SESSION['activities']) && is_array($_SESSION['activities'])) {
                $_SESSION['activities'] = array_filter(
                    $_SESSION['activities'],
                    fn($value) => $value !== $categoryId
                );
            }
            $image = 'data:' . $memory["memoryImageExtension"] . ';base64,' . $memory["memoryimage"];

            $containerClass = $counter % 2 === 0 ? 'right-container' : 'left-container';

            $content = '<h2>' . htmlspecialchars($memory['memorycategory']) . ':</h2>
            <h2>' . htmlspecialchars($memory['memoryTitle']) .  '</h2>
            <small>' . htmlspecialchars($memory['dateofmemory']) . '</small>
            <div class="d-flex align-items-center justify-content-between gap-3">
                <i class="bi bi-pencil text-start"></i>
                <i class="bi bi-eye text-center" onclick="showForm(\'' . $categoryId . '-viewMode#' . $categoryId . '\')"></i>
                <select class="form-select w-40 bg-white custom-dropdown border-0 fw-bold bg-transparent" style="width:40%;" >
                    <option value="0" disabled selected>Options</option>
                    <option value="1">Share</option>
                    <option value="2">Collaborate</option>
                    <option value="3"> <i class="bi bi-trash text-white"></i>Delete</option>
                 </select>
            </div>';


            echo '<div class="timeline-container ' . $containerClass . ' d-flex align-items-stretch">
                <img src="assets/images/icons/' . getMemoryKey($memory['memorycategory']) . '.svg" alt="Icon" class="timeline-image">
                <div class="timelie-text-box ">
                    ' . $content . '
                    
                </div>
                ' . (isset($memory['day_in_history']) && $memory['day_in_history'] ? '<div class="side_icons d-flex flex-column justify-content-center">
                    <p class="side-icon">2</p>
                    <i class="bi bi-calendar4-event side-icon text-white"></i>
                </div>' : '') . '
            </div>';

            $counter++;
        }
    }

    foreach ($_SESSION['activities'] as $index => $activityKey):
        $containerClass = (($counter) % 2 == 0) ? "right-container" : "left-container";


        $activityName = $memoryOptions[$activityKey] ?? "Unknown Activity";

        $imageSrc = "assets/images/icons/right.svg";
        if ($activityKey == 1095) {
            $imageSrc = "assets/images/icons/wedding.png";
        } elseif ($activityKey == 1089) {
            $imageSrc = "assets/images/icons/school.png";
        }
        $counter++;

    ?>


     <div class="timeline-container <?= $containerClass ?> d-flex align-items-stretch">
         <img src="assets/images/icons/<?= $activityKey ?>.svg" alt="Activity" class="timeline-image">
         <div class="timelie-text-box d-flex flex-column align-items-center <?= $selected ?>">
             <p><?php echo $memoryPrompt[$activityKey]; ?> </p>
             <div class="d-flex justify-content-center">
                 <div class="btn btn-white btn-next btn-timeline text-center px-3" onclick="showForm('<?= $activityKey; ?>')">
                     <i class="bi bi-pencil text-black" style="cursor: pointer;" onclick="showStepInvite()"></i> Start
                 </div>
             </div>
         </div>

     </div>
     <?php $selected = ''; ?>
 <?php endforeach; ?>

 <div class="timeline-container <?php echo ($counter % 2 == 0) ? "right-container" : "left-container" ?> d-flex align-items-stretch">
     <img src="assets/images/icons/add.png" alt="Extra" class="timeline-image">
     <div class="timelie-text-box d-flex flex-column align-items-center">
         <p>Record another memory
         <div class="d-flex justify-content-center">
             <div class="btn btn-white btn-next btn-timeline text-center px-3" onclick="showForm('story')">
                 <i class="bi bi-pencil text-black" style="cursor: pointer;" onclick="showStepInvite()"></i> Start
             </div>
         </div>
     </div>

 </div>