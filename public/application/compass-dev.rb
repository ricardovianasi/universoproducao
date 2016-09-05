# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "./assets/dev/css/"
sass_dir = "./assets/resources/sass/"
images_dir = "./assets/dev/img/"
#images_path = "./dev/assets/img/"
#http_images_dir = "./dev/assets/img/"
sprite_load_path = "./assets/resources/sprites/"
javascripts_dir = "./assets/dev/js/"
fonts_dir = "./assets/dev/fonts/"

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed
output_style = :expanded

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = true


# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass

Sass::Script::Number.precision = 10
