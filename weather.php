<?php
// Include header to load language & menu
include __DIR__ . '/includes/header.php';
?>
<h2><?php echo t('Weather Forecast', 'Utabiri wa Hali ya Hewa'); ?></h2>
<p><?php echo t(
    'Here you will find the latest weather updates for your region.',
    'Hapa utapata habari za hivi karibuni za hali ya hewa kwa eneo lako.'
); ?></p>

<!-- Example static weather info -->
<ul>
    <li><?php echo t('Temperature: 28°C', 'Joto: 28°C'); ?></li>
    <li><?php echo t('Rainfall: Light showers expected', 'Mvua: Mvua nyepesi inatarajiwa'); ?></li>
    <li><?php echo t('Wind: 15km/h East', 'Upepo: 15km/h Mashariki'); ?></li>
</ul>

<?php
// Include footer
include __DIR__ . '/includes/footer.php';
?>
