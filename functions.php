<?php

function disabled_acct($get_user)
{
    $query = "select empfullname, disabled from employees where empfullname = '".addslashes($get_user)."'";
    $result = mysql_query($query);

    while ($row = mysql_fetch_array($result)) {
        if (''.$row['disabled'].'' == 1) {
            echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
            echo "<tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>The account for $get_user is
                  disabled</td></tr>\n";
            echo "<tr class=right_main_text>\n";
            echo "<td align=center valign=top scope=row>\n";
            echo "<table width=300 border=0 cellpadding=5 cellspacing=0>\n";
            echo "<tr class=right_main_text><td align=center>Either re-enable the account or go back to the <a class=admin_headings
                  href='employees.php'>\"Add/Edit/Delete Time\"</a> page and choose an account that is not disabled.</td></tr>\n";
            echo "</table><br /></td></tr></table>\n";
            exit;
        }
    }
}

function get_ipaddress()
{
    if (empty($REMOTE_ADDR)) {
        if (!empty($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
            $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
        } elseif (!empty($_ENV) && isset($_ENV['REMOTE_ADDR'])) {
            $REMOTE_ADDR = $_ENV['REMOTE_ADDR'];
        } elseif (@getenv('REMOTE_ADDR')) {
            $REMOTE_ADDR = getenv('REMOTE_ADDR');
        }
    }
    if (empty($HTTP_X_FORWARDED_FOR)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED_FOR'])) {
            $HTTP_X_FORWARDED_FOR = $_ENV['HTTP_X_FORWARDED_FOR'];
        } elseif (@getenv('HTTP_X_FORWARDED_FOR')) {
            $HTTP_X_FORWARDED_FOR = getenv('HTTP_X_FORWARDED_FOR');
        }
    }
    if (empty($HTTP_X_FORWARDED)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED'])) {
            $HTTP_X_FORWARDED = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED'])) {
            $HTTP_X_FORWARDED = $_ENV['HTTP_X_FORWARDED'];
        } elseif (@getenv('HTTP_X_FORWARDED')) {
            $HTTP_X_FORWARDED = getenv('HTTP_X_FORWARDED');
        }
    }
    if (empty($HTTP_FORWARDED_FOR)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $HTTP_FORWARDED_FOR = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED_FOR'])) {
            $HTTP_FORWARDED_FOR = $_ENV['HTTP_FORWARDED_FOR'];
        } elseif (@getenv('HTTP_FORWARDED_FOR')) {
            $HTTP_FORWARDED_FOR = getenv('HTTP_FORWARDED_FOR');
        }
    }
    if (empty($HTTP_FORWARDED)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED'])) {
            $HTTP_FORWARDED = $_SERVER['HTTP_FORWARDED'];
        } elseif (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED'])) {
            $HTTP_FORWARDED = $_ENV['HTTP_FORWARDED'];
        } elseif (@getenv('HTTP_FORWARDED')) {
            $HTTP_FORWARDED = getenv('HTTP_FORWARDED');
        }
    }
    if (empty($HTTP_VIA)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_VIA'])) {
            $HTTP_VIA = $_SERVER['HTTP_VIA'];
        } elseif (!empty($_ENV) && isset($_ENV['HTTP_VIA'])) {
            $HTTP_VIA = $_ENV['HTTP_VIA'];
        } elseif (@getenv('HTTP_VIA')) {
            $HTTP_VIA = getenv('HTTP_VIA');
        }
    }
    if (empty($HTTP_X_COMING_FROM)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_X_COMING_FROM'])) {
            $HTTP_X_COMING_FROM = $_SERVER['HTTP_X_COMING_FROM'];
        } elseif (!empty($_ENV) && isset($_ENV['HTTP_X_COMING_FROM'])) {
            $HTTP_X_COMING_FROM = $_ENV['HTTP_X_COMING_FROM'];
        } elseif (@getenv('HTTP_X_COMING_FROM')) {
            $HTTP_X_COMING_FROM = getenv('HTTP_X_COMING_FROM');
        }
    }
    if (empty($HTTP_COMING_FROM)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_COMING_FROM'])) {
            $HTTP_COMING_FROM = $_SERVER['HTTP_COMING_FROM'];
        } elseif (!empty($_ENV) && isset($_ENV['HTTP_COMING_FROM'])) {
            $HTTP_COMING_FROM = $_ENV['HTTP_COMING_FROM'];
        } elseif (@getenv('HTTP_COMING_FROM')) {
            $HTTP_COMING_FROM = getenv('HTTP_COMING_FROM');
        }
    }

    // Gets the default ip sent by the user //

    if (!empty($REMOTE_ADDR)) {
        $direct_ip = $REMOTE_ADDR;
    }

    // Gets the proxy ip sent by the user //

    $proxy_ip = '';
    if (!empty($HTTP_X_FORWARDED_FOR)) {
        $proxy_ip = $HTTP_X_FORWARDED_FOR;
    } elseif (!empty($HTTP_X_FORWARDED)) {
        $proxy_ip = $HTTP_X_FORWARDED;
    } elseif (!empty($HTTP_FORWARDED_FOR)) {
        $proxy_ip = $HTTP_FORWARDED_FOR;
    } elseif (!empty($HTTP_FORWARDED)) {
        $proxy_ip = $HTTP_FORWARDED;
    } elseif (!empty($HTTP_VIA)) {
        $proxy_ip = $HTTP_VIA;
    } elseif (!empty($HTTP_X_COMING_FROM)) {
        $proxy_ip = $HTTP_X_COMING_FROM;
    } elseif (!empty($HTTP_COMING_FROM)) {
        $proxy_ip = $HTTP_COMING_FROM;
    }

    // Returns the true IP if it has been found, else FALSE //

    if (empty($proxy_ip)) {
        // True IP without proxy
        return $direct_ip;
    } else {
        $is_ip = preg_match('|^([0-9]{1,3}\.){3,3}[0-9]{1,3}|', $proxy_ip, $regs);
        if ($is_ip && (count($regs) > 0)) {
            // True IP behind a proxy
            return $regs[0];
        } else {
            // Can't define IP: there is a proxy but we don't have
            // information about the true IP
            return false;
        }
    }
}

