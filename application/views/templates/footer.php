            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start mt-4">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © <?php echo date('Y'); ?> Smart-Core ERP. All rights reserved.
            <span class="float-end">
                <small>Version 1.0</small>
            </span>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo isset($base_url) ? $base_url : '/smart_core_erp/'; ?>assets/js/dashboard.js"></script>
    <script src="<?php echo isset($base_url) ? $base_url : '/smart_core_erp/'; ?>assets/js/custom.js"></script>
    
    <!-- Additional page-specific scripts -->
    <?php if(isset($additional_scripts)): ?>
        <?php foreach($additional_scripts as $script): ?>
            <script src="<?php echo isset($base_url) ? $base_url : '/smart_core_erp/'; ?><?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Inline scripts -->
    <?php if(isset($inline_scripts)): ?>
        <script>
            <?php echo $inline_scripts; ?>
        </script>
    <?php endif; ?>
</body>
</html>