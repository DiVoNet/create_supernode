<?php

//todo: rewrite this script

if (isset($_POST['pubkey'])) {
        $pubkey=htmlentities($_POST['pubkey']);

	// check if pubkey is valid base64
        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $pubkey)) {
            return;
        }

        // search for free ip
        // Alle bereits vergegebenen IP-Adressen aus Wireguard auslesen
        exec("sudo /usr/bin/wg | grep 'allowed ips' | awk -v FS=' ' '{print $3}' | sed 's/\/32//'", $o);

        $found = false;
        // IP Range ist 10.3.0.0/16
        // Die Range geht von 10.3.1.1-10.3.254.254
        // Die vorherigen IPs sind für Backbonegeschichten (z.B. Verbindungen der Supernodes untereinander)
        // 1. Oktet durchlaufen
        for ($oktet1=1; $oktet1<=254; $oktet1++)
        {
                //und 2. Oktet
                for ($oktet2=1; $oktet2<=254; $oktet2++)
                {
                        $IP="10.3.".$oktet1.".".$oktet2;
                        //Wenn zusammengesetzte IP-Adresse NICHT in der Ausgabe von Wireguard erschienen ist
                        if (in_array($IP, $o) == false) {
                                $found = true;
				break;
			}
                }
		if ($found === true) {
                    break;
		}
        }

        $interface=$oktet1.$oktet2;
        exec("sudo /sbin/ip link delete gre-{$interface}", $o);
        exec("sudo /usr/bin/wg set wg0 peer {$pubkey} remove", $o);
        exec("sudo /usr/bin/wg set wg0 peer {$pubkey} allowed-ips {$IP}/32", $o);
        exec("sudo /sbin/ip link add gre-{$interface} type gretap remote {$IP} local 10.3.0.2", $o);
        exec("sudo /sbin/ip link set up dev gre-{$interface}", $o);
        exec("sudo /sbin/brctl addif wireguard gre-{$interface}", $o);

        echo $IP;
}
