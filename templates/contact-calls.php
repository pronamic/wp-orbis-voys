<?php
/**
 * Contact calls
 *
 * @author    Pronamic
 * @copyright 2005-2026 Pronamic
 * @license   GPL-2.0-or-later
 * @package   Pronamic\Orbis\Voys
 *
 * @var list<array{call_id: string, direction: string, caller_number: string, caller_name: string, in_progress_at: string, ended_at: string}> $calls Calls from and to the phone numbers of the contact.
 */

namespace Pronamic\Orbis\Voys;

use DateTimeImmutable;
use DateTimeZone;
use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="card mb-3">
	<div class="card-header"><?php \esc_html_e( 'Phone Calls', 'orbis-voys' ); ?></div>

	<?php if ( [] === $calls ) : ?>

		<div class="card-body">
			<p class="m-0 text-muted">
				<?php \esc_html_e( 'No phone calls found.', 'orbis-voys' ); ?>
			</p>
		</div>

	<?php else : ?>

		<table class="table table-striped mb-0">
			<thead>
				<tr>
					<th scope="col"><?php \esc_html_e( 'Date / Time', 'orbis-voys' ); ?></th>
					<th scope="col"><span class="visually-hidden"><?php \esc_html_e( 'Direction', 'orbis-voys' ); ?></span></th>
					<th scope="col"><?php \esc_html_e( 'Duration', 'orbis-voys' ); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ( $calls as $call ) : ?>

					<?php

					// Voys timestamps are stored in UTC, see `call-notification-controller/parse.php`.
					try {
						$in_progress_at = new DateTimeImmutable( $call['in_progress_at'], new DateTimeZone( 'UTC' ) );
						$ended_at       = new DateTimeImmutable( $call['ended_at'], new DateTimeZone( 'UTC' ) );
					} catch ( Exception ) {
						continue;
					}

					$timestamp = $in_progress_at->getTimestamp();

					$seconds = \max( 0, $ended_at->getTimestamp() - $timestamp );

					$duration = \sprintf( '%02d:%02d:%02d', \intdiv( $seconds, 3600 ), \intdiv( $seconds % 3600, 60 ), $seconds % 60 );

					$time_title = \sprintf(
						/* translators: 1: Date and time in site timezone, 2: Time in UTC */
						\__( '%1$s (%2$s UTC)', 'orbis-voys' ),
						\wp_date( 'l j F Y H:i:s T', $timestamp ),
						\gmdate( 'H:i:s', $timestamp )
					);

					?>

					<tr>
						<td>
							<time datetime="<?php echo \esc_attr( (string) \wp_date( 'c', $timestamp ) ); ?>" title="<?php echo \esc_attr( $time_title ); ?>">
								<?php echo \esc_html( (string) \wp_date( 'd-m-Y H:i:s', $timestamp ) ); ?>
							</time>
						</td>
						<td>
							<span class="fa-stack">
								<i class="fas fa-phone fa-flip-horizontal"></i>

								<?php if ( 'inbound' === $call['direction'] ) : ?>

									<i class="fas fa-arrow-left" style="color: Green; transform: rotate( -45deg );" title="<?php \esc_attr_e( 'Inbound', 'orbis-voys' ); ?>"></i>

								<?php elseif ( 'outbound' === $call['direction'] ) : ?>

									<i class="fas fa-arrow-right" style="color: Red; transform: rotate( -45deg );" title="<?php \esc_attr_e( 'Outbound', 'orbis-voys' ); ?>"></i>

								<?php endif; ?>
							</span>
						</td>
						<td>
							<time datetime="<?php echo \esc_attr( 'PT' . $seconds . 'S' ); ?>">
								<?php echo \esc_html( $duration ); ?>
							</time>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

	<?php endif; ?>
</div>
