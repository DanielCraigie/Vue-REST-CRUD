mkdir -p ./public/js
mkdir -p ./public/css

cp -f ./node_modules/vue/dist/vue.min.js ./public/js/

cp -f ./node_modules/bootstrap/dist/css/bootstrap.min.css ./public/css/
cp -f ./node_modules/bootstrap/dist/js/bootstrap.min.js ./public/js/

cp -f ./node_modules/@fortawesome/fontawesome-free/css/all.min.css ./public/css/
cp -f ./node_modules/@fortawesome/fontawesome-free/js/all.min.js ./public/js/
cp -rf ./node_modules/@fortawesome/fontawesome-free/webfonts ./public/

cp -f ./node_modules/jquery/dist/jquery.min.js ./public/js/
cp -f ./node_modules/popper.js/dist/umd/popper.min.js ./public/js/

cp -f ./node_modules/axios/dist/axios.min.js ./public/js/
