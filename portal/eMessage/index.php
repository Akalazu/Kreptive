<!-- Contact Page Styles -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php

require_once 'config.php';

?>


<div class="container mt-2 chat-list-container">
  <div class="content-sidebar-title">Kreptive Community</div>
  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
  </svg>

  <div class="alert alert-success d-flex align-items-center p-4" role="alert">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
      <use xlink:href="#info-fill" />
    </svg>
    <div class="ml-3">
      Welcome to Kreptive — the community for art enthusiasts! Connect, engage, and explore creative conversations with fellow creators.
    </div>
  </div>
  <div class="chat-list">

    <?php

    $sql = "SELECT * FROM `reg_details` WHERE `id` != :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':idd', $currUser->id);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_OBJ);
    foreach ($results as $user) {

      if ($user->badge_verification) {
        $badge = '<img src="../../images/blue-tick.png" class="rounded-circle img-fluid" alt="User Icon" style="width: 30px;">';
      } else {
        $badge = "";
      }


    ?>
      <!-- Chat Item 2 -->
      <div class="chat-item d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <img src="../../<?= $user->image ?>" class="rounded-circle img-fluid img-thumbnail" alt="User Icon" style="width: 60px;">
          <div class="chat-details ml-3">
            <h5 class="chat-title mb-1 d-flex align-items-center"><?= $user->first_name . ' ' . $user->last_name ?> <?= $badge ?></h5>
            <p class="chat-preview mb-0 text-muted"> <?= $user->bio ?? "Hello there! I'll love to connect here on Kreptive Community" ?></p>
          </div>
        </div>
        <div class="text-right" data-id="<?= $user->id ?>">
          <i class="fa-solid fa-comment"></i>
        </div>
      </div>

    <?php
    }
    ?>



  </div>
</div>

<!-- Chat Modal -->
<div id="chatModal" class="modal">
  <div class="modal-container">
    <div class="modal-content">
      <span class="close-button">&times;</span>
      <form id="chatForm">
        <textarea id="message" placeholder="Type your message..."></textarea>
        <button type="submit">Send</button>
      </form>
    </div>
  </div>

</div>

<?php
require_once 'footer.php'
?>