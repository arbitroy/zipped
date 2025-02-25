<?php session_start();

?>
<div class="sidebar-wrapper accent-group" data-simplebar="true">
    <div class="sidebar-header accent-group">
        <div>
            <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Dignitrees</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>

    <?php

    $current_page = basename($_SERVER['PHP_SELF']);
    ?>
    <ul class="metismenu" id="menu">
        <li class="<?php echo ($current_page == 'dashboard' || $current_page == 'timeline.php') ? 'mm-active' : ''; ?>">
            <a href="timeline.php">
                <div class="parent-icon">
                    <img src="assets/images/icons/memories.png" alt="Memories" />
                </div>
                <div class="menu-title">Memories</div>
            </a>
        </li>
        <li class="<?php echo ($current_page == 'family-tree.php') ? 'mm-active' : ''; ?>">
            <a href="family-tree.php">
                <div class="parent-icon"><img src="assets/images/icons/family_tree.png" alt="Family Tree" />
                </div>
                <div class="menu-title">Family Tree</div>
            </a>
        </li>
        <li class="<?php echo ($current_page == 'connections.php') ? 'mm-active' : ''; ?>">
            <a href="connections.php">
                <div class="parent-icon">
                    <img src="assets/images/icons/connections.png" alt="Connections" />
                </div>
                <div class="menu-title">Connections</div>
            </a>
        </li>
        <li class="<?php echo ($current_page == 'collaboration.php') ? 'mm-active' : ''; ?>">
            <a href="collaboration.php">
                <div class="parent-icon">
                    <img src="assets/images/icons/collaboration.png" alt="Collaborations" />
                </div>
                <div class="menu-title">Collaborations</div>
            </a>
        </li>
        <li class="<?php echo ($current_page == 'notifications.php') ? 'mm-active' : ''; ?>">
            <a href="notifications.php">
                <div class="parent-icon">
                    <img src="assets/images/icons/notifications.png" alt="Back" style="height: 1em; vertical-align: middle; margin-right:2px;">
                </div>
                <div class="menu-title">Notifications</div>
            </a>
        </li>
        <li class="<?php echo ($current_page == 'settings.php') ? 'mm-active' : ''; ?>">
            <a href="settings.php">
                <div class="parent-icon">
                    <img src="assets/images/icons/settings.svg" alt="Back" style="height: 1em; vertical-align: middle; margin-right:2px;">
                </div>
                <div class="menu-title">Settings</div>
            </a>
        </li>
    </ul>

</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        const sidebar = document.querySelector(".sidebar-wrapper");
        const toggleArrow = document.querySelector(".toggle-icon");
        const logoIcon = document.querySelector(".logo-icon");
        const logoText = document.querySelector(".logo-text");
        const pageWrapper = document.querySelector(".page-wrapper");

        function isMobile() {
            return window.innerWidth <= 768;
        }


        toggleArrow.addEventListener("click", function() {
            if (isMobile()) {
                $('.wrapper').removeClass("toggled");
            } else {
                sidebar.style.width = "70px";
                toggleArrow.style.display = "none";
                logoText.style.display = "none";
                pageWrapper.style.marginLeft = "70px";
            }
        });


        logoIcon.addEventListener("click", function() {
            if (isMobile()) {
                $('.wrapper').addClass("toggled");
            } else {
                if (sidebar.style.width === "70px") {
                    sidebar.style.width = "250px";
                    pageWrapper.style.marginLeft = "250px";
                    toggleArrow.style.display = "block";
                    logoText.style.display = "block";
                }
            }

        });


        const accent = "<?php echo isset($_SESSION['accent']) ? addslashes($_SESSION['accent']) : 'red'; ?>";
        document.documentElement.style.setProperty('--accent-color', accent);
        const elementsToStyle = [
            '.onboarding-card',
            '.sidebar-wrapper',
            '.sidebar-header',
            '.topbar',
            '.connections-header',
            '.connection-card',
            '.accent-group',
            '.setings-container',
            '.timeline-header',
            '.timelie-text-box',
            '.builderForm',
            '.bg-dark',
            '.connection-container-form',
            '.collaborations-header',
            '.collaboration-card',
            '.notification-header',
            '.notification-card',
            '.page-footer',
            '.dropdown-menu',
            '.topbar .navbar .dropdown-menu::after'
        ];

        elementsToStyle.forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.style.background = accent;
                element.style.setProperty('background', accent, 'important');
            }
            const elementsToChange = document.querySelectorAll(selector);
            elementsToChange.forEach((el) => {
                el.style.background = accent;
            });
        });


    });
</script>