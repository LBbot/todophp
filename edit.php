<?php
require "config.php"; // Contains CouchDB url and sets up $client class
include_once "header.php"; // top half of the page

$input_error = "";

// To set up the pre-written textbox
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $name_id = ($_GET["id"]);

    // 404 or database error catching
    try {
        $response = $client->request("GET", "/to-do-list/$name_id");
        $json = $response->getBody()->getContents();
        $decoded_json = json_decode($json, true);
    } catch (Exception $e) {
        exit(include_once "error.php");
    }
// If page is called without id.
} elseif (!isset($_POST["id"]) && empty($_POST["id"])) {
    $no_id_error = "No item ID was supplied to edit.";
    exit(include_once "error.php");
}

// Checks if empty id, and POSTS edit (THERE HAS TO BE A WAY TO AVOID REPEATING THE GET)
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    try {
        $name_id = ($_POST["id"]);
        $response = $client->request("GET", "/to-do-list/$name_id");
        $json = $response->getBody()->getContents();
        $decoded_json = json_decode($json, true);
    } catch (Exception $e) {
        exit(include_once "error.php");
    }

    // Text validation
    $input_note = htmlspecialchars(trim($_POST["editednote"])); // Sanitizes any scripting tags
    if (empty($input_note) && $input_note != "0") { // empty() will read "0" (even as a string) as empty
        $input_error = "<i class=\"fas fa-exclamation-triangle\"></i> You must enter an item before adding it.";
    }
    if (empty($input_error)) {
        // Actual replacement and send
        $decoded_json["note"] = $input_note;
        $response = $client->request("PUT", "/to-do-list/$name_id", [
            "json" => $decoded_json
        ]);
        // Sends you back to index.
        header("location: index.php");
        exit();
    }
}

?>

<div id="inputarea">
    <div class="box box1">
        <h3>Edit item</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" maxlength="240" name="editednote" value="<?php echo $decoded_json["note"]; ?>">
        <input type="hidden" name="id" value="<?php echo $decoded_json["_id"]; ?>">
        <div class="error"><?php echo $input_error;?></div>
        <br />
        <input type="submit" value="Confirm edit">
        </form>
        <a href="index.php"><button>Cancel</button></a>
    </div>
</div>

<?php
include "footer.php";
?>