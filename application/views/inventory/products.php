<style>
.low-stock {
    background-color: #fff3cd;
}

.out-of-stock {
    background-color: #f8d7da;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, .075);
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Products</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fas fa-plus"></i> Add Product
        </button>
    </div>
</div>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $this->session->flashdata('success'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $this->session->flashdata('error'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Products Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Products</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Stock Value</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                    <tr class="<?php 
                                            echo $product->current_stock == 0 ? 'out-of-stock' : 
                                                 ($product->current_stock <= $product->reorder_level ? 'low-stock' : ''); 
                                        ?>">
                        <td><strong><?php echo $product->product_code; ?></strong></td>
                        <td>
                            <?php echo $product->product_name; ?>
                            <?php if ($product->description): ?>
                            <br><small class="text-muted"><?php echo $product->description; ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $product->category_name ?: 'N/A'; ?></td>
                        <td>
                            <strong><?php echo $product->current_stock; ?></strong>
                            <small class="text-muted"><?php echo $product->unit_of_measure; ?></small>
                            <?php if ($product->reorder_level > 0): ?>
                            <br><small class="text-muted">Reorder:
                                <?php echo $product->reorder_level; ?></small>
                            <?php endif; ?>
                        </td>
                        <td>₹<?php echo number_format($product->cost_price, 2); ?></td>
                        <td>₹<?php echo number_format($product->selling_price, 2); ?></td>
                        <td>₹<?php echo number_format($product->current_stock * $product->cost_price, 2); ?>
                        </td>
                        <td>
                            <?php if ($product->current_stock == 0): ?>
                            <span class="badge bg-danger">Out of Stock</span>
                            <?php elseif ($product->current_stock <= $product->reorder_level): ?>
                            <span class="badge bg-warning">Low Stock</span>
                            <?php else: ?>
                            <span class="badge bg-success">In Stock</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">No products found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>
</div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/smart_core_erp/inventory/add_product" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_code" class="form-label">Product Code *</label>
                                <input type="text" class="form-control" id="product_code" name="product_code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>">
                                        <?php echo $category->category_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Supplier</label>
                                <select class="form-select" id="supplier_id" name="supplier_id">
                                    <option value="">Select Supplier</option>
                                    <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?php echo $supplier->id; ?>">
                                        <?php echo $supplier->supplier_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cost_price" class="form-label">Cost Price (₹) *</label>
                                <input type="number" class="form-control" id="cost_price" name="cost_price" step="0.01"
                                    min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="selling_price" class="form-label">Selling Price (₹) *</label>
                                <input type="number" class="form-control" id="selling_price" name="selling_price"
                                    step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="unit_of_measure" class="form-label">Unit of Measure</label>
                                <input type="text" class="form-control" id="unit_of_measure" name="unit_of_measure"
                                    value="PCS" placeholder="e.g., PCS, KG, LTR">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="initial_stock" class="form-label">Initial Stock</label>
                                <input type="number" class="form-control" id="initial_stock" name="initial_stock"
                                    min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reorder_level" class="form-label">Reorder Level</label>
                                <input type="number" class="form-control" id="reorder_level" name="reorder_level"
                                    min="0" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>