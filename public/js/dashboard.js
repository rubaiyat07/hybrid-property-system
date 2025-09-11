// dashboard.js - HybridEstate Admin Dashboard JavaScript

// Toggle dropdown menu
function toggleDropdown() {
    const dropdown = document.getElementById('dropdown');
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
window.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdown');
    const menuIcon = document.querySelector('.menu-icon');
    
    if (!menuIcon.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

// Loading overlay functions
function showLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
}

// Users Management Functions
function initUsersManagement() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('.users-table tbody tr');
            
            rows.forEach(row => {
                const userName = row.querySelector('.user-name').textContent.toLowerCase();
                const userEmail = row.querySelector('.user-email').textContent.toLowerCase();
                if (userName.includes(searchValue) || userEmail.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Filter by role
    const roleFilter = document.getElementById('roleFilter');
    if (roleFilter) {
        roleFilter.addEventListener('change', filterUsers);
    }

    // Filter by status
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', filterUsers);
    }

    // Initialize bulk actions
    initBulkActions();
}

function filterUsers() {
    const roleValue = document.getElementById('roleFilter')?.value || '';
    const statusValue = document.getElementById('statusFilter')?.value || '';
    const rows = document.querySelectorAll('.users-table tbody tr');
    
    rows.forEach(row => {
        if (row.style.display === 'none') return; // Skip already hidden rows
        
        const userRole = row.querySelector('.user-role')?.textContent.toLowerCase() || '';
        const userStatusElement = row.querySelector('.status-active, .status-banned');
        const userStatus = userStatusElement ? userStatusElement.textContent.toLowerCase() : '';
        
        const roleMatch = !roleValue || userRole === roleValue;
        const statusMatch = !statusValue || userStatus === statusValue;
        
        if (roleMatch && statusMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).classList.add('show');
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
    document.body.style.overflow = ''; // Re-enable scrolling
}

// User actions
let currentUserId = null;

function viewUser(userId) {
    showLoading();
    
    // Simulate API call - in real application, fetch user data from server
    setTimeout(() => {
        // This would be replaced with actual API call
        const user = {
            id: userId,
            name: 'User ' + userId,
            email: 'user' + userId + '@example.com',
            phone: '+1234567890',
            address: '123 Main St, City, State',
            role: 'Admin',
            status: 'Active',
            joined: '2023-01-15',
            last_login: '2023-10-25 14:30:45'
        };
        
        document.getElementById('userDetails').innerHTML = `
            <div class="user-detail-item">
                <div class="user-detail-label">ID:</div>
                <div class="user-detail-value">#${String(user.id).padStart(6, '0')}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Name:</div>
                <div class="user-detail-value">${user.name}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Email:</div>
                <div class="user-detail-value">${user.email}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Phone:</div>
                <div class="user-detail-value">${user.phone}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Address:</div>
                <div class="user-detail-value">${user.address}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Role:</div>
                <div class="user-detail-value">${user.role}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Status:</div>
                <div class="user-detail-value">${user.status}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Joined:</div>
                <div class="user-detail-value">${user.joined}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Last Login:</div>
                <div class="user-detail-value">${user.last_login}</div>
            </div>
        `;
        
        hideLoading();
        openModal('viewModal');
    }, 500);
}

function editUser(userId) {
    // Redirect to edit page
    window.location.href = `/admin/users/${userId}/edit`;
}

function banUser(userId) {
    currentUserId = userId;
    document.getElementById('banModalTitle').textContent = 'Confirm Ban';
    document.getElementById('banModalMessage').textContent = 'Are you sure you want to ban this user? They will not be able to access their account.';
    document.getElementById('confirmBan').textContent = 'Ban User';
    document.getElementById('confirmBan').onclick = confirmBanAction;
    openModal('banModal');
}

function unbanUser(userId) {
    currentUserId = userId;
    document.getElementById('banModalTitle').textContent = 'Confirm Unban';
    document.getElementById('banModalMessage').textContent = 'Are you sure you want to unban this user? They will regain access to their account.';
    document.getElementById('confirmBan').textContent = 'Unban User';
    document.getElementById('confirmBan').onclick = confirmUnbanAction;
    openModal('banModal');
}

function deleteUser(userId) {
    currentUserId = userId;
    openModal('deleteModal');
}

// Confirm actions
function confirmBanAction() {
    showLoading();
    
    // Simulate API call - in real application, send request to server
    setTimeout(() => {
        // Find the user row and update status
        const userRow = document.querySelector(`tr:has(input[value="${currentUserId}"])`);
        if (userRow) {
            const statusCell = userRow.querySelector('td:nth-child(5)');
            statusCell.innerHTML = '<span class="status-banned">Banned</span>';
            
            // Update ban button to unban
            const actionsCell = userRow.querySelector('td:nth-child(6)');
            const banButton = actionsCell.querySelector('.btn-ban');
            banButton.innerHTML = '<i class="fas fa-check"></i>';
            banButton.onclick = function() { unbanUser(currentUserId); };
        }
        
        hideLoading();
        closeModal('banModal');
        showNotification('User banned successfully.', 'success');
    }, 500);
}

function confirmUnbanAction() {
    showLoading();
    
    // Simulate API call - in real application, send request to server
    setTimeout(() => {
        // Find the user row and update status
        const userRow = document.querySelector(`tr:has(input[value="${currentUserId}"])`);
        if (userRow) {
            const statusCell = userRow.querySelector('td:nth-child(5)');
            statusCell.innerHTML = '<span class="status-active">Active</span>';
            
            // Update unban button to ban
            const actionsCell = userRow.querySelector('td:nth-child(6)');
            const banButton = actionsCell.querySelector('.btn-ban');
            banButton.innerHTML = '<i class="fas fa-ban"></i>';
            banButton.onclick = function() { banUser(currentUserId); };
        }
        
        hideLoading();
        closeModal('banModal');
        showNotification('User unbanned successfully.', 'success');
    }, 500);
}

document.getElementById('confirmDelete')?.addEventListener('click', function() {
    showLoading();
    
    // Simulate API call - in real application, send request to server
    setTimeout(() => {
        // Remove the user row from table
        const userRow = document.querySelector(`tr:has(input[value="${currentUserId}"])`);
        if (userRow) {
            userRow.remove();
        }
        
        hideLoading();
        closeModal('deleteModal');
        showNotification('User deleted successfully.', 'success');
        toggleBulkActions(); // Update bulk actions panel
    }, 500);
});

// Bulk selection functionality
function initBulkActions() {
    const bulkActionForm = document.getElementById('bulkActionForm');
    if (!bulkActionForm) return;
    
    // Add event listeners to all checkboxes
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });
}

function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    toggleBulkActions();
}

function toggleBulkActions() {
    const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
    const bulkActions = document.getElementById('bulkActions');
    
    if (selectedCount > 0 && bulkActions) {
        bulkActions.style.display = 'block';
        document.getElementById('selectedCount').textContent = selectedCount;
    } else if (bulkActions) {
        bulkActions.style.display = 'none';
    }
}

function submitBulkAction(action) {
    const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedUsers.length === 0) {
        showNotification('Please select at least one user.', 'error');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm('Are you sure you want to delete the selected users? This action cannot be undone.')) {
            return;
        }
    }
    
    const form = document.getElementById('bulkActionForm');
    document.getElementById('actionInput').value = action;
    document.getElementById('userIdsInput').value = JSON.stringify(selectedUsers);
    
    showLoading();
    form.submit();
}

