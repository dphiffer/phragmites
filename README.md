# phragmites

WordPress artist portfolio theme

## Dependencies

- WordPress
- Advanced Custom Fields Pro plugin

## Development dependencies

- Docker Desktop
- node.js (tested on v16)
- Composer

## Dev environment setup

1. Copy `.env.sample` to `.env` and edit the `ACF_PRO_KEY` variable ([more info](https://www.advancedcustomfields.com/pro/))
2. Run the start script: `./bin/start`
3. Update plugins: `docker compose exec web composer update --working-dir="/var/www/html/wp-content/themes/phragmites"`

## Production setup

Assumes you have filesystem ownership set to the user running the following commands.

```
cd wp-content/themes/phragmites
npm install
npm run build
ACF_PRO_KEY=xxxxxx composer update
```

## Image sizes

- Thumbnail: 285x176 (cropped)
- Medium: 720x0
- Large: 1000x0