function ip_range($network, $ip)
{

    /*
     * Based on IP Pattern Matcher
     * Originally by J.Adams <jna@retina.net>
     * Found on <http://www.php.net/manual/en/function.ip2long.php>
     * Modified by Robbat2 <robbat2@users.sourceforge.net>
     *
     * Matches:
     * xxx.xxx.xxx.xxx        (exact)
     * xxx.xxx.xxx.[yyy-zzz]  (range)
     * xxx.xxx.xxx.xxx/nn     (CIDR)
     *
     * Does not match:
     * xxx.xxx.xxx.xx[yyy-zzz]  (range, partial octets not supported)
     *
     * @param   string   string of IP range to match
     * @param   string   string of IP to test against range
     *
     * @return  boolean    always true
     *
     * @access  public
     */

    $result = true;

    if (preg_match('|([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)/([0-9]+)|', $network, $regs)) {
        // performs a mask match
        $ipl = ip2long($ip);
        $rangel = ip2long($regs[1].'.'.$regs[2].'.'.$regs[3].'.'.$regs[4]);

        $maskl = 0;

        for ($i = 0; $i < 31; ++$i) {
            if ($i < $regs[5] - 1) {
                $maskl = $maskl + pow(2, (30 - $i));
            }
        }

        if (($maskl & $rangel) == ($maskl & $ipl)) {
            return true;
        } else {
            return false;
        }
    } else {
        // range based
        $maskocts = explode('.', $network);
        $ipocts = explode('.', $ip);

        // perform a range match
        for ($i = 0; $i < 4; ++$i) {
            if (preg_match('|\[([0-9]+)\-([0-9]+)\]|', $maskocts[$i], $regs)) {
                if (($ipocts[$i] > $regs[2])
                    || ($ipocts[$i] < $regs[1])
                ) {
                    $result = false;
                } // end if
            } else {
                if ($maskocts[$i] != $ipocts[$i]) {
                    $result = false;
                }
            }
        }
    }

    return $result;
}

function setTimeZone()
{
    global $use_client_tz;
    global $use_server_tz;

    if ($use_client_tz == 'yes') {
        if (isset($_COOKIE['tzoffset'])) {
            $tzo = $_COOKIE['tzoffset'];
            settype($tzo, 'integer');
            $tzo = $tzo * 60;
        }
    } elseif ($use_server_tz == 'yes') {
        $tzo = date('Z');
    } else {
        $tzo = 0;
    }
}
