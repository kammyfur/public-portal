<?php

header("Location: https://account.minteck.org/hub/api/rest/oauth2/auth?client_id=" . json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/oauth.json"), true)["id"] . "&response_type=code&redirect_uri=https://pub.minteck.org/login/callback&scope=Hub&request_credentials=default&access_type=offline");
die();
