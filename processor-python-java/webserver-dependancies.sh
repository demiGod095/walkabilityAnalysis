
# webserver shell
sudo yum update -y
sudo yum install httpd httpd-tools -y
sudo systemctl start httpd
sudo systemctl enable httpd 
sudo chown apache:apache /var/www/html -R

sudo yum install php php-fpm php-mysqlnd php-opcache php-gd php-xml php-mbstring -y
sudo systemctl start php-fpm
sudo systemctl enable php-fpm
sudo systemctl restart httpd
sudo setsebool -P httpd_execmem 1

sudo yum install unzip -y

# ansible copy web.zip and couchdb.txt to /home/ec2-user/
sudo unzip /home/ec2-user/web.zip -d /var/www/html/
sudo cp /home/ec2-user/couchdb.txt /var/www/html/web/