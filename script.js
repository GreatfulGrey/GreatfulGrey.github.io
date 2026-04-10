// Wait for the HTML to fully load
document.addEventListener("DOMContentLoaded", () => {
    
    // 1. DEFINE SHARED VARIABLES FIRST
    const viewSections = document.querySelectorAll('.view-section');
    const sidebarItems = document.querySelectorAll('.sidebar li');
    const toggle = document.getElementById('IOswitch');
    const title = document.getElementById('IOstatus');


    // ==========================================
    // 2. SIDEBAR NAVIGATION LOGIC
    // ==========================================
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

    // Initialize the first tab
    if (sidebarItems.length > 0) {
        sidebarItems[0].classList.add('active');
    }

    // ==========================================
    // 3. TABLE CLICK NAVIGATION LOGIC
    // ==========================================
    const clickableRows = document.querySelectorAll('.clickable-row');

    clickableRows.forEach(row => {
        row.addEventListener('click', () => {
            const targetId = row.getAttribute('data-target');

            // Failsafe: if there is no target, do nothing
            if (!targetId) return;

            // Hide all views
            viewSections.forEach(section => {
                section.classList.add('hidden');
            });

            // Show the details view
            const targetView = document.getElementById(targetId);
            if (targetView) {
                targetView.classList.remove('hidden');
            }
        });
    });

    
    // ==========================================
    // UPGRADED DYNAMIC BACK BUTTON LOGIC
    // ==========================================
    const backButtons = document.querySelectorAll('.back-btn');

    backButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Find out where this specific button wants to send us
            const returnTarget = btn.getAttribute('data-return-to');

            // Hide all current views
            viewSections.forEach(section => {
                section.classList.add('hidden');
            });

            // Show the correct return view
            const targetView = document.getElementById(returnTarget);
            if (targetView) {
                targetView.classList.remove('hidden');
            }
        });
    });

    // ==========================================
    // 4. CHART.JS INITIALIZATION
    // ==========================================
    const canvasElement = document.getElementById('utilizationDoughnut');
    // Ensure the canvas actually exists before trying to draw on it
    if (canvasElement) {
        const ctx = canvasElement.getContext('2d');
        const utilizationDoughnut = new Chart(ctx, {
            type: 'doughnut', 
            data: {
                labels: ['Ex1', 'Ex2', 'Ex3', 'Ex4', 'Ex5'],
                datasets: [{
                    label: 'Sales Breakdown',
                    data: [300, 150, 200, 100, 50],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ],
                    borderColor: '#ffffff', 
                    borderWidth: 2,
                    hoverOffset: 6 
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom', 
                    },
                    title: {
                        display: true,
                        text: 'Warehouse Utilization',
                        font: {
                            size: 18,
                            color: '#ffffff' 
                        }
                    }
                },
                cutout: '60%' 
            }
        });
    }

    // ==========================================
    // 5. SHIPMENT TOGGLE LOGIC
    // ==========================================
    // Make sure elements exist before adding listeners
    if (toggle && title) {
        toggle.addEventListener('change', async function() {
            // 1. Update the UI instantly
            if (this.checked) {
                title.textContent = 'Incoming Shipments'; 
                title.style.color = '#4c9aff';
            } else {
                title.textContent = 'Outgoing Shipments'; 
                title.style.color = '';
            }

            // 2. Send the update to your server
            try {
                // Adjust the API endpoint below to match your actual backend route
                const response = await fetch('/api/update-shipment-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        shipmentsActive: this.checked 
                    })
                });

                if (!response.ok) {
                    console.error('Failed to update database');
                }
            } catch (error) {
                console.error('Error connecting to the server:', error);
            }
        });
    }

    populateTable('shipments-table');
});

// ==========================================
// OUTSIDE LOGIC (Functions)
// ==========================================
async function populateTable(tableId, apiUrl = 'api.php') {
    try {
        const response = await fetch(apiUrl);
        const data = await response.json();
        const tableBody = document.querySelector(`#${tableId} tbody`);
        
        if (!tableBody) return;
        
        tableBody.innerHTML = ''; 

        if (!data || data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="100%" style="text-align: center;">No records found.</td></tr>';
            return;
        }

        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = Object.values(item)
                .map(val => `<td>${val !== null && val !== undefined ? val : ''}</td>`)
                .join('');
            tableBody.appendChild(row);
        });
    } catch (error) {
        console.error("Error fetching table data:", error);
    }
}

async function populateBox(boxId, apiUrl = 'api.php') {
    try {
        const response = await fetch(apiUrl);
        const data = await response.json();
        
        const boxBody = document.getElementById(boxId); 

        if (!data || !boxBody) {
            return;
        }

        boxBody.textContent = data.message || "0";

    } catch (error) {
        console.error(`Error fetching data for ${boxId}:`, error);
    }
}
