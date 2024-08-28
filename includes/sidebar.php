<!-- Sidebar -->
<nav
        id="sidenav-1"
        class="sidenav"
>
    <!-- Sidebar Header -->
    <div class="sidenav-header">
        <img src="path/to/logo.png" alt="Logo" class="logo">
    </div>

    <!-- Sidebar Menu -->
    <ul class="sidenav-menu">
        <li class="sidenav-item">
            <a class="sidenav-link" href="#">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Dashboard</span>
            </a>
        </li>
        <li class="sidenav-item">
            <a class="sidenav-link" href="#">
                <i class="fas fa-user fa-fw me-3"></i><span>Profile</span>
            </a>
        </li>
        <li class="sidenav-item">
            <a class="sidenav-link" href="#">
                <i class="fas fa-cog fa-fw me-3"></i><span>Settings</span>
            </a>
        </li>
        <li class="sidenav-item">
            <a class="sidenav-link" href="#">
                <i class="fas fa-book fa-fw me-3"></i><span>Documentation</span>
            </a>
        </li>
        <li class="sidenav-item">
            <a class="sidenav-link" href="logout.php">
                <i class="fas fa-info-circle fa-fw me-3"></i><span>Logout</span>
            </a>
        </li>
    </ul>
</nav>
<!-- Sidebar -->

<!-- Toggler -->
<button
        id="sidenav-toggler"
        class="btn btn-primary btn-toggle"
        aria-controls="#sidenav-1"
        aria-haspopup="true"
>
    <i class="fas fa-bars"></i>
</button>
<!-- Toggler -->

<!-- Custom CSS -->
<style>
    .sidenav {
        background-color: #343a40;
        color: #fff;
        width: 250px; /* Adjust width as needed */
        height: 100vh; /* Full height */
        position: fixed; /* Fixed position */
        top: 0;
        left: 0;
        overflow-y: auto; /* Enable scrolling if needed */
        padding-top: 1rem;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }
    .sidenav.hidden {
        display: none; /* Completely hides the sidebar */
    }
    .sidenav-header {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        border-bottom: 1px solid #495057;
        background-color: #495057;
    }
    .logo {
        width: 40px;
        height: auto;
    }
    .sidenav-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .sidenav-item {
        padding: 0.5rem 1rem;
    }
    .sidenav-link {
        color: #fff;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 0.5rem;
        border-radius: 0.25rem;
    }
    .sidenav-link:hover {
        background-color: #495057;
    }
    .sidenav-link i {
        margin-right: 0.5rem;
    }
    .btn-toggle {
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 1050; /* Ensure it sits above other elements */
    }
</style>

<!-- Custom JS -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidenav = document.getElementById("sidenav-1");
        const toggler = document.getElementById("sidenav-toggler");

        toggler.addEventListener("click", function() {
            sidenav.classList.toggle("hidden");
        });
    });
</script>
