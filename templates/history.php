<div
  class="raudio raudio-history<?= isset($size) ? " raudio-history-$size" : '' ?>"
  data-raudio
>
  <?php foreach (range(1, $max_items) as $i): ?>
    <p class="raudio-history-item">
      <<?= $atts['size'] === 'sm' ? 'small' : 'span' ?>
        class="raudio-artist"
        data-raudio-history-<?= $i ?>-artist
      ></<?= $atts['size'] === 'sm' ? 'small' : 'span'?>><br/>
      <<?= $atts['size'] === 'sm' ? 'small' : 'span' ?>
        class="raudio-title"
        data-raudio-history-<?= $i ?>-title
      ></<?= $atts['size'] === 'sm' ? 'small' : 'span' ?>>
    </p>
  <?php endforeach; ?>
</div>
