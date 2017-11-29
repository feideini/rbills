INSTRUCTIONS FOR ADDING A NEW RENTER

add entries in login.csv and renters.csv and stateItems.csv

here is an example:

tenancy begins 2016-10-01
202,2016-10
is the entry in months.csv

1024,202,1,SDP,-1175
1024,202,1,SEC,1175
1024,202,1,PDP,-1175
1024,202,1,PET,1175
1024,202,1,PAY,-2350

is the relevant entry in stateItems.csv

you may add something like

1029,214,1,FMP,-125.81

to adjust for an odd move-in date

NEW
rsync -avz -e 'ssh -i /home/stefan/gpg/aws3.pem' "/media/stefan/134578d5-ab2b-42d7-acee-ef4be0a6852c/barney/house/rbills/" ubuntu@35.160.211.119:"/home/ubuntu/www/lpublic/rbills"

OLD
rsync -avz --delete /media/Kodak/stefan-2011/computer/mysql/rbills/ becker@67.18.11.31:/home/becker/www/lpublic/rbills/
rsync -avz --delete /cygdrive/e/stefan-2012/computer/mysql/rbills/ becker@67.18.11.31:/home/becker/www/lpublic/rbills/
rsync -avz --delete /media/Kodak/stefan-2010/computer/mysql/rbills/ becker@otis.phpwebhosting.com:/home/becker/www/lpublic/rbills/
rsync -avz --delete /home/pi/stefan-2013/computer/mysql/rbills/ becker@67.18.11.31:/home/becker/www/lpublic/rbills/
rsync -avz --delete /cygdrive/s/stefan-2014/house/rbills/ becker@otis.phpwebhosting.com:/home/becker/www/lpublic/rbills/
rsync -avz --delete /home/stefan/stefan-2013/house/rbills/ becker@67.18.11.31:/home/becker/www/lpublic/rbills/
rsync --protocol=28 -avz --delete /media/truecrypt1/house/rbills/ becker@67.18.11.31:/home/becker/www/lpublic/rbills/
rsync --protocol=28 -avz --delete /media/stefan/134578d5-ab2b-42d7-acee-ef4be0a6852c/barney/house/rbills/ becker@96.127.147.178:/home/becker/www/lpublic/rbills/

********* you can use Shift-NumPad-Insert to paste in putty *********

(2a) change dir to ./lpublic/rbills

(3) mysql -u ubuntu -p
3senuf

mysql -u becker -p
4stoomuch

(4) use rbills;

(5) source loadTables.sql;

(6) fix permissions on phpwebhosting
chmod 644 /home/becker/www/lpublic/rbills/* && chmod 755 /home/becker/www/lpublic/rbills

(7) test

(8) emails.txt will be in rbills on server
n server

scp -i /media/stefan/134578d5-ab2b-42d7-acee-ef4be0a6852c/barney/computer/amazon/aws3.pem rbills.tar.gz ubuntu@ec2-35-160-211-119.us-west-2.compute.amazonaws.com:~/www/lpublic

(1) update stefan-2011/computer/mysql/rbills/ stateItems.csv and utilities.csv

(2) rsync
