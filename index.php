<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; global $UnixAccount; ?>

<div class="container">
    <div class="alert alert-secondary">
        <strong>Login now:</strong> <code>ssh <?= $UnixAccount ?>@pub.minteck.org</code> | <a href="/auth">Manage authentication methods...</a>
    </div>
    <p>
    <?php
    $lines = explode("\n", run("/usr/bin/ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -i $_SERVER[DOCUMENT_ROOT]/includes/ssh root@192.168.1.57 finger $UnixAccount"));
    $server_id = trim(run("/usr/bin/ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -i $_SERVER[DOCUMENT_ROOT]/includes/ssh root@192.168.1.57 cat /var/lastlogin/$UnixAccount"));
    $server = run("/usr/bin/ssh -p " . $server_id . " -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -i $_SERVER[DOCUMENT_ROOT]/includes/ssh root@192.168.1.45 hostname");
    $lastLoginLine = null;
    foreach ($lines as $line) {
        if (str_starts_with($line, "Last login ")) {
            $lastLoginLine = $line;
        }
    }

    if ($lastLoginLine !== null) {
        $trimmed = trim(explode("Last login", $lastLoginLine)[1]);
        $date_raw = trim(explode("on", $trimmed)[0]);
        $date = timeAgo(strtotime($date_raw));
        $address = trim(explode("from", explode("on", $trimmed)[1])[1]);
        $me = $address === $_SERVER['REMOTE_ADDR'];
        echo("Last login <abbr title=\"$date_raw\">$date</abbr> from <code>$address</code>");
        if ($me) echo(" (you)");
        echo(" on server <abbr title=\"ID: $server_id\"><code>$server</code></abbr>.");
    } else {
        echo("No last login information. Either you are currently logged in or you never logged in.");
    }
    ?>
    </p>

    <pre><?= trim(run("/usr/bin/ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -i $_SERVER[DOCUMENT_ROOT]/includes/ssh root@192.168.1.57 who")) ?></pre>

    <div class="list-group">
        <a href="/auth" class="list-group-item list-group-item-action">
            Manage authentication methods (public keys, password, ...)
        </a>
        <a href="/usage" class="list-group-item list-group-item-action">
            See how much disk space your files are currently using
        </a>
        <a href="/prefs" class="list-group-item list-group-item-action">
            Change some user preferences (display name, email, ...)
        </a>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>