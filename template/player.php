<div
  class="raudio raudio-player<?= isset($size) ? " raudio-player-$size" : '' ?>"
  data-raudio="raudio"
>
  <div class="raudio-thumbnail">
    <img class="raudio-img" data-raudio-current-track-artwork-url-large />
  </div>
  <div class="raudio-body">
    <button
      class="raudio-toggle"
      data-toggle="raudio"
    ></button>
    <div class="raudio-current">
      <div class="raudio-artist" data-raudio-current-track-artist="raudio-current-track-artist">&nbsp;</div>
      <div class="raudio-title" data-raudio-current-track-title="raudio-current-track-title">&nbsp;</div>
    </div>
  </div>
</div>
