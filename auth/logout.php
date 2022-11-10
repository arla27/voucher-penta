<?php
include_once("../helper/conn.php");
include_once("../helper/function.php");

session_destroy();
redirect("login.php");
