<?php

// prevent loops from internal network
if (strpos($_SERVER['REMOTE_ADDR'], '10.80.') !== false) {
    return;
}

function execute_command($command, &$o) {
    exec($command, $o);
}

if (isset($_POST['pubkey'])) {
    $pubkey = htmlentities($_POST['pubkey']);

    // check if pubkey is valid base64
    if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $pubkey)) {
        return;
    }

    // return all used ip's in wireguard
    execute_command("sudo /usr/bin/wg | grep 'allowed ips' | awk -v FS=' ' '{print $3}' | sed 's/\/32//' | grep '10.3'", $o);

    /**
     *  range is 10.3.0.0/16
     *           10.3.1.1-10.3.254.254
     *  other ip's for backbone like sn connections to each other
     */
    for ($octetOne = 1; $octetOne <= 254; $octetOne++) {
        for ($octetTwo = 1; $octetTwo <= 254; $octetTwo++) {
            $ip = '10.3.' . $octetOne . '.' . $octetTwo;
            if (in_array($ip, $o) == false) {
                break 2;
            }
        }
    }

    $interfaceGretap = 'gre-' . $octetOne . $octetTwo;
    $interfaceWireguardNodes = 'wg-nodes';

    execute_command("sudo /sbin/ip link delete $interfaceGretap", $o);
    execute_command("sudo /usr/bin/wg set $interfaceWireguardNodes peer {$pubkey} remove", $o);
    execute_command("sudo /usr/bin/wg set $interfaceWireguardNodes peer {$pubkey} allowed-ips {$ip}/32", $o);
    execute_command("sudo /sbin/ip link add $interfaceGretap type gretap remote {$ip} local 10.3.0.2", $o);
    execute_command("sudo /sbin/ip link set up dev $interfaceGretap", $o);
    execute_command("sudo /sbin/brctl addif br-wg-nodes $interfaceGretap", $o);

    echo $ip;
}
