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
function get_user_transactions_balances($user_id, $conn)
{
    // TODO: implement
    return [];
}
