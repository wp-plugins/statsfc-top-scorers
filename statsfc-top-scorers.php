<?php
/*
Plugin Name: StatsFC Top Scorers
Plugin URI: https://statsfc.com/docs/wordpress
Description: StatsFC Top Scorers
Version: 1.5.1
Author: Will Woodward
Author URI: http://willjw.co.uk
License: GPL2
*/

/*  Copyright 2013  Will Woodward  (email : will@willjw.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('STATSFC_TOPSCORERS_ID',		'StatsFC_TopScorers');
define('STATSFC_TOPSCORERS_NAME',	'StatsFC Top Scorers');

/**
 * Adds StatsFC widget.
 */
class StatsFC_TopScorers extends WP_Widget {
	public $isShortcode = false;

	private static $defaults = array(
		'title'			=> '',
		'key'			=> '',
		'competition'	=> '',
		'team'			=> '',
		'date'			=> '',
		'limit'			=> 0,
		'highlight'		=> '',
		'default_css'	=> true
	);

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(STATSFC_TOPSCORERS_ID, STATSFC_TOPSCORERS_NAME, array('description' => 'StatsFC Top Scorers'));
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) {
		$instance		= wp_parse_args((array) $instance, self::$defaults);
		$title			= strip_tags($instance['title']);
		$key			= strip_tags($instance['key']);
		$competition	= strip_tags($instance['competition']);
		$team			= strip_tags($instance['team']);
		$date			= strip_tags($instance['date']);
		$limit			= strip_tags($instance['limit']);
		$highlight		= strip_tags($instance['highlight']);
		$default_css	= strip_tags($instance['default_css']);
		?>
		<p>
			<label>
				<?php _e('Title', STATSFC_TOPSCORERS_ID); ?>:
				<input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
			</label>
		</p>
		<p>
			<label>
				<?php _e('Key', STATSFC_TOPSCORERS_ID); ?>:
				<input class="widefat" name="<?php echo $this->get_field_name('key'); ?>" type="text" value="<?php echo esc_attr($key); ?>">
			</label>
		</p>
		<p>
			<label>
				<?php _e('Competition', STATSFC_TOPSCORERS_ID); ?>:
				<?php
				try {
					$data = $this->_fetchData('https://api.statsfc.com/crowdscores/competitions.php');

					if (empty($data)) {
						throw new Exception;
					}

					$json = json_decode($data);

					if (isset($json->error)) {
						throw new Exception;
					}
					?>
					<select class="widefat" name="<?php echo $this->get_field_name('competition'); ?>">
						<option></option>
						<?php
						foreach ($json as $comp) {
							echo '<option value="' . esc_attr($comp->key) . '"' . ($comp->key == $competition ? ' selected' : '') . '>' . esc_attr($comp->name) . '</option>' . PHP_EOL;
						}
						?>
					</select>
				<?php
				} catch (Exception $e) {
				?>
					<input class="widefat" name="<?php echo $this->get_field_name('competition'); ?>" type="text" value="<?php echo esc_attr($competition); ?>">
				<?php
				}
				?>
			</label>
		</p>
		<p>
			<label>
				<?php _e('Team', STATSFC_TOPSCORERS_ID); ?>:
				<input class="widefat" name="<?php echo $this->get_field_name('team'); ?>" type="text" value="<?php echo esc_attr($team); ?>" placeholder="e.g., Liverpool, Manchester City">
			</label>
		</p>
		<p>
			<label>
				<?php _e('Date (YYYY-MM-DD)', STATSFC_TOPSCORERS_ID); ?>:
				<input class="widefat" name="<?php echo $this->get_field_name('date'); ?>" type="text" value="<?php echo esc_attr($date); ?>" placeholder="YYYY-MM-DD">
			</label>
		</p>
		<p>
			<label>
				<?php _e('Limit', STATSFC_TOPSCORERS_ID); ?>:
				<input class="widefat" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo esc_attr($limit); ?>" min="0" max="99">
			</label>
		</p>
		<p>
			<label>
				<?php _e('Highlight', STATSFC_TOPSCORERS_ID); ?>:
				<input class="widefat" name="<?php echo $this->get_field_name('highlight'); ?>" type="text" value="<?php echo esc_attr($highlight); ?>" placeholder="E.g., Liverpool, Swansea City">
			</label>
		</p>
		<p>
			<label>
				<?php _e('Use default CSS?', STATSFC_TOPSCORERS_ID); ?>
				<input type="checkbox" name="<?php echo $this->get_field_name('default_css'); ?>"<?php echo ($default_css == 'on' ? ' checked' : ''); ?>>
			</label>
		</p>
	<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance) {
		$instance					= $old_instance;
		$instance['title']			= strip_tags($new_instance['title']);
		$instance['key']			= strip_tags($new_instance['key']);
		$instance['competition']	= strip_tags($new_instance['competition']);
		$instance['team']			= strip_tags($new_instance['team']);
		$instance['date']			= strip_tags($new_instance['date']);
		$instance['limit']			= strip_tags($new_instance['limit']);
		$instance['highlight']		= strip_tags($new_instance['highlight']);
		$instance['default_css']	= strip_tags($new_instance['default_css']);

		return $instance;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance) {
		extract($args);

		$title			= apply_filters('widget_title', $instance['title']);
		$key			= $instance['key'];
		$competition	= $instance['competition'];
		$team			= $instance['team'];
		$date			= $instance['date'];
		$limit			= $instance['limit'];
		$highlight		= $instance['highlight'];
		$default_css	= filter_var($instance['default_css'], FILTER_VALIDATE_BOOLEAN);

		$html  = $before_widget;
		$html .= $before_title . $title . $after_title;

		try {
			$data = $this->_fetchData('https://api.statsfc.com/crowdscores/top-scorers.php?key=' . urlencode($key) . '&competition=' . urlencode($competition) . '&team=' . urlencode($team) . '&date=' . urlencode($date) . '&limit=' . urlencode($limit));

			if (empty($data)) {
				throw new Exception('There was an error connecting to the StatsFC API');
			}

			$json = json_decode($data);

			if (isset($json->error)) {
				throw new Exception($json->error);
			}

			$scorers	= $json->scorers;
			$customer	= $json->customer;

			if ($default_css) {
				wp_register_style(STATSFC_TOPSCORERS_ID . '-css', plugins_url('all.css', __FILE__));
				wp_enqueue_style(STATSFC_TOPSCORERS_ID . '-css');
			}

			$html .= <<< HTML
			<div class="statsfc_topscorers">
				<table>
					<thead>
						<tr>
							<th>Player</th>
							<th>Team</th>
							<th class="statsfc_numeric">Goals</th>
						</tr>
					</thead>
					<tbody>
HTML;

			foreach ($scorers as $scorer) {
				$class	= '';
				$player	= esc_attr($scorer->player);
				$badge	= esc_attr($scorer->path);
				$team	= esc_attr($scorer->team);
				$goals	= esc_attr($scorer->goals);

				if (! empty($highlight) && $highlight == $scorer->team) {
					$class = 'statsfc_highlight';
				}

				$html .= <<< HTML
				<tr class="{$class}">
					<td>{$player}</td>
					<td class="statsfc_team statsfc_badge_{$badge}" style="background-image: url(//api.statsfc.com/kit/{$badge}.svg);">{$team}</td>
					<td class="statsfc_numeric">{$goals}</td>
				</tr>
HTML;
			}

			$html .= <<< HTML
					</tbody>
				</table>
HTML;

			if ($customer->attribution) {
				$html .= <<< HTML
				<p class="statsfc_footer"><small>Powered by StatsFC.com. Fan data via CrowdScores.com</small></p>
HTML;
			}

			$html .= <<< HTML
			</div>
HTML;
		} catch (Exception $e) {
			$html .= '<p style="text-align: center;">StatsFC.com – ' . esc_attr($e->getMessage()) . '</p>' . PHP_EOL;
		}

		$html .= $after_widget;

		if ($this->isShortcode) {
			return $html;
		} else {
			echo $html;
		}
	}

	private function _fetchData($url) {
		$response = wp_remote_get($url);

		return wp_remote_retrieve_body($response);
	}

	public static function shortcode($atts) {
		$args = shortcode_atts(self::$defaults, $atts);

		$widget					= new self;
		$widget->isShortcode	= true;

		return $widget->widget(array(), $args);
	}
}

// register StatsFC widget
add_action('widgets_init', create_function('', 'register_widget("' . STATSFC_TOPSCORERS_ID . '");'));
add_shortcode('statsfc-top-scorers', STATSFC_TOPSCORERS_ID . '::shortcode');
