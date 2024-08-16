document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on menu button click
    document.getElementById('menu-toggle').addEventListener('click', function(e) {
        e.preventDefault();
        var sidebar = document.getElementById('sidebar');
        var contentWrapper = document.getElementById('content-wrapper');

        // Toggle the active class on both the sidebar and content-wrapper
        sidebar.classList.toggle('active');
        contentWrapper.classList.toggle('active');
    });

    // Load content for each section when the sidebar link is clicked
    var sidebarLinks = document.querySelectorAll('.sidebar-nav a');
    sidebarLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Prevent default if it's not a navigational link (i.e., if it has '#' as href)
            if(this.getAttribute('href') === '#') {
                e.preventDefault();
            }
            
            var contentArea = document.getElementById('content');
            
            // Check if the clicked link is the Manage Users link
            if (this.id === 'manage-users') {
                // Fetch the user management content (you'll need a URL that responds with this content)
                fetch('manage_users.php')
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(html) {
                        // Insert the HTML into the content area of the dashboard
                        contentArea.innerHTML = html;
                    })
                    .catch(function(error) {
                        console.error('Error loading user management content:', error);
                    });
            } else {
                // Here you might handle other links in the sidebar differently, 
                // possibly updating contentArea.innerHTML with other content
            }
        });
    });
});
