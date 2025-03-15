<?php  
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

include 'components/save_send.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- Google Font for premium typography -->
   <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- Home Section -->
<div class="home">

   <section class="center">
      <form action="search.php" method="post">
         <h3>Find Your Perfect Home</h3>
         <div class="box">
            <p>Enter Location <span>*</span></p>
            <input type="text" name="h_location" required maxlength="100" placeholder="Enter city name" class="input">
         </div>
         <div class="flex">
            <div class="box">
               <p>Property Type <span>*</span></p>
               <select name="h_type" class="input" required>
                  <option value="flat">Flat</option>
                  <option value="house">House</option>
                  <option value="shop">Shop</option>
               </select>
            </div>
            <div class="box">
               <p>Offer Type <span>*</span></p>
               <select name="h_offer" class="input" required>
                  <option value="sale">Sale</option>
                  <option value="resale">Resale</option>
                  <option value="rent">Rent</option>
               </select>
            </div>
            <div class="box">
               <p>Minimum Budget <span>*</span></p>
               <select name="h_min" class="input" required>
                  <!-- Add all your budget options here -->
               </select>
            </div>
            <div class="box">
               <p>Maximum Budget <span>*</span></p>
               <select name="h_max" class="input" required>
                  <!-- Add all your budget options here -->
               </select>
            </div>
         </div>
         <input type="submit" value="Search Property" name="h_search" class="btn">
      </form>
   </section>

</div>

