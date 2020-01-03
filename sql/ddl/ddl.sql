
--
-- Table Users
--
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `username` VARCHAR(40) UNIQUE NOT NULL,
    `name` VARCHAR(80) NOT NULL,
    `email` VARCHAR(30) UNIQUE NOT NULL,
    `gravatar` varchar(255) DEFAULT NULL,
    `presentation` VARCHAR(200),
    `password` VARCHAR(40) NOT NULL,
    `posts` INTEGER NOT NULL DEFAULT 0,
    `answers` INTEGER NOT NULL DEFAULT 0,
    `comments` INTEGER NOT NULL DEFAULT 0,
    `votes` INTEGER NOT NULL DEFAULT 0,
    `score` INTEGER NOT NULL DEFAULT 0,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL
);


--
-- Table Questions
--
DROP TABLE IF EXISTS Question;
CREATE TABLE Question (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `username` VARCHAR(40) NOT NULL,
    `points` INTEGER NOT NULL DEFAULT 0,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL
);


--
-- Table Comment
--
DROP TABLE IF EXISTS Answer;
CREATE TABLE Answer (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `username` VARCHAR(40) NOT NULL,
    `question_id` INTEGER NOT NULL,
    `message` TEXT NOT NULL,
    `points` INTEGER NOT NULL DEFAULT 0,
    `accepted` INTEGER NOT NULL DEFAULT 0,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL
);


--
-- Table Comment
--
DROP TABLE IF EXISTS Comment;
CREATE TABLE Comment (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `username` VARCHAR(40) NOT NULL,
    `question_id` INTEGER NOT NULL,
    `answer_id` INTEGER NOT NULL,
    `message` TEXT NOT NULL,
    `points` INTEGER NOT NULL DEFAULT 0,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL
);


--
-- Table Tags
--
DROP TABLE IF EXISTS Tag;
CREATE TABLE Tag (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `tag` VARCHAR(50) NOT NULL,
    `question_id` INTEGER NOT NULL
);


--
-- Table Tags
--
DROP TABLE IF EXISTS Points;
CREATE TABLE Points (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `point_for_id` INTEGER NOT NULL,
    `point_for_type` VARCHAR(40) NOT NULL,
    `username` VARCHAR(40) NOT NULL
);


INSERT INTO `User` (`username`, `name`, `email`, `password`, `gravatar`, `posts`, `answers`, `comments`, `score`) VALUES
('connys', 'conny', 'conny@hotmail.com', '$2y$10$JsfU2a5RpAwQIO1TPGN5Xu0/E2FAo/jBdNtNB5u.tsZ9pCPM0p8mu', 'https://www.gravatar.com/avatar/e8f710ddf1540b8b1b7c2484b4a589db?d=retro', 0, 1, 0, 5),
('håkanstek', 'håkan', 'håkan@hotmail.com', '$2y$10$0higxO7jcbPfNvqAYjezw.bTXtBoaVRM80x8XdE.N7MPRcqJ6GjNK', 'https://www.gravatar.com/avatar/30d4a31415648af1262bda44e37bda88?d=retro', 0, 0, 0, 0),
('jesperrn', 'jesper nyhlen', 'jeppe_nyhlen@hotmail.com', '$2y$10$0YkHavhqOpng6NQ6hAE9cOuBQcnG.MHzH67prnFM76QgAwuNHhQIi', 'https://www.gravatar.com/avatar/29232024803641c16a9f6b0744248faf?s=200&d=mp&r=g', 0, 4, 1, 22.5),
('lekond', 'luke peterson', 'luke_pete@gmail.com', '$2y$10$pjhCxcUyQbYxFAO1rwBEZO6h/A/btrGouLI5LNezxG/vbbNrL3An6', 'https://www.gravatar.com/avatar/e194eff12215bf06e32c479b619e82c0?s=200&d=mp&r=g', 0, 1, 0, 5),
('beacy', 'ben allme', 'bennyboy@gmail.com', '$2y$10$6wsJluG13aRlAjnau346h.19eJtvtufksmY38CpV2b4qUMZkoJ9vi', 'https://www.gravatar.com/avatar/50784816cbbf302fa12f618aee67075d?s=200&d=mp&r=g', 1, 0, 0, 10),
('tronx55', 'mark hall', 'tronx55@live.com', '$2y$10$tLXnSPb.G3JhSQY4rpPmde7Y.N4A.J6x.tl7wg6Ax4qpuzTLEeiXi', 'https://www.gravatar.com/avatar/e80e80d0be58b5a35292218c65199679?s=200&d=mp&r=g', 1, 0, 0, 10),
('codeWarrior', 'nathan phill', 'nate_p@gmail.com', '$2y$10$KqqodgbABujHJGji6MrTqOPwAvdbQmDupmY3Kkm8lstQOFM3zh51u', 'https://www.gravatar.com/avatar/8a65958a9abb1c650145933005061299?s=200&d=mp&r=g', 1, 0, 1, 12.5),
('dreyJimi', 'jim longstomp', 'j.stomp@gmail.com', '$2y$10$KX.8fZjeUdDFMkeb1Cr1.urIZ1FXutubBmvGLvtG1aG4w5HiEMzoy', 'https://www.gravatar.com/avatar/c74c0d7a20123d6bf50ee96fb6bb80ae?s=200&d=mp&r=g', 0, 1, 0, 5),
('rHampthon', 'robin hampthon', 'hampthon74@live.com', '$2y$10$5ksser8a1HhMZu548C/o5.wx7XhP92Qn7dwSpJTEpAGoy4dqrArUi', 'https://www.gravatar.com/avatar/982f9668307c80e6bd94d8b5706cae7b?s=200&d=mp&r=g', 0, 0, 1, 2.5),
('twistedsoul', 'greg rogers', 'reggo@gmail.com', '$2y$10$x9ARFuPF1EAC/K6JTLptQe/kEjtNalWdPXC5fSWgkyklpbOIB6c1m', 'https://www.gravatar.com/avatar/05b81697257169cafacf572ad418ef84?s=200&d=mp&r=g', 0, 0, 2, 5),
('jenSupreme', 'jenny stevenson', 'supreme.jen@gmail.com', '$2y$10$OzsTYHDtcBr5msLTKBngzuOdhpvqlMff8sxdZbXn2mPOoNampX.ni', 'https://www.gravatar.com/avatar/8251b9de83df7cc8ca4f584ae32e256d?s=200&d=mp&r=g', 1, 0, 0, 10),
('CodeExtreme', 'johan larsson', 'codexlarsson@gmail.com', '$2y$10$vSrIh8Ses8.94WNjy6r9KuWPyUuVhjTNeW74QVltFgzaCe/4raMsO', 'https://www.gravatar.com/avatar/c13b3ea4a79e3575cf7a33ee3ea8f2e0?s=200&d=mp&r=g', 0, 0, 0, 0);


