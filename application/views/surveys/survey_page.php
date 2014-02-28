<div class="row">
  
  <?= $messages ?>  
  
  <span class="label"><strong>Status:</strong> <?= $survey->status ?></span>
  <h1><?= $survey->title ?></h1>
  
  
  <?php if (has_permission('download survey files')) : ?>
    <?php if ($survey->files['xls'] !== NULL) : ?>
      <div>xls: <a href="<?= base_url(sprintf('survey/%d/files/xls', $survey->sid)); ?>">xls</a></div>
    <?php endif; ?>
    
    <?php if ($survey->files['xml'] !== NULL) : ?>
      <div>xml: <a href="<?= base_url(sprintf('survey/%d/files/xml', $survey->sid)); ?>">xml</a></div>
    <?php endif; ?>
  <?php endif; ?>
  
</div>