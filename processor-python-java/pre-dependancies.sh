
# processor shell
sudo yum update -y

sudo yum install python3 -y
sudo python3 -m pip install couchdb
sudo python3 -m pip install tqdm
sudo python3 -m pip install pandas

sudo yum install unzip -y

# ansible copy pre.zip and couchdb.txt to /home/ec2-user/
sudo unzip /home/ec2-user/pre.zip

python3 /home/ec2-user/pre/upload.py