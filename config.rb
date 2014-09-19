# run `$ compass compile -e production --force` to force compile for production

http_path = "/"
preferred_syntax = :scss
css_dir = "css"
sass_dir = "_scss"
images_dir = "images"
javascripts_dir = "js"
# relative_assets = true
# sass_options = { :debug_info => true }
output_style = ( environment == :production ) ? :compressed : :expanded # :expanded, :nested, :compact or :compressed
line_comments = ( environment == :production ) ? false : true
