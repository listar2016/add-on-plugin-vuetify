<?php
/**
 * get average rating per post from DB
 *
 * @param $post_id
 *
 * @return array|bool
 */
function eeao_get_average_rating( $post_id, $wiget_id ) {
	if ( ! is_numeric( $post_id ) || ! is_numeric( $wiget_id ) ) {
		return;
	}
	global $wpdb, $table_prefix;
	$sql     = "select  count(id) as ratings, AVG(rating) as avg_rating from " . $table_prefix . "eeao_post_ratings where post_id=$post_id and widget_id=$wiget_id";
	$results = $wpdb->get_results( $sql );

	if ( $results[0]->ratings > 0 ) {
		$rating       = number_format( $results[0]->avg_rating, 1 );
		$rating_count = $results[0]->ratings;

		return [
			'count'  => (int) $rating_count,
			'rating' => $rating
		];
	}

	return false;
}

function eeao_no_ratings_yet() {
	return __( 'No ratings yet', 'elementor-express-add-ons' );
}

function eeao_default_star_color() {
	return '#ffd055';
}

function eeao_default_inactive_star_color() {
	return '#d8d8d8';
}

function eeao_create_widget_id( $post_id ){
	global $wpdb, $table_prefix;
	$table = $table_prefix . 'eeao_widget_ids';
	$response = $wpdb->insert( $table, [
		'post_id' => $post_id
	] );

	if( $response ){
		return $wpdb->insert_id;
	}
	return false;
}

function eeao_get_rating_statistics_sql_result( $post_id, $wiget_id ){
	if ( ! is_numeric( $post_id ) || ! is_numeric( $wiget_id ) ) {
		return;
	}
	global $wpdb, $table_prefix;
	$table = $table_prefix . 'eeao_post_ratings';
	$where = "where post_Id=$post_id and widget_id=$wiget_id";
	$sql = "SELECT post_Id as pid, 
       ROUND(AVG(rating)) AS rate, 
       COUNT(*) AS review_count,
 COUNT(CASE WHEN `rating` =5 THEN 1 END) AS rating_5,
       COUNT(CASE WHEN `rating` = 5 THEN 1 END) / (COUNT(*) * 100) * 10000 AS rating_5_avg,
       COUNT(CASE WHEN `rating` between 4 and 4.99 THEN 1 END) AS rating_4,
       COUNT(CASE WHEN `rating` between 4 and 4.99 THEN 1 END) / (COUNT(*) * 100) * 10000 AS rating_4_avg,
 COUNT(CASE WHEN `rating` between 3 and 3.99 THEN 1 END) AS rating_3,
       COUNT(CASE WHEN `rating` between 3 and 3.99 THEN 1 END) / (COUNT(*) * 100) * 10000 AS rating_3_avg,
 COUNT(CASE WHEN `rating` between 2 and 2.99 THEN 1 END) AS rating_2,
       COUNT(CASE WHEN `rating` between 2 and 2.99 THEN 1 END) / (COUNT(*) * 100) * 10000 AS rating_2_avg,
 COUNT(CASE WHEN `rating` between 1 and 1.99 THEN 1 END) AS rating_1,
       COUNT(CASE WHEN `rating` between 1 and 1.99 THEN 1 END) / (COUNT(*) * 100) * 10000 AS rating_1_avg
      
FROM $table $where
group by post_Id";
	$results = $wpdb->get_results( $sql );
	return $results;
}
function eeao_get_rating_statistics_result($post_id, $wiget_id){
	$statistics_result = eeao_get_rating_statistics_sql_result($post_id, $wiget_id);
	$statistics_result = $statistics_result[0] ? get_object_vars($statistics_result[0]) : [];
	$statistics = [];
	$total = 0;
	for($star = 5; $star > 0; $star-- ){
		$total += $statistics_result["rating_{$star}"];
	}
	for($star = 5; $star > 0; $star-- ){
		$statistics[$star] = [
			'avg' => $statistics_result["rating_{$star}_avg"],
			'votes' => $statistics_result["rating_{$star}"],
		];
		$statistics[$star]['percentage'] = round($statistics[$star]['votes']/$total * 100);
	}
	return $statistics;
}
function eeao_get_rating_message( $rating_string, $ratings_average, $ratings_count ) {
	if( 0 === $ratings_count ){
		return eeao_no_ratings_yet();
	};

	return __( strtr( $rating_string, [
		'{{ratings_average}}' => $ratings_average,
		'{{ratings_count}}'   => $ratings_count
	] ), 'elementor-express-add-ons' );
}

function eeao_convert_setting_key_to_camel_case($settings){
	foreach($settings as $setting_key => $setting_value){
		$keyCamelCase =  preg_replace_callback('/[-_\s]+(.)?/', function($match){
			return strtoupper($match[1]);
		} , $setting_key);
		unset($settings[$setting_key]);
		$settings[$keyCamelCase] = $setting_value;
	}
	return $settings;
}

function eeao_progress_get_rank_text($settings){
	$rank_text = [];
	$progress_show_rank_text = false;
	if( isset ( $settings['progress_show_rank_text']  ) ){
		$progress_show_rank_text = $settings['progress_show_rank_text'] === 'yes';
	}

	for($star = 5; $star > 0; $star-- ){
		if(!$progress_show_rank_text){
			$rank_text[$star] =  "";
			continue;
		}
		$parts = [$settings["progress_rank_text_{$star}_before"], $star, $settings["progress_rank_text_{$star}_after"]];
		$parts = array_map('trim',$parts);
		$parts = array_filter($parts);
		$rank_text[$star] =  implode (' ' , $parts);
	}
	return $rank_text;
}
function eeao_progress_get_votes_text($settings){
	$votes_text = [];
	$progress_show_votes_number_text = false;
	if( isset($settings['progress_show_votes_number_text'] )) {
		$progress_show_votes_number_text = $settings['progress_show_votes_number_text'] === 'yes';
	}
	for($star = 5; $star > 0; $star-- ){
		if(!$progress_show_votes_number_text){
			$votes_text[$star] =  "";
			continue;
		}
		$parts = [$settings["progress_votes_text_{$star}_before"], "{{vote_number}}", $settings["progress_votes_text_{$star}_after"]];
		$parts = array_map('trim',$parts);
		$parts = array_filter($parts);
		$votes_text[$star] =  implode (' ' , $parts);
	}
	return $votes_text;
}

function eeao_get_post_type_list(){
	$args = array(
		'public'   => true
	);

	$output = 'names'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'

	$post_types = get_post_types( $args, $output, $operator );
	return array_diff( $post_types, [ 'attachment', 'elementor_library' ] );
}
