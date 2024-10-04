<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $page_title; ?> </title>
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="styles/edit_profile.css">
    <link rel="stylesheet" href="styles/journal_entry.css">
    <link rel="stylesheet" href="styles/profile.css">
    <link rel="stylesheet" href="styles/view.css">
</head>

<body>
    <nav>
        <div class="logo">
            <img src="../img/logo.png" alt="CARE">
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="view_goals.php">Goals</a></li>
            <li><a href="view_journal.php">Journal</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </nav>
