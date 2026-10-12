# Go-live check

Go through every line; each needs a yes or an explicit "not needed" from Marcel.

- [ ] `ROBOTS_NOINDEX` empty on live, `true` on staging; `/robots.txt` and `/sitemap.xml` correct on live.
- [ ] `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` = live URL.
- [ ] Mail: `MAIL_*` set, `MAIL_FROM_ADDRESS` on a domain that may send it; a real test submission of every form arrives (marketer and sender), Reply-To works.
- [ ] Recipients: `CONTACT_RECIPIENTS`, `REGISTRATIONS_EXPORT_EMAIL` / `_BCC` = the client's addresses, not Marcel's test addresses.
- [ ] Scheduler cron on the server (`* * * * * php artisan schedule:run`); `php artisan schedule:list` shows the weekly export on the agreed day/time (`REGISTRATIONS_EXPORT_DAY/TIME`) and `apartments:fetch`.
- [ ] Apartments: first `php artisan apartments:fetch` succeeded; table matches the source; floor labels, column order and prices as the agency wants (see [domain.md](domain.md)).
- [ ] Mapbox token allows the live domain; map shows the marker.
- [ ] Turnstile keys set for the live domain, or deliberately only the honeypot.
- [ ] Google Analytics ID only if the client wants it; Datenschutz then shows the section.
- [ ] Content: Impressum, Datenschutz (hosting, marketer e-mail), Disclaimer if used; no "Platzhalter" left (`grep -rn Platzhalter resources`).
- [ ] Favicon, apple-touch-icon, OG image (1200×630), mail logo; `config/site.php` title and description.
- [ ] Downloads: every PDF present, or "PDF folgt" accepted by the client.
- [ ] `commercial-admin`: login created with `user:create` for the marketer, objects seeded.
- [ ] Screenshots of every page at all widths green; Lighthouse/a11y spot check on home and /wohnen.
