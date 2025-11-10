// Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initSalesChart();
    
    // Sidebar toggle for mobile
    initSidebar();
    
    // Update dashboard stats
    updateDashboardStats();
});

// Sales Chart
function initSalesChart() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Sales (₹)',
                data: [65000, 79000, 85000, 95000, 100000, 115000, 124500],
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Sidebar functionality
function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.createElement('button');
    
    sidebarToggle.className = 'btn btn-primary d-md-none';
    sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
    sidebarToggle.style.position = 'fixed';
    sidebarToggle.style.bottom = '20px';
    sidebarToggle.style.right = '20px';
    sidebarToggle.style.zIndex = '1000';
    
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('show');
    });
    
    document.body.appendChild(sidebarToggle);
}

// Update dashboard stats (simulated)
function updateDashboardStats() {
    // This would typically make an AJAX call to get real data
    console.log('Updating dashboard stats...');
    
    // Simulate real-time updates
    setInterval(() => {
        // Update random stats for demo
        const salesElement = document.querySelector('.card:first-child .h5');
        if (salesElement) {
            const currentSales = parseInt(salesElement.textContent.replace('₹', '').replace(',', ''));
            const randomIncrement = Math.floor(Math.random() * 1000);
            salesElement.textContent = '₹' + (currentSales + randomIncrement).toLocaleString();
        }
    }, 10000); // Update every 10 seconds
}

// Logout confirmation
function confirmLogout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = '/smart_core_erp/logout';
    }
}