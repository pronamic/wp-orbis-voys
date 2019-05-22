# Orbis Voys

Orbis Voys plugin
- install / register table
- orbis_voys_notifications - parse JSON routine
- orbis_voys_notifications - user agent
- orbis_voys_notifications - ip address

orbis_voys_calls
- caller_id
- direction
- account_number
- caller_number
- caller_name
- created_at
- ringing_at
- in_progress_at
- ended_at

https://wiki.voipgrid.nl/index.php/Gespreksnotificaties

webhooks
notifications.voys.orbis.example.com
webhooks.voys.orbis.example.com

https://www.voys.nl/weblog/met-webhooks-kun-je-alles/

https://www.voys.nl/hulp/freedom/gespreksnotificaties/

https://www.voys.nl/hulp/features/voys-freedom-integratie-lily/

Klaar!
De koppeling tussen Lily en Freedom is nu gereed. Als je wordt gebeld door nummers die bekend zijn in jouw Lily-omgeving wordt er automatisch bij het juiste contact of account een notitie gemaakt hoe laat, hoe lang en met wie er is gebeld. Dit geldt ook voor uitgaande oproepen die je met jouw VoIP-toestel maakt naar nummers die bekend zijn in jouw Lily-omgeving. Lily genereert bij elke binnenkomende oproep op jouw toestel een popup waarop je kan klikken. Als het nummer bekend is in jouw Lily-omgeving wordt automatisch het juiste contact of account in Lily geopend!
Maar, we kunnen nóg meer!
Nu er een koppeling is tussen Lily en Freedom kan je nog dieper integreren met Freedom. Je kan namelijk Lily óók laten bepalen op welk toestel een binnenkomende oproep moet overgaan. Op deze manier komt een binnenkomende oproep van jouw klant direct bij de juiste collega laten uit! 
Dit doe je door middel van de webhook ‘variabele eindbestemming’. Hier staat uitgelegd hoe je dit instelt.

https://stackoverflow.com/questions/1431378/how-to-remove-htaccess-password-protection-from-a-subdirectory

WordPress user
meta 
- Telefoonnummer
- Intern nummer

Dashboard
- Laatste 10 gesprekken
- Week rapport meest gebeld
- Maand rapport meest gebeld

https://support.twilio.com/hc/en-us/articles/223183008-Formatting-International-Phone-Numbers
https://www.twilio.com/docs/glossary/what-e164
https://en.wikipedia.org/wiki/E.164
https://github.com/giggsey/libphonenumber-for-php

```
SELECT
call_id,
direction,
caller_number,
caller_name,
caller_account_number,
MIN( IF( status = "created", generated_at, NULL ) ) AS created_at,
MIN( IF( status = "ringing", generated_at, NULL ) ) AS ringing_at,
MIN( IF( status = "in-progress", generated_at, NULL ) ) AS in_progress_at,
MIN( IF( status = "warm-transfer", generated_at, NULL ) ) AS warm_transfer_at,
MIN( IF( status = "ended", generated_at, NULL ) ) AS ended_at
FROM 
orbis_voys_notifications
GROUP BY
call_id
ORDER BY
created_at
;
```
