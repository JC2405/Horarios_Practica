<?php
session_start();
session_destroy();
header("Location: vista/plantilla.php");
exit();