# Album Navigation

Album Navigation is a plugin for Piwigo that adds navigation buttons between sibling albums.

When viewing a sub-album, it adds up to three buttons:

- ← previous album
- ↑ parent album
- → next album

The parent button returns to the page of the parent album that contains the current sub-album. This is especially useful when the parent contains enough sub-albums to be paginated.

## Features

- Navigate directly to the previous and next sibling albums.
- Return to the correct page of the parent album.
- Respects Piwigo album visibility and user permissions.
- Uses the album order defined in Piwigo.
- Integrates with Piwigo index buttons and the active theme.
- Supports right-to-left interfaces through Piwigo's normal theme handling.
- No configuration required.

## Languages

Translations are currently provided for:

- Arabic
- Breton
- Czech
- English (UK)
- English (US)
- Esperanto
- French
- German
- Spanish

## Requirements

- Piwigo 16

The plugin was developed and tested with Piwigo 16.4.

## Installation

Copy the `album-navigation` directory into the Piwigo `plugins` directory:

    plugins/album-navigation/

Then activate **Album Navigation** from the Piwigo administration interface.

No configuration is required.

## License

Album Navigation is distributed under the GNU General Public License, version 2 or later.

See `LICENSE` for details.