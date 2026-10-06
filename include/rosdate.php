<?php
/*
 * RouterOS 6/7 date format helper.
 * ROS6 clock: sep/14/2026 (legacy)
 * ROS7 clock: 2026-09-14 (iso)
 */
if (isset($_SERVER["REQUEST_URI"]) && substr($_SERVER["REQUEST_URI"], -11) == "rosdate.php") {
    header("Location:./");
    exit;
}

function mikhmon_detect_date_format($clockDate)
{
    $clockDate = trim((string) $clockDate);
    if ($clockDate === '') {
        return null;
    }
    if (preg_match('/^\d{4}-\d{2}-\d{2}/', $clockDate)) {
        return 'iso';
    }
    if (preg_match('/^[a-z]{3}\/\d{1,2}\/\d{4}/i', $clockDate)) {
        return 'legacy';
    }
    return null;
}

function mikhmon_set_ros_date_format($session, $clockDate)
{
    $fmt = mikhmon_detect_date_format($clockDate);
    if ($fmt) {
        $_SESSION[$session . 'rosdate'] = $fmt;
    }
    return mikhmon_ros_date_format($session);
}

function mikhmon_ros_date_format($session)
{
    if (!empty($_SESSION[$session . 'rosdate'])) {
        return $_SESSION[$session . 'rosdate'];
    }
    return 'iso';
}

function mikhmon_parse_idhr($idhr)
{
    $idhr = trim((string) $idhr);
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $idhr, $m)) {
        return array('year' => $m[1], 'month' => $m[2], 'day' => $m[3], 'format' => 'iso');
    }
    if (preg_match('/^([a-z]{3})\/(\d{1,2})\/(\d{4})/i', $idhr, $m)) {
        $day = $m[2];
        if (strlen($day) === 1) {
            $day = '0' . $day;
        }
        return array('year' => $m[3], 'month' => strtolower($m[1]), 'day' => $day, 'format' => 'legacy');
    }
    return array('year' => '', 'month' => '', 'day' => '', 'format' => '');
}

function mikhmon_parse_idbl($idbl)
{
    $idbl = trim((string) $idbl);
    if (preg_match('/^(\d{2})(\d{4})$/', $idbl, $m)) {
        return array('month' => $m[1], 'year' => $m[2], 'format' => 'iso');
    }
    if (preg_match('/^([a-z]{3})(\d{4})$/i', $idbl, $m)) {
        return array('month' => strtolower($m[1]), 'year' => $m[2], 'format' => 'legacy');
    }
    return array('month' => '', 'year' => '', 'format' => '');
}

function mikhmon_idhr($session, $clockDate = '')
{
    if ($clockDate !== '') {
        $p = mikhmon_parse_idhr($clockDate);
        if ($p['format'] !== '') {
            return mikhmon_build_idhr($p['format'], $p['year'], $p['month'], $p['day']);
        }
    }
    $fmt = mikhmon_ros_date_format($session);
    if ($fmt === 'legacy') {
        return strtolower(date('M')) . '/' . date('d') . '/' . date('Y');
    }
    return date('Y-m-d');
}

function mikhmon_idbl($session, $clockDate = '')
{
    if ($clockDate !== '') {
        $fromHr = mikhmon_idbl_from_idhr($clockDate);
        if ($fromHr !== '') {
            return $fromHr;
        }
    }
    $fmt = mikhmon_ros_date_format($session);
    if ($fmt === 'legacy') {
        return strtolower(date('M')) . date('Y');
    }
    return date('m') . date('Y');
}

function mikhmon_build_idhr($format, $year, $month, $day)
{
    if ($format === 'legacy') {
        return strtolower($month) . '/' . $day . '/' . $year;
    }
    return $year . '-' . $month . '-' . $day;
}

function mikhmon_idbl_from_idhr($idhr)
{
    $p = mikhmon_parse_idhr($idhr);
    if ($p['format'] === 'iso') {
        return $p['month'] . $p['year'];
    }
    if ($p['format'] === 'legacy') {
        return strtolower($p['month']) . $p['year'];
    }
    return '';
}

