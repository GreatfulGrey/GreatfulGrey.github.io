document.addEventListener("DOMContentLoaded", () => {
    
    const sidebarItems = document.querySelectorAll('.sidebar li');
    const viewSections = document.querySelectorAll('.view-section');

    sidebarItems.forEach(item => {
        item.addEventListener('click', () => {
            
            const targetId = item.getAttribute('data-target');

            viewSections.forEach(section => {
                section.classList.add('hidden');
            });

            sidebarItems.forEach(li => li.classList.remove('active'));

            const targetView = document.getElementById(targetId);
            if (targetView) {
                targetView.classList.remove('hidden');
            }
            item.classList.add('active');
        });
    });
    if (sidebarItems.length > 0) {
        sidebarItems[0].classList.add('active');
    }
});