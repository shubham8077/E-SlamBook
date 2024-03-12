<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    $loggedin = true;
} else {
    $loggedin = false;
}
?>

<div class="bg-sky-950 flex h-10 justify-items-center">
    <img src="img/DS.png" class="h-auto justify-center align-middle">
    <div class="text-white flex-row p-2 border border-white rounded-md m-0 font-bold shadow-white shadow-inner hover:text-sky-300 active:font-semisbold select-none"
        id="home" onclick="menu()">Menu</div>
    <div id="options" class="hidden items-center overflow-y-auto">
        <?php
        echo '<div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"
onclick="playClickSound()"><a href="about.php" title="About Slambook">About</a></div>
<!-- music-->
<div class="flex items-center m-2 font-bold hover:text-sky-300 active:font-semisbold select-none" id="musicmode" onclick="musicmode()" title="Music on">
                    <div id="musicText" class="text-white hover:text-sky-300 active:font-semisbold">Music</div>
                </div>';
        if (!$loggedin) {
            echo '
                <div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"><a href="signup.php">Signup</a></div>
                <div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"><a href="login.php">Login</a></div>';
        }

        if ($loggedin) {
            $hashedAdminId = hash('sha256', $_SESSION['admin_id']);
            echo '
                <div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"><a href="home.php" title="Share Link">Link</a></div>
                <div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"><a href="myslambook.php" title="My Slambook">Slambook</a></div>
                <div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"><a href="badge.php?admin_id=' . $hashedAdminId . '" title="Badges Achieved">Badges</a></div>
                <div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"><a href="ffreport.php" title="">Search</a></div>
                <div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"><a href="dreport.php" title="User Report">Report</a></div>
                <div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"><a href="logout.php" title="">Logout</a></div>';
        }
        ?>
    </div>
</div>

<audio id="clickSound" src="sound/a1.mp3"></audio>
<audio id="music" autoplay loop>
    <source src="sound/guitar_bg.mp3" type="audio/mp3">
</audio>

<script>
    const clickSound = document.getElementById("clickSound");
    const music = document.getElementById("music");
    let isOpen = false;
    let mode = true;

    // Function to toggle menu visibility
    function menu() {
        const options = document.getElementById("options");
        options.style.display = isOpen ? "none" : "flex";
        clickSound.play();
        isOpen = !isOpen;
    }

    // Function to play click sound
    function playClickSound() {
        clickSound.play();
    }

    // Function to toggle music mode
    function musicmode() {
        const musicTextElement = document.getElementById("musicText");

        if (mode) {
            musicTextElement.innerHTML = 'Music';
            music.play();
            // Store music state in local storage
            localStorage.setItem('musicState', 'playing');
        } else {
            musicTextElement.innerHTML = '<strike class="text-red-400" title="Music off">Music</strike>';
            music.pause();
            // Store music state in local storage
            localStorage.setItem('musicState', 'paused');
        }

        mode = !mode;
    }

    // Check music state on page load
    window.onload = function () {
        const musicState = localStorage.getItem('musicState');
        if (musicState === 'paused') {
            // If music is paused, update mode variable
            mode = false;
            // Call musicmode to update UI
            musicmode();
        }
    };
</script>