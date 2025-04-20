<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php";
$users = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users.json"), true);

global $_PROFILE;

function run(string $command): string {
    $out = [];
    exec($command, $out);
    $line = implode("\n", $out);
    return trim($line);
}

function timeAgo($time): string {
    if (!is_numeric($time)) {
        $time = strtotime($time);
    }

    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "age");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "100");

    $now = time();

    $difference = $now - $time;
    if ($difference <= 10 && $difference >= 0) {
        return $tense = 'just now';
    } elseif ($difference > 0) {
        $tense = 'ago';
    } else {
        $tense = 'later';
    }

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    $period =  $periods[$j] . ($difference >1 ? 's' :'');
    return "{$difference} {$period} {$tense} ";
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= isset($_TITLE) ? "$_TITLE -  pub.minteck.org" : "pub.minteck.org" ?></title>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-md bg-light">
        <a class="navbar-brand" href="/">pub.minteck.org</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/auth">Authentication</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/usage">Resource Usage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/prefs">Preferences</a>
                </li>
            </ul>
        </div>
    </nav>
    <br>

    <?php if (!in_array($_PROFILE['id'], array_keys($users))): ?>
    <div class="container">
        <div class="alert alert-warning">
            <strong>You don't have access to pub.minteck.org (yet)</strong>
            <p>pub.minteck.org is a limited system, and therefore you need manual administrator approval to get access to the system and start discovering it, what it has to offers and collaborate with its community.</p>
            <p>If you want to get access to pub.minteck.org:</p>
            <ul>
                <li>send in an email to <a href="mailto:contact@minteck.org">contact@minteck.org</a>, with your Minteck Account user ID, login or email address, and the user name you would like to have on the system; (you can also attach an SSH public key)</li>
                <li>once you are approved, you will receive a temporary password (valid for 14 days), or no password if you attached a public key;</li>
                <li>you can now login to the system!</li>
            </ul>
        </div>

        <p class="text-muted small">
            <b>Minteck Account ID:</b> <code><?= $_PROFILE['id'] ?></code><br>
            <b>Minteck Account login:</b> <code><?= $_PROFILE['login'] ?></code><br>
            <b>Minteck Account email address:</b> <code><?= $_PROFILE['profile']['email']['email'] ?></code>
        </p>
    </div>
</body>
</html>

<?php die(); else: ?>
<?php $UnixAccount = $users[$_PROFILE['id']]; ?>
<?php endif; ?>
