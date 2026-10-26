# ModuledPage privacy and processing inventory

Review: 2026-10-26. Source basis: supplied `mp.zip` plugin source and the new
`base3.json`. This is a technical description, not a privacy notice for any
particular website or a compliance statement.

## Scope, roles, purposes and data categories

ModuledPage composes HTML pages from configured headers, contents and footers.
The host website is responsible for account identity, configuration, access
controls, lawfulness and downstream processing. Configuration modules may
render personal data from host-provided page content or embedded displays.
`Page/GeneratedPage.php` reads the active user ID and language from injected
host services to filter configured page modules. The class also reads local
page JSON. Its behavior does not establish native authorization for every
included display or its HTTP actions.

## Entry points, persistence and transfers

The module reads `local/Page/page-<name>.json` from the host/project plugin
layout. Page JSON may include HTML, remote content references and display
identifiers. Configured modules may embed external content, including iframe
sources selected by the website. This module's source snapshot does not
establish a universal database, table, cache, session schema, queue, file
upload location, log retention, recipient or transfer destination. The added
manifest stores only static module metadata and does not persist request data.

## Retention, rights, security and known gaps

Page configuration files persist until changed or removed by their owner.
No module-wide subject export, correction, restriction, erasure, backup purge,
restore/re-delete workflow or retention timer is defined in the inspected
sources. Hosting projects own these obligations for data in actual rendered
pages, displays, sessions and host stores. Content security, escaping,
accessibility, authorization of embedded actions, external embeds, consent
rules, backup procedures, logs and international transfers must be assessed
for each deployment. A navigation/user filter must not substitute for
server-side authorization. Security and legal compliance are not established
by this manifest change.

References: `src/Page/GeneratedPage.php`, `src/Page/AbstractModuledPage.php`,
`src/Content/DisplayPageModule.php`, `src/ModuledPagePlugin.php`, `README.md`.

Change history: 2026-10-26, initial manifest-related technical inventory.
