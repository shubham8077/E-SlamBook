<?php
if (isset($_SESSION["uloggedin"]) && $_SESSION["uloggedin"] == true) {
    $uloggedin = true;
} else {
    $uloggedin = false;
}
?>

<div class="bg-sky-950 flex h-10 justify-items-center">
    <img src="img/DS.png" class="h-auto justify-center align-middle">
    <div class="text-white flex-row p-2 border border-white rounded-md m-0 font-bold shadow-white shadow-inner hover:text-sky-300 active:font-semisbold select-none"
        id="home" onclick="menu()">Menu</div>
    <div id="options" class="hidden">
        <?php
        echo '<div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"
onclick="playClickSound()"><a href="uabout.php" title="About Slambook">About</a></div>
<!--music-->
<div class="flex items-center m-2 font-bold hover:text-sky-300 active:font-semisbold select-none" id="musicmode" onclick="musicmode()" title="Music on">
                    <div id="musicText" class="text-white hover:text-sky-300 active:font-semisbold">Music</div>
                </div>';
        if (!$uloggedin) {
            echo '
                <div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"><a href="usignup.php" onclick="playClickSound()">Signup</a></div>
                <div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"><a href="ulogin.php" onclick="playClickSound()">Login</a></div>';
        }

        if ($uloggedin) {

            $hashedAdminId = hash('sha256', $_SESSION['admin_id']);

            echo '
                <div class="flex items-center m-2 font-bold hover:text-sky-300 active:font-semisbold select-none" id="musicmode" onclick="musicmode()" title="Music on">
                    <div id="musicText" class="text-white hover:text-sky-300 active:font-semisbold">Music</div>
                </div>
                <div class="text-white flex-row m-2 font-bold hover:text-sky-300 active:font-semisbold select-none"><a href="logout.php" onclick="playClickSound()" title="">Logout</a></div>';
        }
        ?>
    </div>
</div>

<audio id="clickSound" src="clickSound.mp3"></audio>
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