<!-- Listings Section -->
<section class="listings">
   <h1 class="heading">Latest Listings</h1>

   <div class="box-container">
      <?php
         $total_images = 0;
         $select_properties = $conn->prepare("SELECT * FROM property ORDER BY date DESC LIMIT 6");
         $select_properties->execute();
         if($select_properties->rowCount() > 0){
            while($fetch_property = $select_properties->fetch(PDO::FETCH_ASSOC)){
               
            $select_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $select_user->execute([$fetch_property['user_id']]);
            $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

            if(!empty($fetch_property['image_02'])){
               $image_coutn_02 = 1;
            }else{
               $image_coutn_02 = 0;
            }
            if(!empty($fetch_property['image_03'])){
               $image_coutn_03 = 1;
            }else{
               $image_coutn_03 = 0;
            }
            if(!empty($fetch_property['image_04'])){
               $image_coutn_04 = 1;
            }else{
               $image_coutn_04 = 0;
            }
            if(!empty($fetch_property['image_05'])){
               $image_coutn_05 = 1;
            }else{
               $image_coutn_05 = 0;
            }

            $total_images = (1 + $image_coutn_02 + $image_coutn_03 + $image_coutn_04 + $image_coutn_05);

            $select_saved = $conn->prepare("SELECT * FROM saved WHERE property_id = ? and user_id = ?");
            $select_saved->execute([$fetch_property['id'], $user_id]);

      ?>
      <form action="" method="POST">
         <div class="box">
            <input type="hidden" name="property_id" value="<?= $fetch_property['id']; ?>">
            <?php
               if($select_saved->rowCount() > 0){
            ?>
            <button type="submit" name="save" class="save"><i class="fas fa-heart"></i><span>Saved</span></button>
            <?php
               }else{ 
            ?>
            <button type="submit" name="save" class="save"><i class="far fa-heart"></i><span>Save</span></button>
            <?php
               }
            ?>
            <div class="thumb">
               <p class="total-images"><i class="far fa-image"></i><span><?= $total_images; ?></span></p> 
               <img src="uploaded_files/<?= $fetch_property['image_01']; ?>" alt="">
            </div>
            <div class="admin">
               <h3><?= substr($fetch_user['name'], 0, 1); ?></h3>
               <div>
                  <p><?= $fetch_user['name']; ?></p>
                  <span><?= $fetch_property['date']; ?></span>
               </div>
            </div>
         </div>
         <div class="box">
            <div class="price"><i class="fas fa-indian-rupee-sign"></i><span><?= $fetch_property['price']; ?></span></div>
            <h3 class="name"><?= $fetch_property['property_name']; ?></h3>
            <p class="location"><i class="fas fa-map-marker-alt"></i><span><?= $fetch_property['address']; ?></span></p>
            <div class="flex">
               <p><i class="fas fa-house"></i><span><?= $fetch_property['type']; ?></span></p>
               <p><i class="fas fa-tag"></i><span><?= $fetch_property['offer']; ?></span></p>
               <p><i class="fas fa-bed"></i><span><?= $fetch_property['bhk']; ?> BHK</span></p>
               <p><i class="fas fa-trowel"></i><span><?= $fetch_property['status']; ?></span></p>
               <p><i class="fas fa-couch"></i><span><?= $fetch_property['furnished']; ?></span></p>
               <p><i class="fas fa-maximize"></i><span><?= $fetch_property['carpet']; ?> sqft</span></p>
            </div>
            <div class="flex-btn">
               <a href="view_property.php?get_id=<?= $fetch_property['id']; ?>" class="btn">View Property</a>
               <input type="submit" value="Send Enquiry" name="send" class="btn">
            </div>

            <!-- Price Negotiation Dropdown -->
            <div class="box">
               <p>Negotiate Price</p>
               <select name="negotiation_percentage" class="input">
                  <?php for($i = 1; $i <= 10; $i++): ?>
                     <option value="<?= $i; ?>"><?= $i; ?>%</option>
                  <?php endfor; ?>
               </select>
               <input type="submit" value="Negotiate Price" name="negotiate" class="btn">
            </div>
         </div>
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">No properties added yet! <a href="post_property.php" style="margin-top:1.5rem;" class="btn">Add New</a></p>';
      }
      ?>
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="listings.php" class="inline-btn">View All</a>
   </div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

<script>
   let range = document.querySelector("#range");
   range.oninput = () =>{
      document.querySelector('#output').innerHTML = range.value;
   }
</script>

</body>
</html>


<!-- Chatbot Button -->
<div id="chatbot-button" class="chatbot-button">
   <i class="fas fa-comment"></i> Chat with us
</div>

<!-- Chat Modal -->
<div id="chatbot-modal" class="chatbot-modal">
   <div class="chatbot-modal-content">
      <span id="close-chatbot" class="close-chatbot">&times;</span>
      <!-- Embed your chatbot here -->
      <iframe src="http://localhost/project/chatbot/bot.php" width="100%" height="100%"></iframe>


   </div>

<!-- Chatbot Modal Styles -->
<style>
   /* Chatbot button styles */
   .chatbot-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #DA2C32;
      color: white;
      border: none;
      border-radius: 50%;
      padding: 15px;
      font-size: 24px;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      z-index: 1000;
   }

   /* Chatbot modal styles */
   .chatbot-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
   }

   .chatbot-modal-content {
      position: relative;
      background-color: white;
      width: 80%;
      height: 80%;
      margin: 50px auto;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
   }

   /* Close button */
   .close-chatbot {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 30px;
      color: #DA2C32;
      cursor: pointer;
   }

   .close-chatbot:hover {
      color: #DA2C32;
   }
</style>

<!-- Chatbot Interaction Script -->
<script>
   // Get elements
   const chatbotButton = document.getElementById("chatbot-button");
   const chatbotModal = document.getElementById("chatbot-modal");
   const closeChatbot = document.getElementById("close-chatbot");

   // Open chatbot modal
   chatbotButton.onclick = function() {
      chatbotModal.style.display = "block";
   }

   // Close chatbot modal
   closeChatbot.onclick = function() {
      chatbotModal.style.display = "none";
   }

   // Close modal if user clicks outside the modal
   window.onclick = function(event) {
      if (event.target == chatbotModal) {
         chatbotModal.style.display = "none";
      }
   }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>