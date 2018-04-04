<div class="container">
  <div class="box box1">
  <h3>Error</h3>
  
    <?php
      if (isset($no_id_error)) {
        echo "<p>" . $no_id_error . "</p>";
      } elseif (isset($e)) {  
        echo "<p>The page has encountered the following error:</p><div class=\"quote\">";
        if (strpos($e->getMessage(), "cURL error 7: Failed to connect to") !== false) {
          echo "<p>The database cannot be reached. Please try again later.</p> </div>";
        } elseif (strpos($e->getMessage(), "404 Object Not Found") !== false) {
          echo "<p>404 Object Not Found - the requested ID does not exist in the database.</p> </div>";
        }
      } else {
        echo "<p>An unknown error has occurred.</p>";
      }

    ?>
  
  <a href="index.php"><button>Return to index</button></a>
  </div>
</div>

<?php
  include "footer.php";
?>