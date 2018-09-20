<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php
    // This determines the page
    $pageurl = $_SERVER["PHP_SELF"];
    if ($pageurl == "/edit.php") {
        $titlevar = "Edit item - TODO List";
    } elseif ($pageurl == "/delete.php") {
        $titlevar = "Delete item- TODO List";
    } elseif ($pageurl == "/error.php") {
        $titlevar = "Error - TODO List";
    } else {
        $titlevar = "TODO List";
    }
    echo "<title>$titlevar</title>";
    ?>

    <link href="https://fonts.googleapis.com/css?family=Lato|Playfair+Display" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/solid.css" integrity="sha384-v2Tw72dyUXeU3y4aM2Y0tBJQkGfplr39mxZqlTBDUZAb9BGoC40+rdFCG0m10lXk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/fontawesome.css" integrity="sha384-q3jl8XQu1OpdLgGFvNRnPdj5VIlCvgsDQTQB6owSOHWlAurxul7f+JpUOVdAiJ5P" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>TODO List</h1>
</header>
