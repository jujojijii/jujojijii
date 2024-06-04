<?php
function ymw($date) {

    $dt = new DateTime($date);
    $m = $dt->format('m');
    // 曜日数値（日曜始まり）
    $w = $dt->format('w');
    applog("日付: $date, 曜日数値: $w");

    // 週初め（日曜日）の年月日
    $ws = new DateTime($date);
    $ws->modify("-{$w} day");
    $bm = $ws->format('m');
    applog("週初め（日曜日）の年月日: ".$dt->format('Y-m-d'));

    if ($m != $bm) {
        applog("前月の最終週である: $m != $bm");
        applog("前月の最終日の週を使う: ".$ws->format('Y-m-t'));
        $beforeDate = ymw($ws->format('Y-m-t'));
        return $beforeDate;
    }

    // 何週目かを求める
    $dt = new DateTime($date);
    $fy = $dt->format('Y');
    $fm = $dt->format('m');
    $fd = $dt->format('d');

    // その月の1日の曜日
    $d1 = new DateTime("{$fy}-{$fm}-01");
    $w1 = $d1->format('w');
    applog("その月の1日の曜日: {$fy}-{$fm}-01, w:{$w1}");

    // 何週目かは7で割ればなんとかわかる
    applog("あとは力技で求める day: {$fd}, offset:{$w1}");
    $fd += $w1 - 1;
    $week = intval($fd / 7);
    if (fmod($w1, 7) == 0) $week += 1;
    applog("week:{$week}");

    return "{$fy}/{$fm} {$week}W";
}

function applog($log, $level = 0) {
    if ($level >= 1) {
        echo $log.PHP_EOL;
    }
}

for ($y = 2024; $y <= 2024 ; $y++) {
    for ($m = 1; $m <= 12 ; $m++) {
        $dt = new DateTime("{$y}-{$m}-01");
        $t = $dt->format('t');
        for ($d = 1; $d <= $t ; $d++) {
            echo "{$y}-{$m}-{$d}: ".ymw("{$y}-{$m}-{$d}").PHP_EOL;
        }
    }
}
