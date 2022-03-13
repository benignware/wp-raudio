<?php

class Raudio_Custom_Nav {
  public function add_nav_menu_meta_boxes() {
    add_meta_box(
      'raudio_player_nav',
      __('Raudio Player'),
      array( $this, 'nav_menu_link'),
      'nav-menus',
      'side',
      'low'
    );
  }
  
  public function nav_menu_link() {?>
    <div id="posttype-raudio-player" class="posttypediv">
      <div id="tabs-panel-raudio-player" class="tabs-panel tabs-panel-active">
        <ul id="raudio-player-form-list" class="categorychecklist form-no-clear">
          <li>
            <label class="menu-item-title">
              <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> Webradio Player
            </label>
            <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="raudio-player">
            <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="Webradio Player">
            <input type="hidden" class="menu-item-classes" name="menu-item[-1][menu-item-classes]" value="">
          </li>
        </ul>
      </div>
      <p class="button-controls">
        <span class="list-controls">
          <a href="/wordpress/wp-admin/nav-menus.php?page-tab=all&amp;selectall=1#posttype-page" class="select-all">Select All</a>
        </span>
        <span class="add-to-menu">
          <input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-raudio-player">
          <span class="spinner"></span>
        </span>
      </p>
    </div>
  <?php }
}

$custom_nav = new Raudio_Custom_Nav;

add_action('admin_init', array($custom_nav, 'add_nav_menu_meta_boxes'));



add_action( 'wp_nav_menu_item_custom_fields', function( $item_id, $item ) {
	$block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();

	if (!isset($block_types['core/button'])) {
		return;
	}

	$button_block_type = $block_types['core/button'];

	$menu_item_button = get_post_meta( $item_id, '_menu_item_button', true );
	$menu_item_button_background_color = get_post_meta( $item_id, '_menu_item_button_background_color', true );
	$menu_item_button_style = get_post_meta( $item_id, '_menu_item_button_style', true );

	?>
	<div style="clear: both;">
		<input type="hidden" class="nav-menu-id" value="<?php echo $item_id ;?>" />
		<input
			type="checkbox"
			name="menu_item_button[<?php echo $item_id ;?>]"
			id="menu-item-button-<?php echo $item_id ;?>"
			value="1"
			data-toggle="menu-plus-settings-panel"
			<?php if (esc_attr( $menu_item_button ) === '1'): ?> checked<?php endif; ?>
		/>
		<label
			for="menu-item-button-<?php echo $item_id ;?>"
			class="button-type"><?php _e( "Button", 'menu-item-button' ); ?>
		</label>

		<div class="menu-plus-settings-panel">
			<div class="menu-plus-settings-panel-body">
				<!-- Background Color -->
				<div style="clear: both;">
					<label class="button-background-color"><?php _e( "Background Color", 'menu-item-button' ); ?></label><br />
					<div class="logged-input-holder">
						<input
							type="text"
							name="menu_item_button_background_color[<?php echo $item_id ;?>]"
							id="menu-item-button-background-color<?php echo $item_id ;?>"
							value="<?= esc_attr( $menu_item_button_background_color ) ?>"
							data-menu-plus-color-picker
						/>
					</div>
				</div>
		
				<!-- Style -->
				<div style="clear: both;">
					<label class="button-style"><?php _e( "Style", 'menu-item-button' ); ?></label><br />
				
					<div class="logged-input-holder">
						<select
							name="menu_item_button_style[<?php echo $item_id ;?>]"
							id="menu-item-button-style<?php echo $item_id ;?>"
						>
							<?php foreach ($button_block_type->styles as ['name' => $style, 'label' => $label]): ?>
								<option
									value="<?= $style ?>"
									<?php if (esc_attr( $menu_item_button_style ) === $style ): ?>selected<?php endif; ?>
								>
									<?= $label ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
}, 10, 2 );


add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args ) {
  if (strpos($item->type, 'raudio') === 0) {
    $slug = $args->menu->slug;
    $classes = explode(' ', $atts['class']);
    $classes[] = 'raudio raudio-player';

    // $match = preg_match('~(xs)~', $item->type, $sizes);
    // $size = $sizes[0];

    // if (isset($size)) {
    //   $classes[] = 'raudio-player-xs';
    // }
    $classes[] = 'raudio-player-xs';

    $atts['class'] = implode(' ', array_unique(array_filter($classes)));
    $atts['id'] = "raudio-player-nav-menu-$slug-item-{$item->ID}";
    $atts['data-raudio'] = true;
  }

  return $atts;
}, 10, 3 );

add_filter( 'wp_nav_menu', function($nav_menu = '', $args = array()) {
  // Parse DOM
  $doc = new DOMDocument();
  @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $nav_menu );

  $doc_xpath = new DOMXpath($doc);
  $elements = $doc_xpath->query('//*[@data-raudio]');

  $html = do_shortcode('[raudio-player]');

  $player_doc = new DOMDocument();
  @$player_doc->loadHTML('<?xml encoding="utf-8" ?>' . $html );

  $player_doc_xpath = new DOMXpath($player_doc);
  $wrapper = $player_doc_xpath->query('/html/body/*')->item(0);
  // $wrapper_attrs = raudio_dom_attrs($wrapper);

  $wrapper = $doc->importNode($wrapper, true);

  foreach ($elements as $element) {
    // Merge class
    $class = implode(' ', array_unique(
      array_filter(
        array_merge(
          preg_split('/\s+/', $element->getAttribute('class')),
          preg_split('/\s+/', $wrapper->getAttribute('class'))
        )
      )
    ));
    $wrapper->setAttribute('class', $class);

    // Copy all other attributes
    foreach ($element->attributes as $attr) {
      if (!$wrapper->hasAttribute($attr->nodeName)) {
        $wrapper->setAttribute($attr->nodeName, $attr->nodeValue);
      }
    }

    $placeholder = uniqid();

    $wrapper->setAttribute('data-turbo-permanent', "$placeholder");
    $wrapper->setAttribute('data-turbolinks-permanent', "$placeholder");

    // $turbo_wrapper = $doc->createElement('div');
    // $turbo_wrapper->setAttribute('id', $wrapper->getAttribute('id') . '-wrapper');
    // $turbo_wrapper->setAttribute('data-turbolinks-permanent', "$placeholder");
    // $turbo_wrapper->appendChild($wrapper);

    $element->parentNode->insertBefore($wrapper, $element);
    $element->parentNode->removeChild($element);
  }

  $nav_menu = preg_replace('~(?:<\?[^>]*>|<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>)\s*~i', '', $doc->saveHTML());
  $nav_menu = str_replace('="' . $placeholder . '"', '', $nav_menu);

  return $nav_menu;
});
