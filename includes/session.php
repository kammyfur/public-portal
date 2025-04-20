<?php

if (isset($_COOKIE['SESSION_TOKEN'])) {
    if (str_contains($_COOKIE['SESSION_TOKEN'], ".") || str_contains($_COOKIE['SESSION_TOKEN'], "/")) {
        header("Location: /login");
        die();
    }

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/tokens/" . str_replace(".", "", str_replace("/", "", $_COOKIE['SESSION_TOKEN'])))) {
        $_PROFILE = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/tokens/" . str_replace(".", "", str_replace("/", "", $_COOKIE['SESSION_TOKEN']))), true);

        $_USER = $_PROFILE['login'];
        $_SUID = $_PROFILE['login'];
        $_FULLNAME = $_PROFILE['name'];
    } else {
        header("Location: /login");
        die();
    }
} else {
    header("Location: /login");
    die();
}