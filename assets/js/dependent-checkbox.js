document.addEventListener('DOMContentLoaded', function() {
    const globalRedirectCheckbox = document.querySelector('.global-switch');
    const dependentCheckboxes = document.querySelectorAll('.dependent-checkbox');

    if (!globalRedirectCheckbox || dependentCheckboxes.length === 0) {
        return;
    }
    function toggleDependentCheckboxes() {
        const isGlobalEnabled = globalRedirectCheckbox.checked;
        
        dependentCheckboxes.forEach(function(checkbox) {
            if (isGlobalEnabled) {
                checkbox.disabled = false;
                checkbox.parentElement.classList.remove('switch-disable');
            } else {
                checkbox.disabled = true;
                checkbox.checked = false; 
                checkbox.parentElement.classList.add('switch-disable');
            }
        });
    }
    toggleDependentCheckboxes();
    globalRedirectCheckbox.addEventListener('change', toggleDependentCheckboxes);
});