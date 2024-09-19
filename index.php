<?php
include 'dbconnection.php';
include 'header.php';
?>




<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card-body">

         <table class="table" id="mytable">

         <th>id</th>
         <th>image</th>
              <th>Product Name & Size</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Size</th>
              <th>Description</th>
              <th>Color</th>
              <th>Action</th>
      </tr>
    </thead> 
      
<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ecommerce_adim");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching product list
$sql = "SELECT id, image, product_name, size, product_category, color, description, price, stock_value FROM tbl_product";
$result = $conn->query($sql);
?>

<table class="table" id="mytable">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<li><a href='product_details.php?id=" . $row['id'] . "'>
           " . $row['image'] . "
                ". $row['product_name'] . "</a>
                " . $row['product_category'] . "
                " . $row['price'] . "
                " . $row['stock_value'] . "
                " . $row['size'] . "
                " . $row['description'] . "
                " . $row['color'] . "</li>";
            }
        } else {
            echo "No products found";
        }
        ?>
        </table>
</body>
</html>

<?php
$conn->close();
?>
