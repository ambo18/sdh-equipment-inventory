<?php
require_once('includes/load.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = (int)$_POST['product-id'];
    $checkout_by = remove_junk($db->escape($_POST['checkout-by']));
    $checkout_date = remove_junk($db->escape($_POST['checkout-date']));
    $quantity = (int)$_POST['quantity'];
    $due_back_date = remove_junk($db->escape($_POST['due-back-date']));
    $comments = remove_junk($db->escape($_POST['comments']));

    // Fetch the current quantity of the product from the products table
    $result = $db->query("SELECT quantity FROM products WHERE id = '{$product_id}'");
    if ($result && $db->num_rows($result) > 0) {
        $product = $db->fetch_assoc($result);
        $current_quantity = (int)$product['quantity'];

        // Check if there is enough stock to check out the desired quantity
        if ($current_quantity >= $quantity) {
            // Calculate the new quantity after checkout
            $new_quantity = $current_quantity - $quantity;

            // Insert into the check_out table
            $query = "INSERT INTO check_out (item_id, checkout_by, checkout_date, quantity, due_back_date, comments)";
            $query .= " VALUES ('{$product_id}', '{$checkout_by}', '{$checkout_date}', '{$quantity}', '{$due_back_date}', '{$comments}')";
            
            if ($db->query($query)) {
                // Update the quantity in the products table (no action update here)
                $update_quantity_query = "UPDATE products SET quantity = '{$new_quantity}' WHERE id = '{$product_id}'";
                if ($db->query($update_quantity_query)) {
                    $session->msg('s', "Item checked out successfully.");
                    redirect('check_out.php');
                } else {
                    $session->msg('d', 'Failed to update product quantity.');
                    redirect('check_out.php');
                }
            } else {
                $session->msg('d', 'Failed to check out item.');
                redirect('check_out.php');
            }
        } else {
            // Not enough stock
            $session->msg('d', 'Not enough stock to check out the desired quantity.');
            redirect('check_out.php');
        }
    } else {
        $session->msg('d', 'Product not found.');
        redirect('check_out.php');
    }
}
?>
