document.addEventListener('DOMContentLoaded', function() {
    const castRoleContainer = document.getElementById('castRoleContainer');
    const addCastRoleBtn = document.getElementById('addCastRole');

    // Add new cast-role pair
    addCastRoleBtn.addEventListener('click', function() {
        const firstPair = castRoleContainer.querySelector('.cast-role-pair');
        const newPair = firstPair.cloneNode(true);
        
        // Clear selected values
        newPair.querySelectorAll('select').forEach(select => {
            select.value = '';
        });
        
        castRoleContainer.appendChild(newPair);
    });

    // Remove cast-role pair
    castRoleContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-cast-role')) {
            const pairs = castRoleContainer.querySelectorAll('.cast-role-pair');
            if (pairs.length > 1) {
                e.target.closest('.cast-role-pair').remove();
            } else {
                alert('At least one cast member is required.');
            }
        }
    });

    // Form validation
    const form = document.getElementById('addContentForm');
    form.addEventListener('submit', function(e) {
        const castSelects = form.querySelectorAll('.cast-select');
        const roleSelects = form.querySelectorAll('.role-select');
        let isValid = true;

        castSelects.forEach((select, index) => {
            if (!select.value || !roleSelects[index].value) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please select both cast member and role for all entries.');
        }
    });
}); 