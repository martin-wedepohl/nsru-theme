<?php
/**
 * Template Name: Virtual Sobriety
 * Template Post Type: post, page
 * The template for displaying the sobriety entered from the virtual ticket purchase
 * at the time of the NSRU start.
 *
 * @package Layers
 * @since Layers 1.0.0
 */

global $wpdb;

get_header();

$start_date       = '2022-04-15 19:00:00';
$round_up_options = get_option( 'round_up_options', array() );
if ( count( $round_up_options ) > 0 ) {
	$start_date = $round_up_options['start_date'] . ' 19:00:00';
}

$table   = $wpdb->prefix . 'countdown';
$sql     = 
"SELECT `name`, `email`,
TIMESTAMPDIFF(DAY, CONCAT(sobriety, ' 19:00:00'), DATE_FORMAT('" . $start_date . "', '%Y-%m-%d %H:%i:%S')) AS days_sober,
TIMESTAMPDIFF(MONTH, CONCAT(sobriety, ' 19:00:00'), DATE_FORMAT('" . $start_date . "', '%Y-%m-%d %H:%i:%S')) AS months_sober,
TIMESTAMPDIFF(YEAR, CONCAT(sobriety, ' 19:00:00'), DATE_FORMAT('" . $start_date . "', '%Y-%m-%d %H:%i:%S')) AS years_sober
FROM ${table} ORDER BY days_sober DESC";
$results = $wpdb->get_results( $sql );
?>

<style>
	.sobriety-header {
		margin-bottom: 1.5em;
	}

	.sobriety-table {
		display: flex;
		flex-direction: column;
		width: 60vw;
		margin: 2em auto;
	}

	.sobriety-row-total {
		font-size: 125%;
		font-weight: 700;
		padding-top: 2em;
		padding-bottom: 1em;
	}
</style>
<section class="sobriety-table">
<h2 class="sobriety-header">Sobriety for Countdown on April 15, 2022</h2>

<?php
if ( ! empty( $results ) ) {
	$years  = 0;
	$months = 0;
	$days   = 0;
	foreach ( $results as $row ) {
		if ( $row->years_sober > 0 ) { ?>
			<p class="sobriety-row"><?php echo $row->name; ?>, <?php echo $row->email; ?>, <?php echo $row->years_sober; ?> Years</p>
		<?php
			$years += $row->years_sober;
		} else if ( $row->months_sober > 0 ) { ?>
			<p class="sobriety-row"><?php echo $row->name; ?>, <?php echo $row->email; ?>, <?php echo $row->months_sober; ?> Months</p>
		<?php
			$months += $row->months_sober;
		} else if ( $row->days_sober > 0 ) { ?>
			<p class="sobriety-row"><?php echo $row->name; ?>, <?php echo $row->email; ?>, <?php echo $row->days_sober; ?> Days</p>
		<?php
			$days += $row->days_sober;
		} else { ?>
			<p class="sobriety-row-none"><?php echo $row->name; ?>, <?php echo $row->email; ?>, -----</p>
		<?php
		}
	}
	$months += intval( $days / 30 );
	$days    = $days % 30;
	$years  += intval( $months / 12 );
	$months  = $months % 12; ?>
	<p class="sobriety-row-total">Total: <?php echo $years; ?> Years <?php echo $months; ?> Months <?php echo $days; ?> Days</p>
	<?php
} else { ?>
	<p class="no-data">No results in database</p>
<?php } ?>
</section>

<?php get_footer();
