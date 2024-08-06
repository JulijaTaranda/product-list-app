<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="add-product-page">
        <header class="header">
            <h1>Add Product</h1>
            <div class="header-buttons">
                <button id="save-button" class="btn-primary">Save</button>
                <a href="index.php" class="btn-danger">Back</a>
            </div>
        </header>
        <form method="POST" id="product_form" class="product-form">
            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" id="sku" name="sku" value="<?= htmlspecialchars($sku); ?>" required>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($price); ?>" required>
            </div>
            <div class="form-group">
                <label for="productType">Type</label>
                <select id="productType" name="type" required>
                    <option value="">Select type</option>
                    <option value="Book" <?= ($type === 'Book') ? 'selected' : ''; ?>>Book</option>
                    <option value="DVD" <?= ($type === 'DVD') ? 'selected' : ''; ?>>DVD</option>
                    <option value="Furniture" <?= ($type === 'Furniture') ? 'selected' : ''; ?>>Furniture</option>
                </select>
            </div>
            <!--Dynamic fields-->
            <div id="dynamic-fields">
                <!--Book-->
                <div id="book-fields" class="dynamic-fields" style="display: none;">
                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <div>
                            <input type="number" id="weight" name="weight" step="0.01" min="0" value="<?= htmlspecialchars($weight); ?>" required>
                            <p class="type-description">*Please, provide weight.</p>
                        </div>
                    </div>
                </div>
                <!--DVD-->
                <div id="dvd-fields" class="dynamic-fields" style="display: none;">
                    <div class="form-group">
                        <label for="size">Size (MB)</label>
                        <div>
                            <input type="number" id="size" name="size" step="0.01" min="0" value="<?= htmlspecialchars($size); ?>" required>
                            <p class="type-description">*Please, provide size.</p>
                        </div>
                    </div>
                </div>
                <!--Furniture-->
                <div id="furniture-fields" class="dynamic-fields" style="display: none;">
                    <div class="form-group">
                        <label for="height">Height (cm)</label>
                        <input type="number" id="height" name="height" step="0.01" min="0" value="<?= htmlspecialchars($height); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="width">Width (cm)</label>
                        <input type="number" id="width" name="width" step="0.01" min="0" value="<?= htmlspecialchars($width); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="length">Length (cm)</label>
                        <div>
                            <input type="number" id="length" name="length" step="0.01" min="0" value="<?= htmlspecialchars($length); ?>" required>
                            <p class="type-description">*Please, provide dimensions.</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!empty($errorMessage)): ?>
            <p style="color: red;"><?= htmlspecialchars($errorMessage); ?></p>
            <?php endif; ?>
        </form>
        <footer class="footer">Scandiweb Test assignment</footer>
    </div>
    <script>
        document.getElementById('productType').addEventListener('change', function () {
            const type = this.value;
            const dynamicFields = document.getElementById('dynamic-fields');
            document.querySelectorAll('.dynamic-fields').forEach(group => {
                group.style.display = 'none';
            });
            if (type) {
                document.getElementById(type.toLowerCase() + '-fields').style.display = 'flex';
            }
        });
        document.getElementById('productType').dispatchEvent(new Event('change'));
        document.getElementById('save-button').addEventListener('click', function () {
            document.getElementById('product_form').submit();
        });
    </script>
</body>
</html>
