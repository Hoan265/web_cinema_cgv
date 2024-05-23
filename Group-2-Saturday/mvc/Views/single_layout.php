<?php require "inc/admin/head.php" ?>
<body>
    <!-- Page Container -->
    <div id="page-container">
        <!-- Main Container -->
        <main id="main-container">
        <!-- Page Content -->
        <?php include "./mvc/Views/".$data['Page'].".php" ?>
        <!-- END Page Content -->
        </main>
    </div>
    <!-- END Page Container -->
    <?php include "inc/admin/script.php" ?>
</body>
</html>
