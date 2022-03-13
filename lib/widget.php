<?php
/**
 * Adds Raudio widget.
 */
class Raudio_Widget extends WP_Widget
{
  /**
   * Register widget with WordPress.
   */
  function __construct()
  {
      parent::__construct('raudio_widget', // Base ID
      esc_html__('Raudio Widget', 'raudio') , // Name
      array(
          'description' => esc_html__('Webradio player widget', 'raudio') ,
      ) // Args
      );
  }
  /**
   * Front-end display of the widget.
   *
   * @param array $args Widget arguments.
   * @param array $instance Saved values from the database.
   *
   * @see WP_Widget::widget()
   *
   */
  public function widget($args, $instance) {
    echo $args['before_widget'];

    if (!empty($instance['title'])) {
        echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
    }
  
    echo do_shortcode('[raudio-player]');

    echo $args['after_widget'];
  }
  /**
   * Back-end widget form.
   *
   * @param array $instance Previously saved values from the database.
   *
   * @see WP_Widget::form()
   *
   */
  public function form($instance)
  {
      $title = !empty($instance['title']) ? $instance['title'] : '';
      $size = !empty($instance['size']) ? $instance['size'] : '';
?>
<!-- Title -->
<p>
<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
<?php esc_attr__('Widget Title:', 'raudio'); ?>
</label>
<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
value="<?php echo esc_attr($title); ?>">
</p>
<!-- Player size -->
<p>
<label for="<?php echo esc_attr($this->get_field_id('size')); ?>">
<?php esc_attr_e('Specify player size', 'raudio'); ?>
</label>
<select class="widefat" id="<?php echo esc_attr($this->get_field_id('size')); ?>"
name="<?php echo esc_attr($this->get_field_name('size')); ?>">
<option value="" <?php echo ($size == '') ? 'selected' : ''; ?>>Normal</option>
<option value="sm" <?php echo ($size == 'sm') ? 'selected' : ''; ?>>Small (Toggle & Display)</option>
<option value="xs" <?php echo ($size == 'xs') ? 'selected' : ''; ?>>Tiny (Toggle Only)</option>
</select>
</p>
<?php
  }
  /**
   * Sanitize widget form values as they are saved.
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from the database.
   *
   * @return array Updated safe values to be saved.
   * @see WP_Widget::update()
   *
   */
  public function update($new_instance, $old_instance)
  {
      $instance = array();
      $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
      $instance['size'] = (!empty($new_instance['size'])) ? sanitize_text_field($new_instance['size']) : '';
      return $instance;
  }
}

add_action('widgets_init', function() {
  register_widget('Raudio_Widget');
});

