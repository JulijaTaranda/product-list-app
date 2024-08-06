<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="homepage">
        <header class="header">
            <h1>Product List</h1>
            <div class="header-buttons">
                <a href="add_product.php" class="btn-primary">ADD</a>
                <button type="button" id="delete-product-btn" class="btn-danger">MASS DELETE</button>
            </div>
        </header>
        <form id="product-form" method="POST">
            <!--Product list-->
            <div class="product-list">
                <?php foreach ($products as $product): ?>

                <!--Product box-->
                <div class="product-item">
                    <input class="delete-checkbox" type="checkbox" name="product_ids[]"
                        value="<?= htmlspecialchars($product['id']); ?>">
                    <p><?= htmlspecialchars($product['sku']); ?></p>
                    <p><?= htmlspecialchars($product['name']); ?></p>
                    <p><?= htmlspecialchars($product['price']); ?> $</p>

                    <!-- Specific information for type -->
                    <?php if ($product['type'] === 'Book'): ?>
                    <p>Weight: <?= htmlspecialchars($product['specific_info']); ?> KG</p>
                    <?php elseif ($product['type'] === 'DVD'): ?>
                    <p>Size: <?= htmlspecialchars($product['specific_info']); ?> MB</p>
                    <?php elseif ($product['type'] === 'Furniture'): ?>
                    <p>Dimension: <?= htmlspecialchars($product['specific_info']); ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- Hidden form for deleting -->
            <input type="hidden" name="delete_products" id="delete-products-field">
        </form>
        <footer class="footer">
            Scandiweb Test assignment
        </footer>
    </div>
    <script>
        document.getElementById('delete-product-btn').addEventListener('click', function () {
            var form = document.getElementById('product-form');
            var deleteField = document.getElementById('delete-products-field');

            // Set the field value to indicate that a delete request was submitted
            deleteField.value = '1';

            form.submit();
        });
    </script>
</body>
</html>
