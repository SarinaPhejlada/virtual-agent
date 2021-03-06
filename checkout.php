<?php
    session_start();
    include('connect.php');
    include 'header.php';
    //ini_set('display_errors',1);
    $customer = $_SESSION['user_id'];
    $date = date("Y-m-d");
    $update = "Preparing for Shipment";
    if(isset($_POST['confirm'])){
        $sql = "INSERT INTO orders(customerid, order_status, date_purchased) VALUES($customer, '$update', DATE('$date'))";
        $rs = mysql_query($sql);
        if (!$rs) {
            echo "Could not execute query: $sql";
            trigger_error(mysql_error(), E_USER_ERROR); 
        }
        $sql = "TRUNCATE table shoppingcart";
        $rs = mysql_query($sql);
        if (!$rs) {
            echo "Could not execute query: $sql";
            trigger_error(mysql_error(), E_USER_ERROR); 
        }
    }
    $query = "Select shoppingcart.productid, shoppingcart.product_quantity, shoppingcart.product_price, inventory.name from shoppingcart, inventory where shoppingcart.productid = inventory.productid";
    $rs = mysql_query($query);
    if (!$rs) { 
        echo "Could not execute query: $query";
        trigger_error(mysql_error(), E_USER_ERROR); 
    }
    $x=0;
    $sum = 0;
    while ($row = mysql_fetch_assoc($rs)){
        $product_id[$x] = $row['productid'];
        $product_name[$x] = $row['name'];
        $product_quantity[$x] = $row['product_quantity'];
        $product_price[$x] = $row['product_price'];
        $sum = $sum + $product_price[$x];
        $x++;
    }
?>
<html>
    <head>
        <title>Checkout</title>
        <link rel="stylesheet" href="styles/checkout.css"/>
    </head>
    <body>
        <h1>Cart Checkout</h1>
        <form method = "post">
            <table>
                <caption style="font-size: 25px;">Items in Cart</caption>
                <tr>
                    <th>Product #ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
                    <?php
                        for($j=0; $j < count($product_id); $j++){
                            echo "<tr><td>".$product_id[$j]."</td><td>".$product_name[$j]."</td><td>".$product_quantity[$j]."</td><td>".$product_price[$j]."</td></tr>";
                        }
                    ?>
                <tr>
                    <td colspan="4" style="text-align: right"><span style="color: red" id = "discount"></span>Coupon Code<input type = "text"/><button onclick = "coupon()">Apply</button></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right"><?php echo "Total: $".$sum?></td>
                </tr>
            </table>
            <h2>Billing Information</h2>
            <div id = "bill_info">
                First Name<br>
                <input type = "text" id = "first_name" value="<?PHP echo $_SESSION['user_first']?>" required><br>
                Last Name<br>
                <input type = "text" id = "last_name" value="<?PHP echo $_SESSION['user_last']?>" required><br>
                Address<br>
                <input type = "text" id = "address1" value="<?PHP echo $_SESSION['user_adress']?>" required><br>
                <input type = "text" id = "address2"><br>
                City<br>
                <input type = "text" id = "city" required><br>
                State<br>
                <input type = "text" id = "state" max = "2" required><br>
                Country<br>
                <input type = "text" id = "country" required><br>
            </div>
            Phone Number<br>
            <input type = "number" id = "phone" required><br>
            <h2>Shipping Information</h2>
            <input onclick = "shipping()" type = "radio" name = "ship" id = "same" value = "same" checked>Ship to same Address<br>
            <input onclick = "shipping()" type = "radio" name = "ship" id = "dif" value = "different">Ship to different Address<br>
            <div id = "ship_info"></div>
            <h2>Payment Information</h2>
            Card Number<span id = result></span><br>
            <input onchange = "validate()" type = "number" id = "cardNum" size = "18" max = "9999999999999999" required><br>
            Security Code<br>
            <input type = "number" id = "security" size = "5" max = "999" required><br>
            Name on card<br>
            <input type = "text" id = "name" size = "15" required><br>
            Expiration<br>
            <input type = "month" id = "month" size = "4" max = "12" required><br>
            Email<br>
            <input type = "text" name = "email" required><br>
            <input type = "submit" name = "confirm" value = "Submit Order"> 
        </form>
        <script>
            function coupon(){
                document.getElementById("discount").innerHTML = "Invalid Promo Code";
            }
            function validate(){
                var num = document.getElementById("cardNum").value;
                var master = /^(5[1-5]|22([3-9][0-9]|2[1-9])|2([3-6][0-9]{2}|7([0-1][0-9]|20)))/g;
                var visa = /^4/g;
                var american = /^3[47]/g;
                var discover = /^6([45]|011)/g;
                if(master.test(num))
                    document.getElementById("result").innerHTML = "<img width = '40' style = 'margin: auto' src = 'src/master.png'/>";
                else if(visa.test(num))
                    document.getElementById("result").innerHTML = "<img width = '40' style = 'margin: auto' src = 'src/visa.png'/>";
                else if(american.test(num))
                    document.getElementById("result").innerHTML = "<img width = '40' style = 'margin: auto' src = 'src/american.png'/>";
                else if(discover.test(num))
                    document.getElementById("result").innerHTML = "<img width = '40' style = 'margin: auto' src = 'src/discover.png'/>";
                else
                    document.getElementById("result").innerHTML = "";
            }
            function shipping(){
                var checked = document.getElementById("dif").checked;
                var info = document.getElementById("bill_info").innerHTML;
                if(checked == true){
                    document.getElementById("ship_info").innerHTML = info;
                }
                else{
                    document.getElementById("ship_info").innerHTML = "";
                }
            }
        </script>
    </body>
</html>
