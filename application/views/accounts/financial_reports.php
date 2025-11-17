<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Financial Reports</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<!-- Report Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/smart_core_erp/accounts/financial_reports">
            <div class="row">
                <div class="col-md-3">
                    <label for="report_type" class="form-label">Report Type</label>
                    <select class="form-select" id="report_type" name="report_type" onchange="this.form.submit()">
                        <option value="profit_loss" <?php echo $report_type == 'profit_loss' ? 'selected' : ''; ?>>
                            Profit & Loss
                        </option>
                        <option value="balance_sheet" <?php echo $report_type == 'balance_sheet' ? 'selected' : ''; ?>>
                            Balance
                            Sheet</option>
                        <option value="cash_flow" <?php echo $report_type == 'cash_flow' ? 'selected' : ''; ?>>Cash Flow
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="<?php echo $start_date; ?>"
                        <?php echo $report_type == 'balance_sheet' ? 'disabled' : ''; ?>>
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="<?php echo $end_date; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Report Content -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <?php
                            switch($report_type) {
                                case 'profit_loss': echo 'Profit & Loss Statement'; break;
                                case 'balance_sheet': echo 'Balance Sheet'; break;
                                case 'cash_flow': echo 'Cash Flow Statement'; break;
                            }
                            ?>
            <small class="text-muted">
                (<?php echo date('M d, Y', strtotime($start_date)); ?> to
                <?php echo date('M d, Y', strtotime($end_date)); ?>)
            </small>
        </h5>
    </div>
    <div class="card-body">
        <?php if ($report_type == 'profit_loss'): ?>
        <!-- Profit & Loss Report -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Revenue</th>
                        <th class="text-end">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($report_data['revenues'])): ?>
                    <?php foreach ($report_data['revenues'] as $revenue): ?>
                    <tr>
                        <td><?php echo $revenue->account_name; ?></td>
                        <td class="text-end text-success">
                            <?php echo number_format($revenue->amount, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <tr class="table-light">
                        <td><strong>Total Revenue</strong></td>
                        <td class="text-end">
                            <strong><?php echo number_format($report_data['total_revenue'], 2); ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered mt-4">
                <thead class="table-light">
                    <tr>
                        <th>Expenses</th>
                        <th class="text-end">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($report_data['expenses'])): ?>
                    <?php foreach ($report_data['expenses'] as $expense): ?>
                    <tr>
                        <td><?php echo $expense->account_name; ?></td>
                        <td class="text-end text-danger">
                            <?php echo number_format($expense->amount, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <tr class="table-light">
                        <td><strong>Total Expenses</strong></td>
                        <td class="text-end">
                            <strong><?php echo number_format($report_data['total_expenses'], 2); ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered mt-4">
                <tbody>
                    <tr class="table-primary">
                        <td><strong>Net Income</strong></td>
                        <td class="text-end"><strong
                                class="<?php echo $report_data['net_income'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                ₹<?php echo number_format($report_data['net_income'], 2); ?>
                            </strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php elseif ($report_type == 'balance_sheet'): ?>
        <!-- Balance Sheet Report -->
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th colspan="2">Assets</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($report_data['assets'])): ?>
                        <?php foreach ($report_data['assets'] as $asset): ?>
                        <tr>
                            <td><?php echo $asset->account_name; ?></td>
                            <td class="text-end"><?php echo number_format($asset->balance, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <tr class="table-light">
                            <td><strong>Total Assets</strong></td>
                            <td class="text-end">
                                <strong>₹<?php echo number_format($report_data['total_assets'], 2); ?></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th colspan="2">Liabilities & Equity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2"><strong>Liabilities</strong></td>
                        </tr>
                        <?php if (!empty($report_data['liabilities'])): ?>
                        <?php foreach ($report_data['liabilities'] as $liability): ?>
                        <tr>
                            <td><?php echo $liability->account_name; ?></td>
                            <td class="text-end"><?php echo number_format($liability->balance, 2); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <tr class="table-light">
                            <td><strong>Total Liabilities</strong></td>
                            <td class="text-end">
                                <strong>₹<?php echo number_format($report_data['total_liabilities'], 2); ?></strong>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2"><strong>Equity</strong></td>
                        </tr>
                        <?php if (!empty($report_data['equity'])): ?>
                        <?php foreach ($report_data['equity'] as $equity): ?>
                        <tr>
                            <td><?php echo $equity->account_name; ?></td>
                            <td class="text-end"><?php echo number_format($equity->balance, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <tr class="table-light">
                            <td><strong>Total Equity</strong></td>
                            <td class="text-end">
                                <strong>₹<?php echo number_format($report_data['total_equity'], 2); ?></strong>
                            </td>
                        </tr>

                        <tr class="table-primary">
                            <td><strong>Total Liabilities & Equity</strong></td>
                            <td class="text-end">
                                <strong>₹<?php echo number_format($report_data['liabilities_equity'], 2); ?></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php elseif ($report_type == 'cash_flow'): ?>
        <!-- Cash Flow Report -->
        <div class="table-responsive">
            <?php if (!empty($report_data['operating'])): ?>
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th colspan="2">Operating Activities</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['operating'] as $item): ?>
                    <tr>
                        <td><?php echo $item->account_name; ?></td>
                        <td class="text-end <?php echo $item->amount >= 0 ? 'text-success' : 'text-danger'; ?>">
                            <?php echo number_format($item->amount, 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>

            <?php if (!empty($report_data['investing'])): ?>
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th colspan="2">Investing Activities</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['investing'] as $item): ?>
                    <tr>
                        <td><?php echo $item->account_name; ?></td>
                        <td class="text-end <?php echo $item->amount >= 0 ? 'text-success' : 'text-danger'; ?>">
                            <?php echo number_format($item->amount, 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>

            <?php if (!empty($report_data['financing'])): ?>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th colspan="2">Financing Activities</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['financing'] as $item): ?>
                    <tr>
                        <td><?php echo $item->account_name; ?></td>
                        <td class="text-end <?php echo $item->amount >= 0 ? 'text-success' : 'text-danger'; ?>">
                            <?php echo number_format($item->amount, 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Disable start date for balance sheet
document.getElementById('report_type').addEventListener('change', function() {
    const startDate = document.getElementById('start_date');
    if (this.value === 'balance_sheet') {
        startDate.disabled = true;
    } else {
        startDate.disabled = false;
    }
});
</script>