<?php
    # ¿El usuario ha iniciado sesión?
    function isUserLoggedIn($url_login = RUTA_URL.'User/login/') {
        if (!isset($_SESSION['session'])) {
            header("Location:" . $url_login);
            exit;
        }
    
    }
?>