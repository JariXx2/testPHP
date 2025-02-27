<?php

/**
 * Return list of users.
 */
function get_users($conn)
{
    //A database query that returns all user ids and names that have incoming and outgoing transactions.
    $sql = "SELECT u.id as id, u.name as name FROM users as u 
    INNER JOIN user_accounts as ua ON u.id = ua.user_id 
    INNER JOIN transactions as t ON ua.id = t.account_from 
    OR ua.id = t.account_to";

    //Executing SQL query
    $state = $conn->query($sql);

    //We create an array and fill it with the values ​​obtained from the request
    //We use the user's id as the id, and the name as the value.
    $users = [];
    while ($row = $state->fetch(PDO::FETCH_ASSOC)) {
        $users[$row['id']] = $row['name'];
    }

    //Return the received array as a response
    return $users;
}

/**
 * Return transactions balances of given user.
 */
function get_user_transactions_balances($user_id, $conn, $month_names)
{
    // SQL query to get the monthly balance of a user, 
    // taking into account that he may have several accounts.
    $sql = "
        SELECT 
            strftime('%m', t.trdate) AS month,
            COUNT(DISTINCT strftime('%Y-%m-%d', t.trdate)) AS days,
            SUM(CASE WHEN ua.id = t.account_to THEN t.amount ELSE 0 END) AS incoming,
            SUM(CASE WHEN ua.id = t.account_from THEN t.amount ELSE 0 END) AS outgoing,
            COUNT(DISTINCT CASE WHEN ua.id = t.account_from THEN strftime('%Y-%m-%d', t.trdate) END) AS days
        FROM transactions t
            INNER JOIN user_accounts ua ON ua.id = t.account_from OR ua.id = t.account_to
            WHERE ua.user_id = :user_id
            GROUP BY strftime('%m', t.trdate)
    ";

    // Request preparation
    $stmt = $conn->prepare($sql);

    // Binding a parameter
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // Request Execution
    $stmt->execute();

    // Getting results
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Filtering results to account for only months from $month_names
    $filtered_result = array_filter($result, function ($item) use ($month_names) {
        return array_key_exists($item['month'], $month_names);
    });

    return $filtered_result;
}
