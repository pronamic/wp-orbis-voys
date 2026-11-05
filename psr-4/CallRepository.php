<?php
/**
 * Call repository
 *
 * @author    Pronamic
 * @copyright 2005-2026 Pronamic
 * @license   GPL-2.0-or-later
 * @package   Pronamic\Orbis\Voys
 */

declare(strict_types=1);

namespace Pronamic\Orbis\Voys;

/**
 * Call repository class
 *
 * Reads the calls from the `orbis_voys_notifications` table, which is filled
 * by the Voys call notification controller (see `call-notification-controller/`).
 * A call consists of an `in-progress` and an `ended` notification with the
 * same call ID.
 *
 * @link https://www.voys.nl/hulp/freedom/gespreksnotificaties/
 */
final class CallRepository {
	/**
	 * Get the calls from or to the given phone numbers, newest first.
	 *
	 * @param array<string> $phone_numbers Phone numbers as stored by Voys, e.g. `+31612345678` and `0612345678`.
	 * @param int           $limit         Maximum number of calls.
	 * @return list<array{call_id: string, direction: string, caller_number: string, caller_name: string, in_progress_at: string, ended_at: string}>
	 */
	public function get_calls_by_phone_numbers( array $phone_numbers, int $limit = 40 ): array {
		global $wpdb;

		/**
		 * WordPress database abstraction object.
		 *
		 * @var \wpdb $wpdb
		 */

		if ( [] === $phone_numbers ) {
			return [];
		}

		$placeholders = \implode( ', ', \array_fill( 0, \count( $phone_numbers ), '%s' ) );

		$query = <<<SQL
			SELECT
				notification_start.call_id,
				notification_start.direction,
				notification_start.caller_number,
				notification_start.caller_name,
				notification_start.generated_at AS in_progress_at,
				notification_end.generated_at AS ended_at
			FROM
				orbis_voys_notifications AS notification_start
					INNER JOIN
				orbis_voys_notifications AS notification_end
						ON notification_start.call_id = notification_end.call_id
			WHERE
				notification_start.status = 'in-progress'
					AND
				notification_end.status = 'ended'
					AND
				(
					notification_start.caller_number IN ( $placeholders )
						OR
					notification_start.destination_number IN ( $placeholders )
				)
			ORDER BY
				notification_start.generated_at DESC
			LIMIT
				%d
			;
			SQL;

		$args = [ ...\array_values( $phone_numbers ), ...\array_values( $phone_numbers ), $limit ];

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- The query only interpolates placeholders.
		$rows = $wpdb->get_results( $wpdb->prepare( $query, $args ), \ARRAY_A );

		$calls = [];

		foreach ( \is_array( $rows ) ? $rows : [] as $row ) {
			$value = static fn( string $key ): string => \is_scalar( $row[ $key ] ?? null ) ? (string) $row[ $key ] : '';

			$calls[] = [
				'call_id'        => $value( 'call_id' ),
				'direction'      => $value( 'direction' ),
				'caller_number'  => $value( 'caller_number' ),
				'caller_name'    => $value( 'caller_name' ),
				'in_progress_at' => $value( 'in_progress_at' ),
				'ended_at'       => $value( 'ended_at' ),
			];
		}

		return $calls;
	}
}
