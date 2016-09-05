# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "./assets/dist/css/"
sass_dir = "./assets/resources/sass/"
images_dir = "./assets/dist/img/"
#images_path = "./dist/assets/img/"
#http_images_dir = "./dist/assets/img/"
sprite_load_path = "./assetsresources/sprites/"
javascripts_dir = "./assets/dist/js/"
fonts_dir = "./assets/dist/fonts/"

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed
output_style = :compressed

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false


# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass

Sass::Script::Number.precision = 10
