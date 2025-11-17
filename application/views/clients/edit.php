<!-- Page Heading -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Client</h1>
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
                <h6 class="m-0 font-weight-bold text-primary">Edit Client Information</h6>
            </div>
            <div class="card-body">
                <form id="clientForm" action="/smart_core_erp/clients/process_edit/<?php echo $client->id; ?>"
                    method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Client Code</label>
                                <input type="text" class="form-control" value="<?php echo $client->client_code; ?>"
                                    readonly>
                                <small class="form-text text-muted">Client code cannot be changed</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="company_name" class="form-label">Company Name *</label>
                                <input type="text" class="form-control" id="company_name" name="company_name"
                                    value="<?php echo $client->company_name; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="contact_person" class="form-label">Contact Person *</label>
                                <input type="text" class="form-control" id="contact_person" name="contact_person"
                                    value="<?php echo $client->contact_person; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo $client->email; ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Rest of the form similar to add.php but with values -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="<?php echo $client->phone; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" id="mobile" name="mobile"
                                    value="<?php echo $client->mobile; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address"
                            rows="3"><?php echo $client->address; ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    value="<?php echo $client->city; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state"
                                    value="<?php echo $client->state; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="pin_code" class="form-label">PIN Code</label>
                                <input type="text" class="form-control" id="pin_code" name="pin_code"
                                    value="<?php echo $client->pin_code; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country"
                                    value="<?php echo $client->country; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="gstin" class="form-label">GSTIN</label>
                                <input type="text" class="form-control" id="gstin" name="gstin"
                                    value="<?php echo $client->gstin; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="pan_number" class="form-label">PAN Number</label>
                                <input type="text" class="form-control" id="pan_number" name="pan_number"
                                    value="<?php echo $client->pan_number; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="credit_limit" class="form-label">Credit Limit (₹)</label>
                                <input type="number" class="form-control" id="credit_limit" name="credit_limit"
                                    value="<?php echo $client->credit_limit; ?>" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="active" <?php echo $client->status == 'active' ? 'selected' : ''; ?>>Active
                            </option>
                            <option value="inactive" <?php echo $client->status == 'inactive' ? 'selected' : ''; ?>>
                                Inactive</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Client
                        </button>
                        <a href="/smart_core_erp/clients" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>