<?php
  require "config.php"; // Contains CouchDB url and sets up $client class
  include_once "header.php"; // top half of the page

  // NOTE: the following operations/variable can be done on one line, it's just unreadable:
  // $decoded_json = json_decode(($client->request("GET", "/to-do-list/_design/tickcheck/_view/ticktrue", [])->getBody()->getContents()), true);

  // Checking for True tick view
  try {
      $true_response = $client->request("GET", "/to-do-list/_design/tickcheck/_view/tickbydate", []);
  } catch (Exception $e) {
    $no_db_error = "The database cannot be reached. Please try again later.";
    exit(include_once "error.php");
  }
  $true_json = $true_response->getBody()->getContents();
  $true_decoded_json = json_decode($true_json, true);

  // Checking for False tick view
  $false_response = $client->request("GET", "/to-do-list/_design/tickcheck/_view/tickfalse", []);
  $false_json = $false_response->getBody()->getContents();
  $false_decoded_json = json_decode($false_json, true);

  // Setting blank variables
  $input_error = "";
  $boxcount = 0;

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Text validation
    $input_note = htmlspecialchars(trim($_POST["note"])); // Sanitizes any scripting tags 
    if (empty($input_note) && $input_note != "0") { // empty() will read "0" (even as a string) as empty
      $input_error = "<i class=\"fas fa-exclamation-triangle\"></i> You must enter an item before adding it.";
    }

    if (empty($input_error)) {
      $new_json_content = [
        "note" => $input_note,
        "ticked" => 1,
        "created_at" => date(DateTime::ATOM),
        "readable_date" => date("H:i:sA D d/m/Y")
      ];
      $response = $client->request("POST", "/to-do-list", [
        "json" => $new_json_content
      ]);
      // Checks for confirmation
      if ($response->getBody()){
        header("location: index.php");
        exit();
      } else {
        echo "There was a problem adding the note.";
      }
    }
  }
?>

<!-- New item input -->
<div id="inputarea">
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="text" maxlength="240" name="note">
    <input type="submit" value="Add new item">
    <div class="error"><?php echo $input_error;?></div>
  </form>
</div>

<!-- To-do item list -->
<div class="container">
  <ul>
    <?php
      // Checks if there are no items left on the list
      if (!$true_decoded_json["rows"]) {
        echo "<div class=\"box box1\"><li>No items on your to-do list! Why not add a new one?</li></div>";
      } else {
        foreach ($true_decoded_json["rows"] as $true_couch_document) {
          $boxcount += 1;
          // The IF below makes sure boxes are different colours
          if ($boxcount % 2 == 0) {
            echo "<div class=\"box box2\"><li>" ;
          } else {
            echo "<div class=\"box box1\"><li>";
          }
          // First key index is date for sorting, second is the note
          echo $true_couch_document["key"][1] . "</li> 
          

            <div class = \"buttons\">
              <div class = \"dateadded\">Added " . 
                  $true_couch_document["key"][2]
              . "</div>
               <a href=\"tick.php?id=" . $true_couch_document["id"] . "\" title=\"Cross out\"><i class=\"fas fa-check\"></i></a>
              <a href=\"edit.php?id=" . $true_couch_document["id"] . "\" title=\"Edit\"><i class=\"fas fa-pencil-alt\"></i></a>
              <a href=\"delete.php?id=" . $true_couch_document["id"] . "\" title=\"Delete\"><i class=\"fas fa-trash-alt\"></i></a>
            </div>
          </div>";
        }
      }
    ?>
  </ul>
</div>

<!-- Ticked off items -->
<div class="container">
  <ul>
    <?php
      foreach ($false_decoded_json["rows"] as $false_couch_document) {
        echo "<div class=\"box box3\"><li>" . $false_couch_document["key"][1] . "</li>
          <div class=\"buttons\">
            <div class = \"dateadded\">Added " . 
              $false_couch_document["key"][2]
            . "</div>
            <a href=\"tick.php?id=" . $false_couch_document["id"] . "\"><i title=\"Undo cross out\" class=\"fas fa-undo\"></i></a>
            <a href=\"edit.php?id=" . $false_couch_document["id"] . "\" title=\"Edit\"><i class=\"fas fa-pencil-alt\"></i></a>
            <a href=\"delete.php?id=" . $false_couch_document["id"] . "\" title=\"Delete\"><i class=\"fas fa-trash-alt\"></i></a>
          </div>
        </div>";
      }
    ?>
  </ul>
</div>

<?php
  include "footer.php";
?>