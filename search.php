<?php
// session_start();
// $_SESSION['users'] = [
//     "john.doe@example.com" => "John Doe",
//     "jane.doe@example.com" => "Jane Doe",
//     "alice.smith@example.com" => "Alice Smith",
//     "bob.jones@example.com" => "Bob Jones"
// ];
// if (isset($_GET['query'])) {
//     $query = strtolower(trim($_GET['query']));
//     $users = $_SESSION['users'] ?? [];

//     $results = [];
//     foreach ($users as $email => $name) {
//         if (strpos(strtolower($name), $query) !== false) {
//             $results[] = ["email" => $email, "name" => $name];
//         }
//     }

//     echo json_encode($results);
// }

session_start();

if (isset($_GET['query'])) {
    $query = strtolower(trim($_GET['query']));
    $connections = $_SESSION['connections'] ?? [];

    $results = [];

    foreach ($connections as $connection) {
        $name = trim($connection['PersonName']);
        $email = trim($connection['userId']);


        if (!empty($email) && !empty($name) && strpos(strtolower($name), $query) !== false) {
            $results[] = ["email" => $email, "name" => $name];
        }
    }

    echo json_encode($results);
}