function mikhmon_month_values($session)
{
    if (mikhmon_ros_date_format($session) === 'legacy') {
        return array(1 => 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec');
    }
    return array(1 => '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
}

function mikhmon_month_names()
{
    return array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
}

function mikhmon_selected_day($idhr)
{
    $p = mikhmon_parse_idhr($idhr);
    return $p['day'];
}

function mikhmon_selected_month($idhr, $idbl)
{
    $h = mikhmon_parse_idhr($idhr);
    if ($h['month'] !== '') {
        return $h['month'];
    }
    $b = mikhmon_parse_idbl($idbl);
    return $b['month'];
}

function mikhmon_selected_year($idhr, $idbl)
{
    $h = mikhmon_parse_idhr($idhr);
    if ($h['year'] !== '') {
        return $h['year'];
    }
    $b = mikhmon_parse_idbl($idbl);
    return $b['year'];
}

function mikhmon_idbl_label($idbl)
{
    $p = mikhmon_parse_idbl($idbl);
    $names = mikhmon_month_names();
    if ($p['format'] === 'iso') {
        $idx = intval($p['month']);
        $label = isset($names[$idx]) ? $names[$idx] : $p['month'];
        return $label . ' ' . $p['year'];
    }
    if ($p['format'] === 'legacy') {
        return ucfirst($p['month']) . ' ' . $p['year'];
    }
    return $idbl;
}

function mikhmon_scheduler_start_date($session)
{
    if (mikhmon_ros_date_format($session) === 'legacy') {
        return strtolower(date('M/d/Y'));
    }
    return date('Y-m-d');
}

function mikhmon_ros_onlogin($expmode, $price, $validity, $sprice, $getlock)
{
    return ':put (",' . $expmode . ',' . $price . ',' . $validity . ',' . $sprice . ',,' . $getlock . ',"); {:local date [ /system clock get date ];:local year "";:local month "";:if ([:pick $date 4 5] = "-") do={:set year [:pick $date 0 4];:set month [:pick $date 5 7];} else={:set year [:pick $date 7 11];:set month [:pick $date 0 3];};:local comment [ /ip hotspot user get [/ip hotspot user find where name="$user"] comment]; :local ucode [:pic $comment 0 2]; :if ($ucode = "vc" or $ucode = "up" or $comment = "") do={ /sys sch add name="$user" disable=no start-date=$date interval="' . $validity . '"; :delay 2s; :local exp [ /sys sch get [ /sys sch find where name="$user" ] next-run]; :local getxp [len $exp]; :if ($getxp = 15) do={ :local d [:pic $exp 0 6]; :local t [:pic $exp 7 16]; :local s ("/"); :local exp ("$d$s$year $t"); /ip hotspot user set comment=$exp [find where name="$user"];}; :if ($getxp = 8) do={ /ip hotspot user set comment="$date $exp" [find where name="$user"];}; :if ($getxp > 15) do={ /ip hotspot user set comment=$exp [find where name="$user"];}; /sys sch remove [find where name="$user"]';
}

function mikhmon_ros_bgservice($name, $mode)
{
    return ':local dateint do={:if ([:pick $d 4 5] = "-") do={:local year [:pick $d 0 4];:local month [:pick $d 5 7];:local days [:pick $d 8 10];:return [:tonum ("$year$month$days")];} else={:local montharray ("jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec");:local days [ :pick $d 4 6 ];:local month [ :pick $d 0 3 ];:local year [ :pick $d 7 11 ];:local monthint ([ :find $montharray $month]);:local month ($monthint + 1);:if ( [len $month] = 1) do={:local zero ("0");:return [:tonum ("$year$zero$month$days")];} else={:return [:tonum ("$year$month$days")];}}}; :local timeint do={ :local hours [ :pick $t 0 2 ]; :local minutes [ :pick $t 3 5 ]; :return ($hours * 60 + $minutes) ; }; :local date [ /system clock get date ]; :local time [ /system clock get time ]; :local today [$dateint d=$date] ; :local curtime [$timeint t=$time] ; :foreach i in [ /ip hotspot user find where profile="' . $name . '" ] do={ :local comment [ /ip hotspot user get $i comment]; :local name [ /ip hotspot user get $i name]; :if ([:pic $comment 4] = "-" and [:pic $comment 7] = "-") do={:local gettime [:pic $comment 11 19]; :local expd [$dateint d=$comment] ; :local expt [$timeint t=$gettime] ; :if (($expd < $today and $expt < $curtime) or ($expd < $today and $expt > $curtime) or ($expd = $today and $expt < $curtime)) do={ [ /ip hotspot user ' . $mode . ' $i ]; [ /ip hotspot active remove [find where user=$name] ];}}; :if ([:pic $comment 3] = "/" and [:pic $comment 6] = "/") do={:local gettime [:pic $comment 12 20]; :local expd [$dateint d=$comment] ; :local expt [$timeint t=$gettime] ; :if (($expd < $today and $expt < $curtime) or ($expd < $today and $expt > $curtime) or ($expd = $today and $expt < $curtime)) do={ [ /ip hotspot user ' . $mode . ' $i ]; [ /ip hotspot active remove [find where user=$name] ];}}}}';
}
