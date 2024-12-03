// Tooltip positioning
document.addEventListener('DOMContentLoaded', function() {
    const tooltips = document.querySelectorAll('.tooltip');
    tooltips.forEach(function(tooltip) {
        const content = tooltip.querySelector('.tooltip-content');
        tooltip.addEventListener('mouseenter', function() {
            const bounding = tooltip.getBoundingClientRect();
            const windowWidth = window.innerWidth;
            if (bounding.right > windowWidth / 2) {
                content.style.transform = 'translateX(-100%)';
            } else {
                content.style.transform = 'translateX(0)';
            }
            content.style.display = 'block';
        });
        tooltip.addEventListener('mouseleave', function() {
            content.style.display = 'none';
        });
    });
});
