<?php

$currentPage = $_SERVER['SCRIPT_NAME'];

session_start();


isset($_SESSION["username"]) ? $name = $_SESSION["username"] : '';

$error = '';

$year = date('Y');

if (trim($_SESSION['background']) == '') {
    $_SESSION['background'] = 'Summer';
}
if (trim($_SESSION['token']) == '') {
    $_SESSION['accent'] = "rgba(0, 0, 0, 0.70)";
}
// functions
function makePostAPIcall($uri, $body, $token = '')
{
    $header = array('Content-Type: application/json');

    if ($token != '') {
        array_push($header, 'Authorization: Bearer ' . $token);
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://143.110.147.146:3999/' . $uri,
        // CURLOPT_URL => 'http://localhost:3999/' . $uri,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}

function makeGetAPICall($uri, $token = '')
{
    $header = array('Authorization: Bearer ' . $token);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://143.110.147.146:3999/' . $uri,
        // CURLOPT_URL => 'http://localhost:3999/' . $uri,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}

function mapOptionSets($arr_name, $selected = 0)
{
    $optionStr = '<select class="form-select border-white" id="' . $arr_name . '" name="' . $arr_name . '">
    <option>Select Option</option>';

    $arr = $_SESSION[$arr_name];

    foreach ($arr as $arr_items) {
        ($selected == $arr_items['key']) ? ($optionStr .= '<option selected value="' . $arr_items['key'] . '">' . $arr_items['value'] . '</option>') : ($optionStr .= '<option value="' . $arr_items['key'] . '">' . $arr_items['value'] . '</option>');
    }

    $optionStr .= '</select>';

    return $optionStr;
}

function getOptionID($arr_name, $searchPhrase = '')
{
    $optionId = 0;
    $arr = $_SESSION[$arr_name];

    foreach ($arr as $arr_items) {
        $arr_items['value'] == $searchPhrase ? $optionId = $arr_items['key'] : '';
    }

    return $optionId;
}


function getFamilyOptionKey($value)
{

    $familyOptions = [
        ["key" => 1104, "value" => "Parent"],
        ["key" => 1105, "value" => "Child"],
        ["key" => 1106, "value" => "Grandparent"],
        ["key" => 1107, "value" => "Grandchild"],
        ["key" => 1108, "value" => "Sibling"],
        ["key" => 1109, "value" => "Spouse"],
        ["key" => 1110, "value" => "Aunt/Uncle"],
        ["key" => 1111, "value" => "Niece/Nephew"],
        ["key" => 1112, "value" => "Cousin"],
        ["key" => 1113, "value" => "Great-grandparent"],
        ["key" => 1114, "value" => "Great-grandchild"],
        ["key" => 1115, "value" => "In-law"],
        ["key" => 1116, "value" => "Step-parent"],
        ["key" => 1117, "value" => "Step-child"],
        ["key" => 1118, "value" => "Half-sibling"],
        ["key" => 1119, "value" => "Godparent"],
        ["key" => 1120, "value" => "Foster parent"],
        ["key" => 1121, "value" => "Foster child"],
        ["key" => 1122, "value" => "Adoptive parent"],
        ["key" => 1123, "value" => "Adopted child"],
        ["key" => 1124, "value" => "Other"]
    ];

    foreach ($familyOptions as $option) {
        if (strcasecmp($option['value'], $value) === 0) {
            return $option['key'];
        }
    }

    return 1124;
}


function getAccentByValue($value)
{
    $colors = [
        1150 => 'rgba(0, 0, 0, 0.5)',
        1142 => 'rgba(140, 69, 21, 0.50)',
        1143 => 'rgba(13, 99, 62, 0.50)',
        1144 => 'rgba(34, 68, 191, 0.50)',
        1145 => 'rgba(120, 47, 193, 0.50)',
        1146 => 'rgba(182, 255, 202, 0.50)',
        1147 => 'rgba(255, 228, 189, 0.50)',
        1148 => 'rgba(185, 234, 255, 0.50)',
        1149 => 'rgba(227, 199, 255, 0.50)',
    ];
    return isset($colors[$value]) ? $colors[$value] : 'rgba(0, 0, 0, 0.7)';
}

function getValueByText($text)
{

    $textToValue = [
        "Black" => 1141,
        "Brown" => 1142,
        "Green" => 1143,
        "Blue" => 1144,
        "Purple" => 1145,
        "Light Yellow" => 1146,
        "Light Green" => 1147,
        "Light Blue" => 1148,
        "Lavender" => 1149
    ];

    return isset($textToValue[$text]) ? $textToValue[$text] : 1150;
}
$memoryOptions = [
    1089 => "Started School",
    1090 => "Made a friend",
    1091 => "Graduated",
    1092 => "Moved to a new home",
    1093 => "Began first job",
    1094 => "Fell in love",
    1095 => "Got Married",
    1131 => "Became a parent",
    1132 => "Adopted a special pet",
    1133 => "Reached a faith milestone"
];

$memoryPrompt = [
    1089 => "Record the story of starting school.",
    1090 => "Record the memory of meeting a friend.",
    1091 => "Record your graduation story.",
    1092 => "Record the experience of moving to a new home.",
    1093 => "Record the story of your first job.",
    1094 => "Record the moment you fell in love.",
    1095 => "Record your wedding story.",
    1131 => "Record your journey to becoming a parent.",
    1132 => "Record the story of adopting your special pet.",
    1133 => "Record the story of reaching a faith milestone."
];


function getValueByKey($key, $data)
{
    return $data[$key] ?? null;
}

function getKeyByValue($value, $data)
{
    $key = array_search($value, $data, true);
    return $key !== false ? $key : null;
}

function getMemoryKey($search)
{
    $memoryOptions = [
        1089 => "Started School", // should be 1089
        1090 => "Made a friend", // should be 1090
        1091 => "Graduated",
        1092 => "Moved to a new home",
        1093 => "Began first job",
        1094 => "Fell in love",
        1095 => "Got Married",
        1131 => "Became a parent",
        1132 => "Adopted a special pet",
        1133 => "Reached a faith milestone"
    ];
    foreach ($memoryOptions as $key => $value) {
        if (strcasecmp($search, $value) === 0) {
            return $key;
        }
    }
    return null;
}
