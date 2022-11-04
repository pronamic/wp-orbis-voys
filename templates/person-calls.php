<?php

$post = get_post();

$numbers = array(
    get_post_meta( $post->ID, '_orbis_phone_number', true ),
    get_post_meta( $post->ID, '_orbis_mobile_number', true ),
);

$numbers = array_filter( $numbers );

$phone_numbers = array();

$phone_util = \libphonenumber\PhoneNumberUtil::getInstance();

foreach ( $numbers as $number ) {
    try {
        $phone_number_object = $phone_util->parse( $number, 'NL' );

        $phone_numbers[] = $phone_util->format( $phone_number_object, \libphonenumber\PhoneNumberFormat::E164 );
        $phone_numbers[] = '0' . $phone_number_object->getNationalNumber();
    } catch ( NumberParseException $e ) {

    }
}

if ( ! empty( $phone_numbers ) ) : ?>

    <div class="card mb-3">
        <div class="card-header"><?php esc_html_e( 'Telefoongesprekken', 'orbis' ); ?></div>

        <?php

        global $wpdb;

        $where = '1 = 1';

        $where .= $wpdb->prepare( ' AND notification_start.status = %s', 'in-progress' );
        $where .= $wpdb->prepare( ' AND notification_end.status = %s', 'ended' );

        $where .= ' AND ( ';

        $where .= $wpdb->prepare(
            sprintf(
                "notification_start.caller_number IN ( %s )",
                implode( ',', array_fill( 0, count( $phone_numbers ), '%s' ) )
            ),
            $phone_numbers
        );

        $where .= ' OR ';

        $where .= $wpdb->prepare(
            sprintf(
                "notification_start.destination_number IN ( %s )",
                implode( ',', array_fill( 0, count( $phone_numbers ), '%s' ) )
            ),
            $phone_numbers
        );

        $where .= ')';

        $query = "
            SELECT
                notification_start.call_id,
                notification_start.direction,
                notification_start.caller_number,
                notification_start.caller_name,
                notification_start.caller_account_number,
                notification_start.generated_at AS in_progress_at,
                notification_end.generated_at AS ended_at
            FROM 
                orbis_voys_notifications AS notification_start
                    INNER JOIN
                orbis_voys_notifications AS notification_end
                        ON notification_start.call_id = notification_end.call_id
            WHERE
                $where
            ORDER BY
                notification_start.generated_at DESC
            LIMIT
                0, 40
            ;
        ";

        $calls = $wpdb->get_results( $query );

        if ( filter_input( INPUT_GET, 'debug_voys', FILTER_VALIDATE_BOOLEAN ) ) {
            echo '<pre>', $query, '</pre>';
        }

        if ( empty( $calls ) ) : ?>

            <div class="card-body">
                <p class="m-0 text-muted">
                    Geen telefoongesprekken gevonden.
                </p>
            </div>

        <?php else : ?>

            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th scope="col">Datum / tijd</th>
                        <th scope="col"></th>
                        <th scope="col">Duur</th>
                    </tr>
                </thead>

                <tbody>
                    
                    <?php foreach ( $calls as $call ) : ?>

                        <tr>
                            <?php

                            $in_progress_at = new DateTime( $call->in_progress_at );
                            $ended_at       = new DateTime( $call->ended_at );

                            $time = $ended_at->getTimestamp() - $in_progress_at->getTimestamp();

                            ?>
                            <td>
                                <?php echo esc_html( $in_progress_at->format( 'd-m-Y H:i:s' ) ); ?>
                            </td>
                            <td>
                                <span class="fa-stack">
                                    <i class="fas fa-phone fa-flip-horizontal"></i>

                                    <?php 

                                    switch ( $call->direction ) {
                                        case 'inbound':
                                            echo '<i class="fas fa-arrow-left" style="color: Green; transform: rotate( -45deg );"></i>';
                                            break;
                                        case 'outbound':
                                            echo '<i class="fas fa-arrow-right" style="color: Red; transform: rotate( -45deg );"></i>';
                                            break;
                                    }

                                    ?>                                              
                                </span>
                            </td>
                            <td>
                                <?php echo esc_html( orbis_time( $time, 'HH:MM:SS' ) ); ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>

        <?php endif; ?>

    </div>

<?php endif; ?>
