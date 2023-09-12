
<div class="container">
<table class="table table-striped">
    <thead>
        <tr>
            <th>No.</th>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Product Description</th>
            <th>Unit Price</th>
        </tr>
    </thead>
    <?php
        include_once "./dbconnect.php";
        $ID= $_GET['idtransaction'];
        //echo $ID;
        $sql="SELECT * from transaction,product
        where itemID=idproduct and
        idtransaction = $ID";
        $result=$conn-> query($sql);
        $count=1;
        if ($result-> num_rows > 0){
            while ($row=$result-> fetch_assoc()) {
    ?>
                <tr>
                    <td><?=$row["idproduct"]?></td>
                    <td><img height="80px" src="<?=$row["image"]?>" alt="product_image"></td>
                    <td><?=$row["itemname"] ?></td>
                    <td><?=$row["description"] ?></td>
                    <td><?=$row["price"]?></td>
                </tr>
    <?php
               
            }
        }else{
            echo "error";
        }
    ?>
</table>
</div>
