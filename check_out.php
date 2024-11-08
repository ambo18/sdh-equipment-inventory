<?php
  $page_title = 'All Items';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  
   // Check if there's a search query
  $search_query = '';
  if (isset($_GET['search'])) {
      $search_query = $_GET['search'];
      $products = search_items($search_query);
  } else {
      $products = join_product_table();
  }
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <form action="check_out.php" method="GET" class="form-inline pull-left">
            <input type="text" name="search" class="form-control" placeholder="Search item...">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="check_out.php" class="btn btn-danger">Reset</a>
        </form>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th class="text-center"> Photo</th>
                <th class="text-center"> Item Name </th>
                <th class="text-center"> Category </th>
                <th class="text-center"> Number of Items </th>
                <th class="text-center"> Description </th>
                <th class="text-center"> Works/Don't Work </th>
                <th class="text-center"> Where Found </th>
                <th class="text-center"> Checkin By </th>
                <th class="text-center"> Chek in Date </th>
                <th class="text-center"> Check in Room </th>
                <th class="text-center"> Check in Location </th>
                <th class="text-center"> Check in Location Barcode </th>
                <th class="text-center"> Check in item Barcode </th>
                <th class="text-center"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($products)): ?>
                  <tr>
                      <td colspan="15" class="text-center btn-danger">No items found</td>
                  </tr>
              <?php else: ?>
                <?php foreach ($products as $product):?>
                <tr>
                  <td class="text-center"><?php echo count_id();?></td>
                  <td>
                    <?php if($product['media_id'] === '0'): ?>
                      <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                    <?php else: ?>
                    <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                  <?php endif; ?>
                  </td>
                  <td> <?php echo remove_junk($product['name']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['quantity']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['description']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['status']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['where_found']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['checkin_by']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['checkin_date']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['checkin_room']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['checkin_location']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['checkin_location_barcode']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['checkin_item_barcode']); ?></td>
                  <td class="text-center">
                    <div class="btn-group">
                      <button 
                        class="btn btn-info btn-xs" 
                        title="Check Out" 
                        data-toggle="modal" 
                        data-target="#checkoutModal"
                        onclick="fillModal(<?php echo (int)$product['id']; ?>, '<?php echo remove_junk($product['name']); ?>', '<?php echo remove_junk($product['categorie']); ?>')"
                      >
                        <span class="glyphicon glyphicon-log-out"></span>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
             <?php endif; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>

  <!-- Check Out Modal -->
  <div id="checkoutModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Check Out Item</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="checkout_item.php">
            <input type="hidden" name="product-id" id="modal-product-id">
            
            <!-- Item Information (Read-only fields) -->
            <div class="form-group">
              <label>Item Name:</label>
              <input type="text" class="form-control" id="modal-product-name" readonly>
            </div>
            <div class="form-group">
              <label>Category:</label>
              <input type="text" class="form-control" id="modal-product-category" readonly>
            </div>
            
            <!-- Checkout Details -->
            <div class="form-group">
              <label>Check Out By:</label>
              <input type="text" class="form-control" name="checkout-by" required>
            </div>
            <div class="form-group">
              <label>Check Out Date:</label>
              <input type="date" class="form-control" name="checkout-date" required>
            </div>
            <div class="form-group">
              <label>Quantity:</label>
              <input type="number" class="form-control" name="quantity" required>
            </div>
            <div class="form-group">
              <label>Due Back Date:</label>
              <input type="date" class="form-control" name="due-back-date">
            </div>
            <div class="form-group">
              <label>Comments:</label>
              <textarea class="form-control" name="comments"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function fillModal(id, name, category) {
      document.getElementById('modal-product-id').value = id;
      document.getElementById('modal-product-name').value = name;
      document.getElementById('modal-product-category').value = category;
    }
  </script>

<?php include_once('layouts/footer.php'); ?>

<!--
Category
Item
Picture 
Check in Item Barcode
Check Out By
Check Out Date
Due Back Date 
-->
