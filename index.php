<?php
include_once('db.php');
include_once('model.php');
include_once('test.php');

$conn = get_connect();
init_db($conn);

?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User transactions information</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <h1>User transactions information</h1>
  <!-- User selection form -->
  <form id="user-form" method="get">
    <label for="user">Select user:</label>
    <select name="user" id="user">
      <?php
      // Getting the list of users from the database
      $users = get_users($conn);
      // We display the list of users in the form of options in a drop-down list
      foreach ($users as $id => $name) {
        echo "<option value=\"" . htmlspecialchars($id) . "\">" . htmlspecialchars($name) . "</option>";
      }
      ?>
    </select>
    <input id="submit" type="submit" value="Show">
  </form>
  <!-- Container for a table with transaction data -->
  <div id="data" style="display: none;">
    <h2 id="user-header">Transactions of</h2>
    <table id="table">
      <thead>
        <tr>
          <th>Month</th>
          <th>Amount</th>
          <th>Count Days</th>
        </tr>
      </thead>
      <!-- The body of the table to be filled in with JavaScript -->
      <tbody></tbody>
    </table>
  </div>

  <script src="script.js"></script>
</body>

</html>