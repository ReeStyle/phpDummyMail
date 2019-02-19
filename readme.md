# Usage

Installation is simple:
- Unpack (or clone with git) into a folder 
- Run 'composer install' (DO NOT run 'composer update'!)
- Edit your php.ini (both CGI and CLI!) and edit the following line:
    - sendmail_path = \[path_to_phpdummymail]/receiver.php
- Run: php tester.php
- Create an alias to the \[path_to_phpdummymail]/htdocs/ in your 
apache config (or whatever flavor webservice you're using)
and set index.php as primary file to invoke
- It is PHP5.6 and PHP7 compatible: We use the Class::class which is only present in 
versions from PHP5.6 on
  

Done, it should already be running


# License

Use and distribution of this software requires you to abide these simple rules:


The license states 'FREE' and I want it to stay 'FREE': Free of use.

I do not grant you the privilege to charge others for the use this software, 
other than for support or initial installation. A man (or woman) has to make a living.

I do grant you the freedom to use this and/or its components in whatever product 
you wish create; I also grant you the freedom to take this software, change it 
into anything you like; As long as you do NOT charge any entity, whether a business
or real person, for the use of this software other than stated before.

I will not hold me responsible for any damage, monetary or otherwise, when something 
goes wrong while you use this software. 

If you wish to donate to the project, you can transfer the funds to my PayPal account.


Remember that this software is primarily aimed at development enthusiasts that need
a simple mail catching tool to see what their application produces when it's actually 
sent.

# Lastly

Have fun.