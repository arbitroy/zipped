<?php

$counter = 0;
$selected = 'timeline-selected';

foreach ($_SESSION['activities'] as $index => $activityKey):
    $containerClass = ($index % 2 == 0) ? "right-container" : "left-container";

    $activityName = $memoryOptions[$activityKey] ?? "Unknown Activity";

    $imageSrc = "assets/images/icons/right.svg";
    if ($activityKey == 1095) {
        $imageSrc = "assets/images/icons/wedding.png";
    } elseif ($activityKey == 1089) {
        $imageSrc = "assets/images/icons/school.png";
    }
?>


    <div class="timeline-container <?= $containerClass ?> d-flex align-items-stretch">
        <img src="assets/images/icons/<?= $activityKey ?>.svg" alt="Activity" class="timeline-image">
        <div class="timelie-text-box d-flex flex-column align-items-center  <?= $selected ?>">
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

<div class="timeline-container <?php echo ((count($_SESSION['activities'])) % 2 == 0) ? "right-container" : "left-container" ?> d-flex align-items-stretch">
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