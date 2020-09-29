# IEEE_Olympics_CTF_2020
This repo contains writeups for IEEE Olympics 1.0 CTF 2020.
Produced to by Champion Team. 
We got the **4th place** with 494 points only 3 points to the 3rd place, enjoy.

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
I made some modification to print the time and random number as shown from that i found that the time of response + 3h will return the time required ,but this is was in my local server only in the challenge server there is no constant hours to add.
(![local server](https://www8.0zz0.com/2020/09/29/22/978549807.png))

so now we have one variable in the name generated hash function is the random value.
here i tried to upload image with php code injected to my local server and check if I can get it. 
I found that the server saved it with new hash name and can be accessed, but the server doesn’t show the name.

So, all what I have to do is:
1. Inject picture with php code to get RCE. 
2. rename it to image.jpg.php.
3. take the date in response.
4. generate 1000 possible hash names.
5. use a tool like intruder in burp and give it the names to return the correct one to you.
6. finally execute your commands.

To inject php I used exiftoo with this command:
`exiftool -Comment='<?php echo "<pre>"; system($_GET['cmd']); ?>' test5.jpg`
Then rename it to: test5.jpg.php.
I uploaeded it and get the response date and give to a script I wrote for this challenge you can get it in the repo. 
Now I have file with 1000 possible hash names, I tried to give it to the intruder and start it, but it didn't work because intruder take long time and the server was deleting the files every **3 Minutes** .

In this point I talked to my teammates for a help. my teammate **Mohammed Saleh** told me to use a great tool called FUFF.
You can get it from ([here](https://github.com/ffuf/ffuf)).

WoW it worked!!!
(![FUFF](https://www11.0zz0.com/2020/09/29/23/841804976.png))

So now I have the path, let's get the flag.
I tried to put the parameter cmd=ls to list the contents of the folder.
(![before flag](https://www13.0zz0.com/2020/09/29/23/931832642.png))

Let's take step before this directory.
(![before flag2](https://www5.0zz0.com/2020/09/29/23/161437722.png))

After showing the flag it returns blank page so, I showed the source page, and We got the flag successfully.
(![flag](https://www5.0zz0.com/2020/09/29/23/525548868.png))


I would like to thank to the organizers for this competition that was very interesting and had very good challenges.
Thanks to my Teammates (Mohamed Saleh & Hussen ELSayed) for this great effort.
