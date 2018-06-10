<?php

$input_filename = "<FILE_PATH>";
$output_filename = "<FILE_PATH>";

$file = file_get_contents($input_filename);
$timetable = json_decode($file, true);

$rows = array(
    "FileType,OuDia.JikokuhyouCsv.1",
    "dia_name",
    "direction",
    "",
    "列車番号,,",
    "列車種別,,",
    "列車名,,",
    "号数,,",
    ",,",
);

foreach ($timetable["timetable"][0]["stations"] as $station) {
    array_push($rows, $station["name"] . "," . $station["type"] . ",");
}
array_push($rows, "備考,,");

foreach ($timetable["timetable"] as $train) {
    $rows[4] .= $train["train_number"] . ",";
    $rows[5] .= $train["train_type"] . ",";
    $rows[6] .= $train["train_name"] . ",";
    $rows[7] .= ",";
    $rows[8] .= ",";

    foreach ($train["stations"] as $index => $station) {
        $input = "";
        if ($station["passing"]) {
            $input = " ﾚ";
        } else if (!$station["via"]) {
            $input = "||";
        } else {
            $input = str_replace(":", "", $station["time"]);
        }
        $rows[9+$index] .= $input . ",";
    }
    $rows[count($rows)-1] .= ",";
}

$output = "";
foreach ($rows as $row) {
    $output .= preg_replace("/,$/", "", $row) . "\n";
}
$output = str_replace(",0", ",", $output);

file_put_contents($output_filename, mb_convert_encoding($output, "Shift-JIS", "UTF-8"));
