// ========== NAVIGATION ACTIVE STATE (Without blocking navigation) ==========
// This only updates the active class without preventing navigation
document.querySelectorAll('.nav-link').forEach(function(link) {
    link.addEventListener('click', function() {
        // Remove active class from all links
        document.querySelectorAll('.nav-link').forEach(function(l) {
            l.classList.remove('active');
        });
        // Add active class to clicked link
        this.classList.add('active');
        // Let the browser navigate normally (no e.preventDefault())
    });
});

// ========== BOOKING FUNCTION ==========
function bookCaregiver(btn) {
    if (btn.classList.contains('booked')) return;
    btn.textContent = 'Booked';
    btn.classList.add('booked');
    btn.disabled = true;
}

// ========== PHOTO UPLOAD FUNCTIONS ==========
function triggerUpload(placeholder) {
    placeholder.querySelector('input[type="file"]').click();
}

function previewPhoto(input) {
    var file = input.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var p = input.closest('.photo-placeholder');
        p.innerHTML = '<img src="' + e.target.result + '" alt="photo"/>';
    };
    reader.readAsDataURL(file);
}

// ========== SEARCH FUNCTION ==========
function searchCaregivers() {
    console.log('Search:', document.getElementById('city').value,
        document.getElementById('max_price').value,
        document.getElementById('availability').value);
}

// ========== SET ACTIVE NAV LINK BASED ON CURRENT PAGE ==========
// This runs when the page loads to highlight the current page
document.addEventListener('DOMContentLoaded', function() {
    var currentPage = window.location.pathname.split('/').pop();
    
    // If currentPage is empty or index.php, set to 'index.php' for comparison
    if (currentPage === '' || currentPage === 'Find_Caregivers') {
        currentPage = 'index.php';
    }
    
    document.querySelectorAll('.nav-link').forEach(function(link) {
        var href = link.getAttribute('href');
        
        // Check if the link's href contains the current page name
        if (href && href.includes(currentPage)) {
            link.classList.add('active');
        }
        
        // Special case for Home page
        if (currentPage === 'index.php' && link.textContent === 'Home') {
            link.classList.add('active');
        }
        
        // Special case for Find Caregivers page
        if (currentPage === 'index.php' && link.textContent === 'Find Caregivers') {
            link.classList.add('active');
        }
    });
});