<?php
/*set_time_limit(30);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);
*/
// config
$ldapserver = 'ldap://10.175.10.133';
//$ldapserver = 'ldap://10.2.156.35';
//$ldapserver = 'ldap://10.2.198.201';
$ldapuser      = 'msetuhelpdesk';
$ldappass     = 'Msetu,1000';
//$ldapuser      = '23106254';
//$ldappass     = 'Aug@2018';
//$ldapuser      = '23103788';
//$ldappass     = '23@comeback';

$ldaptree    = "DC=corp,DC=mahindra,DC=com";

// connect
$ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");

if($ldapconn) {
ldap_set_option ($ldapconn, LDAP_OPT_REFERRALS, 0);
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful...<br /><br />";


        $result = ldap_search($ldapconn,$ldaptree, "(sn=pandya)") or die ("Error in search query: ".ldap_error($ldapconn));
        $data = ldap_get_entries($ldapconn, $result);
        echo "Number of entries found: " . ldap_count_entries($ldapconn, $result);
        // SHOW ALL DATA
        echo '<h1>Dump all data</h1><pre>';
        print_r($data);
        echo '</pre>';


        // iterate over array and print data for each entry
        echo '<h1>Show me the users</h1>';
        for ($i=0; $i<$data["count"]; $i++) {
            //echo "dn is: ". $data[$i]["dn"] ."<br />";
            echo "User: ". $data[$i]["cn"][0] ."<br />";
            if(isset($data[$i]["mail"][0])) {
                echo "Email: ". $data[$i]["mail"][0] ."<br /><br />";
            } else {
                echo "Email: None<br /><br />";
            }
        }
        // print number of entries found

    } else {
        echo "LDAP bind failed...";
    }

}

// all done? clean up
ldap_close($ldapconn);
?>