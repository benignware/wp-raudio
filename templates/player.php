<div
  class="raudio raudio-player<?= isset($size) ? " raudio-player-$size" : '' ?>"
  data-raudio
>
  <div class="raudio-thumbnail">
    <img class="raudio-img" data-raudio-current-track-artwork-url-large/>
  </div>
  <div class="raudio-body">
    <button
      class="raudio-toggle"
      data-toggle="raudio"
    ></button>
    <div class="raudio-current">
      <div class="raudio-artist" data-raudio-current-track-artist></div>
      <div class="raudio-title" data-raudio-current-track-title></div>
    </div>
  </div>
</div>
