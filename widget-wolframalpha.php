<?php
/*
Plugin Name: Wolfram Alpha
Plugin URI: http://blog.melimato.com/wolframalpha
Description: Adds a Wolfram Alpha search engine plugin.
Version: 0.1 Beta
Author: Pedro Camara
Author URI: http://blog.melimato.com/

Copyright 2009  Pedro Camara (email : pedrobc@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('widgets_init', array(WolframAlphaWidget::ID, 'register'));
register_activation_hook( __FILE__, array(WolframAlphaWidget::ID, 'activate'));
register_deactivation_hook( __FILE__, array(WolframAlphaWidget::ID, 'deactivate'));

class WolframAlphaWidget
{
	const ID		= 'WolframAlphaWidget';
	const NAME		= 'WolframAlpha Widget';

	function activate()
	{
		$defaults = array(
			'sizes' => array('small' => 'small' ,'medium' => 'medium', 'large' => 'large'),
			'title' => _e('WolframAlpha Widget')
	  );
		if(!get_option(self::ID))
		{
		  add_option(self::ID, $defaults);
		} else {
		  update_option(self::ID, $defaults);
		}
	}

	function deactivate()
	{
		delete_option(self::ID);
	}

	function control()
	{
		$options = get_option(self::ID);
		?>
	<fieldset>
		<legend></legend>
		<p><label><?php _e('Title:'); ?><input class="widefat" name="<?php echo self::ID.'_title' ; ?>" type="text" value="<?php echo $options['title']; ?>" /></label></p>
		<p><?php _e('Size:'); ?></p>
		<p><input name="<?php echo self::ID.'_size' ; ?>" type="radio" value="<?php echo $options['sizes']['small'];?>" <?php checked( $options['size'], $options['sizes']['small']); ?>/>&nbsp;<label><?php echo _e('Small');?></label></p>
		<p><input name="<?php echo self::ID.'_size' ; ?>" type="radio" value="<?php echo $options['sizes']['medium'];?>" <?php checked( $options['size'], $options['sizes']['medium']); ?> />&nbsp;<label><?php echo _e('Medium');?></label></p>
		<p><input name="<?php echo self::ID.'_size' ; ?>" type="radio" value="<?php echo $options['sizes']['large'];?>" <?php checked( $options['size'], $options['sizes']['large']); ?> />&nbsp;<label><?php echo _e('Large');?></label></p>
	</fieldset>
	<?php
		if(isset($_POST[ self::ID.'_title' ])) {
			$options['title'] = attribute_escape($_POST[ self::ID.'_title' ]);
			$options['size'] = attribute_escape($_POST[ self::ID.'_size' ]);
			update_option( self::ID, $options );
		}
	}

	function display($args = array())
	{
		extract($args);
		$options = get_option(self::ID);

		$title = empty($options['title']) ? '' : apply_filters('widget_title', $options['title']);
		$size = $options['size'];

		echo $args['before_widget'];
		if(!empty($title)) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo self::getWidgetCode($size);
		echo $args['after_widget'];
	}

	function getWidgetCode($size)
	{
		return '<script id="WolframAlphaScript" src="http://www.wolframalpha.com/input/embed/?type=' . $size . '" type="text/javascript"></script>';
  }

	function register()
	{
		register_sidebar_widget(self::NAME, array(self::ID, 'display'));
		register_widget_control(self::NAME, array(self::ID, 'control'));
	}
}
?>
