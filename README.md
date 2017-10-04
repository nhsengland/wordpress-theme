# NHS England theme

The WordPress theme running on the main site and most subsites of [england.nhs.uk](https://www.england.nhs.uk/).

## Plugin dependencies

The theme calls functions from the following plugins:

- [ACF PRO](https://www.advancedcustomfields.com/pro/)
- [Post Indexer Plugin](https://premium.wpmudev.org/project/post-indexer/)
- [Co-Authors Plus](https://wordpress.org/plugins/co-authors-plus/)
- An unpublished plugin called star-ratings

However, it should function with only ACF PRO. (And it might function without).

## Development

### Install dependencies

```
% composer install
% yarn install
```

### Run tests

```
% vendor/bin/peridot spec -r dot
% vendor/bin/php-cs-fixer fix --dry-run -v --diff
```

### Build assets

```
% grunt
```

### Code and design style

- This theme is based on [iguana](https://github.com/dxw/iguana) and [whippet-theme-template](https://github.com/dxw/whippet-theme-template)
- Use SVG inline within the markup and style/position using class names - this is for speed and is good practice at least at the time of writing
- Graphics / Icons should be made using SS Pika as the icon font - if no icon exists try to create one in the same style or question the need for an icon. Icons should be used graphic symbols and as way-finding points for information contained within the document
- Colours should be pulled from the NHS colour pallet defined in `assets/scss/settings/_colour.scss`
- Type is set in Frutiger and falls back to a system font stack
- Use the grid defined in `assets/scss/helpers/_grid.scss` it works in a fractional way so there should be enough flexibility
- If you need to use similar functional SCSS in multiple files consider adding it to the mixins defined in `assets/scss/helpers/_mixins.scss`
- Settings is the place for all common theme settings like; typography, use of colour, margins, padding etc.

## Licence

Made by [dxw](https://www.dxw.com/). Licensed under the [MIT licence](LICENCE.txt).
