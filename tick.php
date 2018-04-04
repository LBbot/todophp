<?php
  require "config.php"; // Contains CouchDB url and sets up $client class

  if(isset($_GET["id"]) && !empty($_GET["id"])){
  // 404 or database error catching
    try {
      // Gets the json
      $id = ($_GET["id"]);
      $response = $client->request("GET", "/to-do-list/$id");
      $json = $response->getBody()->getContents();
      $decoded_json = json_decode($json, true);
    } catch (Exception $e) {
      include_once "header.php";
      exit(include_once "error.php");
    }
    
    // Checks ticked boolean in json and reverses it
    if ($decoded_json["ticked"] == 1) {
      $decoded_json["ticked"] = 0;
    } elseif ($decoded_json["ticked"] == 0) {
      $decoded_json["ticked"] = 1;
    }
    // Actually sends the json
    $response = $client->request("PUT", "/to-do-list/$id", [
      "json" => $decoded_json
    ]);
    // Sends you straight back to index
    header("location: index.php");
    exit();
  } else {
    // Catching page errors.
    $no_id_error = "No item ID was supplied to cross out or undo.";
    include_once "header.php";
    exit(include_once "error.php");
  }

?>
