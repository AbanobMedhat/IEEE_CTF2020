# IEEE-_CTF
This repo contain writeups for IEEE Mansora Branch CTF 2020.
Produced to by Champion Team. 
We got the **4th place** with 494 points only 3 points to the 3rd place, enjoy.

## Web
### Se3cure Uploader
#### Discription
We've released our new open source file uploading service, do
your things and find out if there are any security gaps. Source
code is attached, demo can be found
at http://165.227.140.251/s3cure_uploader To be more secure,
and for testing purposes we're deleting the uploaded files every 3
mins. flag file can be found at flag.php.
Challenge Points : 200
(![challenge](https://www4.0zz0.com/2020/09/29/22/474349029.jpg))

#### Dive into Challenge
When I finished reading the description , I found that is file upload challenge ,so all what I have is to upload picture or file and execute commands to read flag.php file. 
when i tired to upload jpg picutre it returns the path to it ,but the name of the file is **hashed** with .jpg extention.
so I tried to upload .php file ,it returns **invalid type**.
(![upload](https://www5.0zz0.com/2020/09/29/22/704605433.png))
(![invalid type](https://www8.0zz0.com/2020/09/29/23/676416689.png))

#### Source code analysis 
So first, I downloaded the source code , began to analysis it carefully.
As shown in the below picture of upload.php
(![source](https://www8.0zz0.com/2020/09/29/22/273265710.jpg))


From analyzing , I found the following:
-	After uploading the file it will be saved in the uploads directory
-	The server saves with different name from its original name.
-	There is validation on the size and type of file that must be an image.
So I started to run the source code locally on my laptop and try to solve it.

From source code I found that naming the file have the following procedure:
1.	Generate random number from 1 to 1000
2.	Concatenate the random number with the current time in format y-m-d min-sec
3.	Concatenate to them the file original name without extension.
4.	Apply md5 hashing method to the previous combination.
5.	concatenate to the output hash the extension of the file.

In the upload image challenges there are 2 things you need to check:
- upload image with php code successfuly. 
- path to access it.

#### Solution Process
I made some modification to print the time and random number as shown from that i found that the time of response + 3h will return the time required ,but this is was in my local server only in the challenge server there is no constant hours to add.
(![local server](https://www8.0zz0.com/2020/09/29/22/978549807.png))

so now we have one variable in the name generated hash function is the random value.
here i tried to upload image with php code injected to my local server and check if i can get it. 
I found that the server saved it with new hash name and can be accessed ,but the server don't show the name .

So, All what i have to do is :
1. Inject picture with php code to get rce. 
2. rename it to image.jpg.php.
3. take the date in response.
4. generate 1000 possible hash names.
5. use a tool like intruder in burp and give it the names to return the correct one to you.
6. finally execute your commands.

To inject php I used exiftoo with this command:
`exiftool -Comment='<?php echo "<pre>"; system($_GET['cmd']); ?>' test5.jpg`
Then rename it to : test5.jpg.php.
I uploaeded it and get the response date and give to a script I wrote for this challenge you can get it in the repo. 
Now I have file with 1000 possible hash names, I tried to give it to the intruder and start it, but it didn't work because intruder take long time and the server was deleting the files every **3 Minutes** .

In this point I talked to my teammates for a help. my teammate **Mohammed Saleh** told me to use a great tool called FUFF.
You can get it from ([here](https://github.com/ffuf/ffuf)).

WoW it worked !!!
(![FUFF](https://www11.0zz0.com/2020/09/29/23/841804976.png))

So now I have the path , let's get the flag.
I tried to put the parameter cmd=ls to list the contents of the folder.
(![before flag](https://www13.0zz0.com/2020/09/29/23/931832642.png))

Let's take step before this directory.
(![before flag2](https://www5.0zz0.com/2020/09/29/23/161437722.png))

After showing the flag it return blank page so, I showed the source page and We got the flag successfuly.
(![flag](https://www5.0zz0.com/2020/09/29/23/525548868.png))



