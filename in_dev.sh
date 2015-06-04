cp -R /var/www/velaforfun/web /var/www/dev_velaforfun
echo "copiata web"
cp -R /var/www/velaforfun/app/Resources /var/www/dev_velaforfun/app
echo "copiata Resources"
cp -R /var/www/velaforfun/src /var/www/dev_velaforfun
echo "copiata src"
cd /var/www/dev_velaforfun
git add .
echo "deploy in dev in corso..."
git commit -m "deploy in dev env heroku"
git push heroku master