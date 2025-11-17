<!-- Page Heading -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Client</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/clients" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Clients
        </a>
    </div>
</div>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $this->session->flashdata('error'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Client Information</h6>
            </div>
            <div class="card-body">
                <form id="clientForm" action="/smart_core_erp/clients/process_add" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="company_name" class="form-label">Company Name *</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="contact_person" class="form-label">Contact Person *</label>
                                <input type="text" class="form-control" id="contact_person" name="contact_person"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" id="mobile" name="mobile">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="gstin" class="form-label">GSTIN</label>
                                <input type="text" class="form-control" id="gstin" name="gstin">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="pin_code" class="form-label">PIN Code</label>
                                <input type="text" class="form-control" id="pin_code" name="pin_code">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="India">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="pan_number" class="form-label">PAN Number</label>
                                <input type="text" class="form-control" id="pan_number" name="pan_number">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="credit_limit" class="form-label">Credit Limit (₹)</label>
                                <input type="number" class="form-control" id="credit_limit" name="credit_limit"
                                    value="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Client
                        </button>
                        <a href="/smart_core_erp/clients" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Tips</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="fas fa-info-circle text-primary"></i> Required fields are marked with *</li>
                    <li><i class="fas fa-info-circle text-primary"></i> Client code will be generated automatically</li>
                    <li><i class="fas fa-info-circle text-primary"></i> Keep client information up to date</li>
                    <li><i class="fas fa-info-circle text-primary"></i> Set appropriate credit limits</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    $('#clientForm').on('submit', function (e) {
        let valid = true;

        // Basic validation for required fields
        $(this).find('[required]').each(function () {
            if (!$(this).val().trim()) {
                valid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>
