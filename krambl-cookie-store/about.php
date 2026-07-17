<?php
include __DIR__ . "/config.php";
$pageTitle = "About";
include "includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Our Story</span>
    <h1>About Krambl</h1>
    <p>Homegrown cookies inspired by Filipino comfort and celebration.</p>
</section>

<section class="section about-feature">
    <div class="about-copy">
        <span class="eyebrow">Made in the Philippines</span>
        <h2>Cookies na may pusong Pinoy.</h2>
        <p>Krambl Cookie Store is a fictional educational business that reimagines familiar Filipino desserts as soft, chunky cookies.</p>
        <p>Our flavors celebrate merienda, family gatherings, and the simple joy of sharing something sweet.</p>
        <a class="btn" href="store.php">Explore Flavors</a>
    </div>

    <div class="team-visual">
        <div class="team-cookie"><img src="assets/img/product-ube.svg" alt="Ube cookie"></div>
        <div class="team-card">
            <strong>Krambl Team</strong>
            <span>Design • Development • Database</span>
        </div>
    </div>
</section>

<section class="section team-section">
    <div class="section-head centered">
        <div>
            <span class="eyebrow">Meet the Team</span>
            <h2>People behind Krambl</h2>
        </div>
    </div>

    <div class="team-grid">
        <article><div class="avatar">UI</div><h3>Rovik Dinco</h3><p>Design / Developer</p></article>
        <article><div class="avatar">DB</div><h3>Lester Felix</h3><p>Database / Testing</p></article>
        <article><div class="avatar">PM</div><h3>Cairos Ramos</h3><p>Project Manager / Programmer</p></article>
        <article><div class="avatar">UI</div><h3>Angelo Santos</h3><p>Design / Documentation</p></article>
    </div>
</section>
<?php include "includes/footer.php"; ?>
