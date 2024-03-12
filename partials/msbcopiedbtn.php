<?php
echo '
    <script>

        function copyfun() {
            let copylink = document.getElementById("cplink");
            let btn = document.getElementById("btn");
            copylink.value="http://localhost/slambook/welcome.php";
            copylink.select();
            copylink.setSelectionRange(0, 9999);
            navigator.clipboard.writeText(copylink.value);
            btn.innerHTML = "Copied";
            btn.style.backgroundColor = "lightgreen";
            
        }
    </script>'
    ?>