INSERT INTO `Question` (`title`, `message`, `username`) VALUES
-- ('What is the most stupid question asked?', 'Hey, im wondering if cucumbers are made of stone or lava or sement...? Answer please', 'connys'),
-- ('Is grey a color?', 'There is alot of colors around, is grey legit? huuh', 'håkanstek'),
('What are the possibilities in order to reduce the size of a python virtual environment?', 'How is it possible to reduce the size of a python virtual environment?


This might be:

	
*  Removing packages from site_packages but which one can be removed?
*  Removing the *.pyc files
*  Checking for used files as mentioned here: https://medium.com/@mojodna/slimming-down-lambda-deployment-zips-b3f6083a1dff


What else can be removed or stripped down? Or are there other way?


The use case is for example the upload of the virtualenv to a server with limited space (e.g. AWS Lambda function with 512 MB limit)', 'tronx55'),
('Nginx not receiving second request after 100-Contnue, 401 response', "For the below configuration, the request 2 is not reflecting in nginx. There is no error log, and nothing in access log for request 2.

The issue started occurring only after 100-Continue, 401 Unauthorized was introduced as initial response. If request 1 is responded with 101 from proxied server, everything (the end websocket connection is established).

Expected behavior: Second request should successfully reach proxy and then to proxied server. After that it will be coverted to websocket connection.

Nginx config
```
server {
    listen 443 ssl;
    server_name admin.example.com;
    ssl_certificate       /etc/nginx/server.pem;
    ssl_certificate_key   /etc/nginx/key.pem;
    ssl_session_cache shared:SSL:5m;
    ssl_protocols TLSv1.2;
    ssl_dhparam /etc/ssl/certs/dhparam.pem;
    ssl_ecdh_curve secp384r1;
    server_name_in_redirect on;
    client_max_body_size 100m;

    location /my_line/ {
        proxy_pass        https://y.y.y.y/;
        proxy_set_header  Host y.y.y.y;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection $http_connection;
        proxy_ignore_client_abort on;
        proxy_buffering off;
        proxy_http_version 1.1;
        proxy_set_header Origin '';
        keepalive_timeout 100s;
        proxy_set_header Expect $http_expect;
        proxy_read_timeout 7200;
    }

    error_page  404              /index.html;
    location / {
        root   /usr/share/nginx/html;
        add_header X-Frame-Options DENY always;
        add_header X-Content-Type-Options nosniff always;
        add_header X-XSS-Protection &quot;1; mode=block&quot;  always;
    }
}
```
REQUEST 1:
```
GET /myserver/my_line/84620 HTTP/1.1
Connection: Upgrade
Authorization: Basic ODQ2MjA6c3MxOE1VRUs5UEF6RTB5eHoyVmpSZ0Roc3VyV0tCcA==
User-Agent: IPOffice/WebSocketClient/
Host: x.x.x.x
Upgrade: websocket
Sec-WebSocket-Key: DhaIyjupEbTHXQvX3asVeA==
Sec-WebSocket-Protocol: Web_Proxy
Sec-WebSocket-Version: 13
Sec-WebSocket-Extensions: mux
response 1

HTTP/1.1 100 Continue
Server: nginx
Date: Mon, 09 Dec 2019 16:33:51 GMT
Connection: keep-alive
response 2

HTTP/1.1 401 Unauthorized
Date: Mon, 09 Dec 2019 16:33:51 GMT
Expires: Mon, 09 Dec 2019 16:34:51 GMT
Cache-Control: private,max-age=60
WWW-Authenticate: Digest realm=&quot;WebSocket Group@myapp&quot;, domain=&quot;&quot;, nonce=&quot;f9beaf5521a1cdf078362b68a4332df5&quot;, algorithm=MD5
Server: MyServer/
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
X-Content-Type-Options: nosniff
Strict-Transport-Security: max-age=31536000
Content-Length: 0`
```
REQUEST 2
```
GET /myserver/my_line/84620 HTTP/1.1
Connection: Upgrade
Authorization: Digest username=&quot;84620&quot;, realm=&quot;WebSocket Group@myapp&quot;, nonce=&quot;f9beaf5521a1cdf078362b68a4332df5&quot;, uri=&quot;/myserver/my_line/84620&quot;, response=&quot;5d8bb7c396724bb840da698c06f19629&quot;, algorithm=MD5, nc=00000056
User-Agent: MyApp/WebSocketClient/
Host: x.x.x.x
Upgrade: websocket
Sec-WebSocket-Key: DhaIyjupEbTHXQvX3asVeA==
Sec-WebSocket-Protocol: Web_Proxy
Sec-WebSocket-Version: 13
Sec-WebSocket-Extensions: mux
Issue -&gt; The second request is not reflected in the nginx access logs, There is nothing in error logs.
```

The nginx is supported to proxy the request to the proxied server and not do anything else own it's own. The TCP connection between client and proxy, proxy and proxied server was intact when REQUEST2 was triggered from client. I suspect the configuration at nginx has issue wrt the Connection and Upgrade headers. I have captured all wireshark at nginx and it clearly states that the tcp connection was in place and request 2 was ack by nginx. It is just that nginx is not processing it - as if it goes in blackhole and doesn't come out from it.", 'jenSupreme'),
('Jira automation smart values of github integration', "Jira automation smart values of github integration|I have the Github integration for Jira and want to set up some Jira automations integrating with Github API. What I'm looking for specifically is the possibility to get hold of either the Github PR id (or the full PR link) from the &quot;smart values&quot; library within Jira.


I've tried `issue.property[development].pr `but it doesn't return anything.


Any ideas if the PR info is exposed as smart values in Jira at all?", 'codeWarrior'),
('Call to undefined function define()', "I'm getting this quite a lot on my WP site


`PHP Fatal error:  Call to undefined function define() in index.php on line 14` 
The site works for several days (weeks in some cases), no one touches it, and then it suddenly starts logging these errors.


It's a Windows 2008 R2 box. Has PHP fallen over? How would I go about looking into it further. Restarting IIS fixes the issue.", 'beacy');


INSERT INTO `Answer` (`username`, `question_id`, `message`, `accepted`) VALUES
("lekond", 1, 'If there is a . `pyc file`, you can remove the `.py`  file, just be aware that you will lose stack trace information from these files, which will most likely mess up any error/exception logging you have.


Apart from that there is no universal way of reducing the size of a virtualenv - it will be highly dependent on the packages you have installed, and you will most likely have to resort to trial and error or reading source code to figure out exactly what you can remove.


The best you can do is look for the packages that take up the most space and then further investigate the ones that take up the most disk space. On a *nix system with the standard coreutil commands available, you can run the following command:


`du -ha /path/to/virtualenv | sort -h | tail -20`', 0),
("jesperrn", 1, 'When you create your virtualenv you can tell it to use your system site_packages. If you installed all required packages globally on the system, when you created your virtualenv, it would essentially be empty.


```
$ pip install package1 package2 ...
$ virtualenv --system-site-pacages venv
$ source venv/bin/activate
(venv) $ 
```
now you can use package1, package2, ...
With this method you can overinstall a package. If, inside your virtualenv, you install a package, that will be used instead of whatever is on the system.', 0),
("connys", 2, 'If you look at your Request 1, there is no body but you are getting a 100 continue response from the proxied server, which I believe is due to Expect request-header &quot;100-continue&quot; added by Nginx (probably due to the config entry proxy_set_header Expect $http_expect;), but this is wrong as per the spec.


[https://www.w3.org/Protocols/rfc2616/rfc2616-sec8.html](https://www.w3.org/Protocols/rfc2616/rfc2616-sec8.html)


A client MUST NOT send an Expect request-header field (section 14.20) with the &quot;100-continue&quot; expectation if it does not intend to send a request body.
This might be the reason for the hang, as the server might be waiting for a body (for Request 1) that is never going to arrive.', 0),
("jesperrn", 2, 'Point to be noted here is there is a difference in the way websocket proxy is used - [nginx.com/blog/websocket-nginx](nginx.com/blog/websocket-nginx). Here there are multiple HTTP requests flowing on the same tcp connection and then it is supposed to be upgraded to ws.', 0),
("jesperrn", 3, 'All the GitHub related data would be present in the webhookData smart value, which will contain the data sent by GitHub’s webhook.

So to access the PR’s URL, you can use webhookData.pull_request.html_url as shown in the screenshot below. Similarly, you can access any other field sent by GitHub’s webhook payload via webhookData.', 0),
("dreyJimi", 4, "Well, define is one of the core functions of PHP. Since line 14 of your code is the first function call and it is giving error on that line, therefore, your error is not about define function and you would get error for any function call. You can simply check this scenario by calling other core functions before that line.


By that finding, I was able to find a few related questions about fatal errors in IIS. It seems like your problem is duo to php.ini configuration file. Please read the following solutions:


* [https://stackoverflow.com/questions/4817944/call-to-undefined-function-mysql-connect](https://stackoverflow.com/questions/4817944/call-to-undefined-function-mysql-connect)
* [https://stackoverflow.com/questions/52438327/call-to-undefined-function-ocilogon-iis-7-5-windows-2008-r2-x64-php-7-2](https://stackoverflow.com/questions/52438327/call-to-undefined-function-ocilogon-iis-7-5-windows-2008-r2-x64-php-7-2)

If by any chance, that didn't work, it could be because of conflicts by your WP plugins. I'm not sure which plugins you have installed, but here are some related topics that could help you. You may be able to solve it by disabling some plugins:


* [https://buddypress.org/support/topic/getting-a-php-fatal-error-with-bp-core-functions/](https://buddypress.org/support/topic/getting-a-php-fatal-error-with-bp-core-functions/)
* [https://wordpress.org/support/topic/fatal-error-3152/](https://wordpress.org/support/topic/fatal-error-3152/)
* [https://wordpress.org/support/topic/php-fatal-error-enabling-paired-mode/](https://wordpress.org/support/topic/php-fatal-error-enabling-paired-mode/)

You should realize that it is really hard to determine your problem without knowing more details about your PHP installation and WP configuration.", 0),
("jesperrn", 4, "I had a similar problem and another `define()` solved it for me. The problem was a memory issue.


Please take a look at your php error_log to verify a memory issue.


Open your `wp-setting.php` file and add the below line almost on top of the file.


`define( 'WP_MEMORY_LIMIT', '40M' );// you can try it with more or a bit less memory`", 0);


INSERT INTO `Comment` (`username`, `question_id`, `answer_id`, `message`) VALUES
("twistedsoul", 1, 2, 'this would mean to "outsource the packages to the system. However, I would need to relocate the virtualenv to another system that might not have the packes installed and where no root permission are available.'),
("jesperrn", 1, 2, "Relocating the virtualenv to another system won't work anyway, because virtualenvs contain system specific paths."),
("twistedsoul", 1, 2, 'Its no problem to relocated a virtualenv with `virtualenv --relocatable my-venv`. I did it many times. Check it out here: [stackoverflow.com/questions/32407365/can-i-move-a-virtualenv](stackoverflow.com/questions/32407365/can-i-move-a-virtualenv)'),
("rHampthon", 3, 5, 'Did this help you?'),
("codeWarrior", 3, 5, 'Unfortionatly no. The thing is that Im not looking for how to process incoming webhook. Im trying to access the issue PR id (exist in the jira issue) for an outgoing webhook..');


INSERT INTO `Tag` (`tag`, `question_id`) VALUES
('stupid', 1),
('cucumbers', 1),
('nginx', 2),
('websocket', 2),
('nginx-config', 2),
('automation', 3),
('github', 3),
('jira', 3),
('php', 4),
('stupid', 4),
('iis', 4),
('windows', 4);