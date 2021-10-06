Our final application, MeTrade, consists of 3 communicating Virtual Machines: 2 web servers (clientserver and adminserver) and 1 database server (dbserver).  It is a very primitive system for a rental company. 

The clientserver hosts the webpage for a user to submit a form to rent a pre-loaded item. 
The dbserver hosts the mySQL database to store the booking information from the client server. 
The adminserver hosts a webpage for the business side - as it displays who has rented what item (along with their contact details). 

**To set up this application, follow these steps: **

1. Create a new directory and transport into it. 

2. Run $git init

3. Run $git clone https://github.com/hroseoc/assignment1 

4. Transport into the ‘assignment1’ directory. 

5. Run $vagrant up

5. Go to: 

a) http://192.168.2.11/client.php ~ to view the client interface of MeTrade.

b) http://192.168.2.13/admin.php ~ to view the admin interface of MeTrade.

