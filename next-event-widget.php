<?php
/*
Plugin Name: Next Event Widget
Plugin URI: https://github.com/michaelschwarz1/next-event-widget
Description: Displays the upcoming events from the cooltimeline plugin in a simple widget
Author: Michael M. Schwarz
Version: 1.0
Author URI: http://mmschwarz.de/
*/


class NextEventWidget extends WP_Widget
{
  function NextEventWidget()
  {
    $widget_ops = array('classname' => 'NextEventWidget', 'description' => 'Displays the upcoming events from the cooltimeline plugin in a simple widget' );
    $this->WP_Widget('NextEventWidget', 'Next Event Widget', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => ''), array( 'post_per_page' => '' ) );  
    $title = $instance['title'];

        $post_per_page = $instance['post_per_page'];

      
?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">Title:
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /> </label>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('post_per_page'); ?>">Anzahl angezeigter Events:
            <input class="widefat" id="<?php echo $this->get_field_id('post_per_page'); ?>" name="<?php echo $this->get_field_name('post_per_page'); ?>" type="number" value="<?php echo attribute_escape($post_per_page); ?>" /> </label>
    </p>
    <?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    if($new_instance['post_per_page'] >0) {
      $instance['post_per_page'] = $new_instance['post_per_page'];
    }
    else {
        return false;
    }  
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    $post_per_page = $instance['post_per_page'];
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
    $html='';
    $format =__('d.m.y','cool_timeline');
    $args = array(
            'post_type' => 'cool_timeline', 
            'posts_per_page' => $post_per_page,
            'post_status' => 'future',
			'orderby' => 'date',
			'order' =>"ASC");
    $ctl_loop = new WP_Query($args);
    if ($ctl_loop->have_posts()){
       $html.='<div id="next_post_plugin" class="textwidget">';
	   while ($ctl_loop->have_posts()) : $ctl_loop->the_post();
            $html.='<h4>>'. get_the_date($format) . ' - ';
            $html.='<a href="http://hard-polka-wage.de/?page_id=27">';
            $html .=get_the_title() . '</h4></a>';
    
        endwhile;
        $html.='</div>';
        echo $html;
    }
      else{
          echo "Keine Daten vorhanden";
      }     
    wp_reset_query();
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("NextEventWidget");') );?>
