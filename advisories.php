<?php
// Include header for language + menu
include __DIR__ . '/includes/header.php';
?>
<h2><?php echo t('Farming Advisories', 'Ushauri wa Kilimo'); ?></h2>
<p><?php echo t(
    'Stay updated with the latest farming tips and alerts.',
    'Kaa ukijua ushauri na tahadhari za kilimo za hivi karibuni.'
); ?></p>

<!-- Example static advisories -->
<ul>
    <li><?php echo t('Prepare fields for planting maize.', 'Andaa mashamba kwa upandaji wa mahindi.'); ?></li>
    <li><?php echo t('Irrigate crops due to dry spell.', 'Mwagilia mimea kutokana na ukame.'); ?></li>
    <li><?php echo t('Use certified seeds to improve yields.', 'Tumia mbegu zilizoidhinishwa ili kuongeza mavuno.'); ?></li>
</ul>

<?php
include __DIR__ . '/includes/footer.php';
?>
