<?php

include_once('db.php');
include_once('model.php');

// Getting the user ID from the GET parameter
$user_id = isset($_GET['user']) ? (int)$_GET['user'] : null;

$month_names = [
    '01' => 'January',
    '02' => 'February',
    '03' => 'March',
];

// Checking that the user ID is set
if ($user_id) {
    $conn = get_connect();
    // Getting user transaction data
    $transactions = get_user_transactions_balances($user_id, $conn, $month_names);

    // Forming an array of data for output
    $output_data = [];
    foreach ($month_names as $month_key => $month_name) {
        $month_data = [
            'month' => $month_name,
            'balance' => '-'
        ];

        foreach ($transactions as $transaction) {
            if ($transaction['month'] == $month_key) {
                $balance = $transaction['incoming'] - $transaction['outgoing'];
                $month_data['balance'] = number_format($balance, 2);
                break; // The month has been found, we are exiting the internal cycle
            }
        }

        $output_data[] = $month_data;
    }

    // Setting the Content-Type header for the JSON response
    header('Content-Type: application/json');

    // Отправка данных в формате JSON
    echo json_encode($output_data);
} else {
    // If the user ID is not set, we send an error message
    echo json_encode(['error' => 'User ID is not set']);
}
