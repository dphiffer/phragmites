# phragmites

WordPress artist portfolio theme

## Dependencies

- [WordPress](https://wordpress.org/)
- [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/) plugin

## Development dependencies

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [node.js](https://nodejs.org/en/) (tested on v18)
- [Composer](https://getcomposer.org/)

## Dev environment setup

1. Copy `.env.sample` to `.env` and edit the `ACF_PRO_KEY` variable ([more info](https://www.advancedcustomfields.com/pro/))
2. Run the start script: `./bin/start`

## Production setup

Assumes you have filesystem ownership set to the user running the following commands.

```
cd wp-content/themes/phragmites
npm install
npm run build
```

## Image sizes

Image sizes are configured automatically.

- Thumbnail: 285x176 (cropped)
- Medium: 720x0
- Large: 1000x0