// Notification system
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notification => {
        notification.remove();
    });
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `custom-notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize users management if on users page
    if (document.querySelector('.users-management')) {
        initUsersManagement();
    }
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal.show');
            modals.forEach(modal => {
                closeModal(modal.id);
            });
        }
    });
    
    // Add click outside to close modals
    document.addEventListener('click', function(e) {
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });
});

// Dashboard statistics and charts (placeholder for future implementation)
function initDashboardCharts() {
    // This would initialize charts using Chart.js or similar library
    console.log('Dashboard charts initialized');
}

// Responsive sidebar toggle for mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.admin-sidebar');
    const content = document.querySelector('.admin-content');
    
    if (sidebar && content) {
        sidebar.classList.toggle('mobile-hidden');
        content.classList.toggle('full-width');
    }
}

// Add CSS for notifications
const notificationStyles = `
<style>
.custom-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 8px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-width: 300px;
    max-width: 500px;
    z-index: 10000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease;
}

.custom-notification.success {
    background: #10b981;
}

.custom-notification.error {
    background: #ef4444;
}

.custom-notification.info {
    background: #3b82f6;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.notification-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    margin-left: 15px;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .custom-notification {
        min-width: auto;
        width: calc(100% - 40px);
        left: 20px;
        right: 20px;
    }
}
</style>
`;

// Inject notification styles
document.head.insertAdjacentHTML('beforeend', notificationStyles);