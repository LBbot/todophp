<?php
require "config.php"; // Contains CouchDB url and sets up $client class
include_once "header.php"; // top half of the page

// Processes delete operation only after confirming
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    try {
        $name_id = ($_POST["id"]);
        // This gets the _rev needed to delete the doc.
        $response = $client->request("GET", "/to-do-list/$name_id");
        $json = $response->getBody()->getContents();
        $decoded_json = json_decode($json, true);
        $doc_rev = $decoded_json["_rev"];

        // Actual deletion
        $response = $client->request("DELETE", "/to-do-list/$name_id?rev=$doc_rev");
        // Sends you back to index once deletion is complete.
        header("location: index.php");
        exit();
    } catch (Exception $e) {
        exit(include_once "error.php");
    }
}

if (isset($_GET["id"]) && !empty($_GET["id"])) {
    // This runs before submitting - to set up the quote for deletion
    try {
        // Gets the json
        $id = ($_GET["id"]);
        $response = $client->request("GET", "/to-do-list/$id");
        $json = $response->getBody()->getContents();
        $decoded_json = json_decode($json, true);
    } catch (Exception $e) {
        exit(include_once "error.php");
    }
} else {
    $no_id_error = "No item ID was supplied for deletion.";
    include_once "header.php";
    exit(include_once "error.php");
}

?>

<div class="container">
    <div class="box box1">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
            <h3>Delete item</h3>
            <?php
                echo "<div class=\"quote\">" . $decoded_json["note"] . "</div>";
            ?>
            <p>Are you sure you want to delete this item? This cannot be undone. (But you can just write a new one.)</p>
        <input type="submit" value="Yes"><br>
        </form>
        <a href="index.php"><button>No</button></a>
    </div>
</div>

<?php
    include "footer.php";
?>
