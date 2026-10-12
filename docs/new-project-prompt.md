# New project prompt

Copy the block below into a new Claude session (started in `Webroot`), fill in what you know
and delete lines that don't apply. Anything left empty, Claude asks for or puts on the open-items list.

```text
New immosite project. Use the immosite skill.

Project
- Name:
- Domain:                      (e.g. seestrasse-zug.ch → https://seestrasse-zug.ch.test)
- Type:                        landing / full site
- GitHub repo:                 public / private

Design
- Brief:                       (path to PDF / Figma link / screenshots, or "none")
- Sister project of:           (existing project to copy instead of the starter, or "none")
- Designer credits:            stoz
- Images:                      (folder with originals, or "placeholders for now")

Modules
- Form:                        registration (sign-up) / contact form / both
- Weekly export:               yes / no
- Map:                         yes / no – coordinates: lat , lng
- Extra pages:                 FAQ / Ausbau / Gewerbe / Downloads / Disclaimer
- Apartments source:           none / static (Mieterspiegel: path) / Flatfox (URL) / Melon (URL)
- Apply button:                none / source link / own form
- Isometry:                    no / yes (AI or SVG export: path)

Marketer
- Company, address:
- Contact person, role:
- E-mail, phone:

Mail
- Contact form to:
- Weekly export to / BCC:      (day + time, default Monday 08:00)
- Sender address:

Other
- Hosting:                     (for the Datenschutz page, default webkeeper)
- Notes:
```

Before starting: add `https://<domain>.test/` to the Mapbox token's allowed URLs if the site has a map.
