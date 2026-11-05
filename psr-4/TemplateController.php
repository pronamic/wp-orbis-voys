<?php
/**
 * Template controller
 *
 * @author    Pronamic
 * @copyright 2005-2026 Pronamic
 * @license   GPL-2.0-or-later
 * @package   Pronamic\Orbis\Voys
 */

declare(strict_types=1);

namespace Pronamic\Orbis\Voys;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Pronamic\Orbis\Contacts\ContactJson;

/**
 * Template controller class
 */
final readonly class TemplateController {
	/**
	 * Construct.
	 *
	 * @param CallRepository $calls Call repository.
	 */
	public function __construct(
		private CallRepository $calls
	) {
		\add_action( 'orbis_after_side_content', $this->maybe_include_person_calls( ... ) );
	}

	/**
	 * Maybe include the calls from and to the phone numbers of a person.
	 *
	 * @return void
	 */
	private function maybe_include_person_calls(): void {
		if ( ! \is_singular( 'orbis_person' ) ) {
			return;
		}

		if ( ! \class_exists( ContactJson::class ) ) {
			return;
		}

		$phone_numbers = $this->get_voys_phone_numbers( ContactJson::get_phone_numbers( (int) \get_the_ID() ) );

		if ( [] === $phone_numbers ) {
			return;
		}

		$calls = $this->calls->get_calls_by_phone_numbers( $phone_numbers );

		include __DIR__ . '/../templates/person-calls.php';
	}

	/**
	 * Get the phone numbers in the formats Voys uses.
	 *
	 * Voys stores phone numbers in E.164 (`+31612345678`) or in national
	 * format as dialed (`0612345678`), so both are returned.
	 *
	 * Phone numbers that Orbis Contacts could not standardize to E.164 are
	 * skipped.
	 *
	 * @param mixed $items Phone numbers from `ContactJson::get_phone_numbers()`.
	 * @return list<string>
	 */
	private function get_voys_phone_numbers( mixed $items ): array {
		$phone_util = PhoneNumberUtil::getInstance();

		$phone_numbers = [];

		foreach ( \is_array( $items ) ? $items : [] as $item ) {
			$e164 = \is_array( $item ) ? ( $item['e164'] ?? null ) : null;

			if ( ! \is_string( $e164 ) || '' === $e164 ) {
				continue;
			}

			$phone_numbers[] = $e164;

			try {
				$phone_number = $phone_util->parse( $e164 );
			} catch ( NumberParseException ) {
				continue;
			}

			$phone_numbers[] = '0' . $phone_number->getNationalNumber();
		}

		return \array_values( \array_unique( $phone_numbers ) );
	}
}
